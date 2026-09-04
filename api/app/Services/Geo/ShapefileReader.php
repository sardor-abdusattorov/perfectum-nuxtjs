<?php

declare(strict_types=1);

namespace App\Services\Geo;

use RuntimeException;
use ZipArchive;

class ShapefileReader
{
    private const TYPE_POLYLINE = 3;

    private const TYPE_POLYGON = 5;

    private const TYPE_POLYLINE_Z = 13;

    private const TYPE_POLYGON_Z = 15;

    private const EARTH_RADIUS = 6378137.0;

    private const WGS84_FLATTENING = 1 / 298.257223563;

    /**
     * Four decimals is about eleven metres, and the coverage contours are traced
     * off a twenty-metre raster grid — the fifth and sixth decimals carry no
     * measurement, only the remainder of the reprojection. They cost real
     * traffic: those digits are close to random, so gzip cannot pack them, and
     * the layer went over the wire more than twice the weight of the same
     * shapes written as whole numbers.
     */
    private const PRECISION = 4;

    /**
     * @return array<string, mixed>
     */
    public function fromZip(string $path): array
    {
        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            throw new RuntimeException('The archive could not be opened');
        }

        try {
            $shape = $this->extract($zip, '.shp');
            $projection = $this->extract($zip, '.prj');
        } finally {
            $zip->close();
        }

        if ($shape === null) {
            throw new RuntimeException('The archive holds no .shp file');
        }

        return $this->parse($shape, (string) $projection);
    }

    private function extract(ZipArchive $zip, string $extension): ?string
    {
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $name = (string) $zip->getNameIndex($index);

            if (str_starts_with(basename($name), '.')) {
                continue;
            }

            if (str_ends_with(strtolower($name), $extension)) {
                return (string) $zip->getFromIndex($index);
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function parse(string $shape, string $projection): array
    {
        $project = $this->projector($projection);
        $metric = $project !== null;

        $features = [];
        $offset = 100;
        $length = strlen($shape);

        while ($offset + 8 <= $length) {
            $header = unpack('Nnumber/Nwords', substr($shape, $offset, 8));
            $size = $header['words'] * 2;
            $record = substr($shape, $offset + 8, $size);
            $offset += 8 + $size;

            if (strlen($record) < 4) {
                continue;
            }

            $type = unpack('Vtype', substr($record, 0, 4))['type'];

            if (! in_array($type, [self::TYPE_POLYLINE, self::TYPE_POLYGON, self::TYPE_POLYLINE_Z, self::TYPE_POLYGON_Z], true)) {
                continue;
            }

            $rings = $this->rings($record);

            if ($rings === []) {
                continue;
            }

            if (in_array($type, [self::TYPE_POLYLINE, self::TYPE_POLYLINE_Z], true)) {
                $features[] = $this->feature('LineString', $this->convert($rings, $project)[0]);

                continue;
            }

            if ($metric) {
                $rings = $this->mergeGridCells($rings);
            }

            $polygons = array_map(
                fn (array $ring): array => [$ring],
                $this->convert($rings, $project),
            );

            $features[] = count($polygons) === 1
                ? $this->feature('Polygon', $polygons[0])
                : $this->feature('MultiPolygon', $polygons);
        }

        return ['type' => 'FeatureCollection', 'features' => $features];
    }

    /**
     * @param  array<int, array<int, array<int, float>>>  $coordinates
     * @return array<string, mixed>
     */
    private function feature(string $type, array $coordinates): array
    {
        return [
            'type' => 'Feature',
            'properties' => (object) [],
            'geometry' => [
                'type' => $type,
                'coordinates' => $coordinates,
            ],
        ];
    }

    /**
     * @return array<int, array<int, array<int, float>>>
     */
    private function rings(string $record): array
    {
        $meta = unpack('Vtype/dminX/dminY/dmaxX/dmaxY/Vparts/Vpoints', substr($record, 0, 44));
        $partCount = $meta['parts'];
        $pointCount = $meta['points'];

        $partsAt = 44;
        $pointsAt = $partsAt + $partCount * 4;

        if ($partCount < 1 || strlen($record) < $pointsAt + $pointCount * 16) {
            return [];
        }

        $starts = array_values(unpack("V{$partCount}", substr($record, $partsAt, $partCount * 4)));
        $starts[] = $pointCount;

        $rings = [];

        for ($part = 0; $part < $partCount; $part++) {
            $ring = [];

            for ($point = $starts[$part]; $point < $starts[$part + 1]; $point++) {
                $pair = unpack('dx/dy', substr($record, $pointsAt + $point * 16, 16));

                $ring[] = [$pair['x'], $pair['y']];
            }

            if (count($ring) > 1) {
                $rings[] = $ring;
            }
        }

        return $rings;
    }

    /**
     * @param  array<int, array<int, array<int, float>>>  $rings
     * @return array<int, array<int, array<int, float>>>
     */
    private function convert(array $rings, ?callable $project): array
    {
        return array_map(
            fn (array $ring): array => $this->dropRepeats(array_map(
                fn (array $point): array => $project === null
                    ? [round($point[0], self::PRECISION), round($point[1], self::PRECISION)]
                    : $project($point[0], $point[1]),
                $ring,
            )),
            $rings,
        );
    }

    /**
     * Rounding pulls neighbours that stood a few centimetres apart onto the same
     * spot. Repeating a point draws nothing and only adds weight; the closing
     * point of a ring is not a repeat of its neighbour, so it stays.
     *
     * @param  array<int, array<int, float>>  $ring
     * @return array<int, array<int, float>>
     */
    private function dropRepeats(array $ring): array
    {
        $kept = [];

        foreach ($ring as $point) {
            if ($kept !== [] && end($kept) === $point) {
                continue;
            }

            $kept[] = $point;
        }

        return count($kept) > 1 ? $kept : $ring;
    }

    private function projector(string $projection): ?callable
    {
        if (str_contains($projection, '3857')
            || str_contains($projection, 'Web_Mercator')
            || str_contains($projection, 'Pseudo-Mercator')) {
            return fn (float $x, float $y): array => $this->fromMercator($x, $y);
        }

        if (str_contains($projection, 'Transverse_Mercator') || str_contains($projection, 'UTM_Zone')) {
            $parameters = [
                'central_meridian' => deg2rad($this->parameter($projection, 'central_meridian', 0.0)),
                'scale_factor' => $this->parameter($projection, 'scale_factor', 0.9996),
                'false_easting' => $this->parameter($projection, 'false_easting', 500000.0),
                'false_northing' => $this->parameter($projection, 'false_northing', 0.0),
            ];

            return fn (float $x, float $y): array => $this->fromTransverseMercator($x, $y, $parameters);
        }

        return null;
    }

    private function parameter(string $projection, string $name, float $default): float
    {
        return preg_match('/PARAMETER\["'.$name.'",([-\d.]+)\]/i', $projection, $match)
            ? (float) $match[1]
            : $default;
    }

    /**
     * @return array<int, float>
     */
    private function fromMercator(float $x, float $y): array
    {
        return [
            round($x / self::EARTH_RADIUS * 180 / M_PI, self::PRECISION),
            round((2 * atan(exp($y / self::EARTH_RADIUS)) - M_PI_2) * 180 / M_PI, self::PRECISION),
        ];
    }

    /**
     * @param  array{central_meridian: float, scale_factor: float, false_easting: float, false_northing: float}  $p
     * @return array<int, float>
     */
    private function fromTransverseMercator(float $x, float $y, array $p): array
    {
        $a = self::EARTH_RADIUS;
        $f = self::WGS84_FLATTENING;
        $e2 = $f * (2 - $f);
        $ep2 = $e2 / (1 - $e2);

        $m = ($y - $p['false_northing']) / $p['scale_factor'];
        $mu = $m / ($a * (1 - $e2 / 4 - 3 * $e2 ** 2 / 64 - 5 * $e2 ** 3 / 256));

        $e1 = (1 - sqrt(1 - $e2)) / (1 + sqrt(1 - $e2));

        $phi = $mu
            + (3 * $e1 / 2 - 27 * $e1 ** 3 / 32) * sin(2 * $mu)
            + (21 * $e1 ** 2 / 16 - 55 * $e1 ** 4 / 32) * sin(4 * $mu)
            + (151 * $e1 ** 3 / 96) * sin(6 * $mu)
            + (1097 * $e1 ** 4 / 512) * sin(8 * $mu);

        $sin = sin($phi);
        $cos = cos($phi);
        $tan = $sin / $cos;

        $c1 = $ep2 * $cos ** 2;
        $t1 = $tan ** 2;
        $n1 = $a / sqrt(1 - $e2 * $sin ** 2);
        $r1 = $a * (1 - $e2) / (1 - $e2 * $sin ** 2) ** 1.5;
        $d = ($x - $p['false_easting']) / ($n1 * $p['scale_factor']);

        $latitude = $phi - ($n1 * $tan / $r1) * (
            $d ** 2 / 2
            - (5 + 3 * $t1 + 10 * $c1 - 4 * $c1 ** 2 - 9 * $ep2) * $d ** 4 / 24
            + (61 + 90 * $t1 + 298 * $c1 + 45 * $t1 ** 2 - 252 * $ep2 - 3 * $c1 ** 2) * $d ** 6 / 720
        );

        $longitude = $p['central_meridian'] + (
            $d
            - (1 + 2 * $t1 + $c1) * $d ** 3 / 6
            + (5 - 2 * $c1 + 28 * $t1 - 3 * $c1 ** 2 + 8 * $ep2 + 24 * $t1 ** 2) * $d ** 5 / 120
        ) / $cos;

        return [round(rad2deg($longitude), self::PRECISION), round(rad2deg($latitude), self::PRECISION)];
    }

    /**
     * @param  array<int, array<int, array<int, float>>>  $rings
     * @return array<int, array<int, array<int, float>>>
     */
    private function mergeGridCells(array $rings): array
    {
        [$cells, $others, $pitch] = $this->splitGridCells($rings);

        if ($pitch === null || count($cells) < 2) {
            return $rings;
        }

        $columns = [];

        foreach ($cells as [$x, $y]) {
            $columns[$x][] = $y;
        }

        $merged = [];

        foreach (array_keys($columns) as $x) {
            sort($columns[$x]);

            $run = [$columns[$x][0], $columns[$x][0]];

            foreach (array_slice($columns[$x], 1) as $y) {
                if ($y === $run[1] + 1) {
                    $run[1] = $y;

                    continue;
                }

                $merged[] = [$x, $run[0], $run[1]];
                $run = [$y, $y];
            }

            $merged[] = [$x, $run[0], $run[1]];
        }

        $rectangles = array_map(
            fn (array $column): array => $this->rectangle(
                $column[0] * $pitch,
                $column[1] * $pitch,
                ($column[0] + 1) * $pitch,
                ($column[2] + 1) * $pitch,
            ),
            $merged,
        );

        return [...$rectangles, ...$others];
    }

    /**
     * @param  array<int, array<int, array<int, float>>>  $rings
     * @return array{0: array<int, array<int, int>>, 1: array<int, array<int, array<int, float>>>, 2: int|null}
     */
    private function splitGridCells(array $rings): array
    {
        $pitch = null;
        $candidates = [];
        $others = [];

        foreach ($rings as $ring) {
            $box = $this->squareBox($ring);

            if ($box === null) {
                $others[] = $ring;

                continue;
            }

            $candidates[] = $box;
            $pitch = $pitch === null ? $box[2] : min($pitch, $box[2]);
        }

        if ($pitch === null || $pitch <= 0) {
            return [[], $rings, null];
        }

        $cells = [];

        foreach ($candidates as $box) {
            if ($box[2] !== $pitch
                || ((int) $box[0]) % $pitch !== 0
                || ((int) $box[1]) % $pitch !== 0) {
                $others[] = $this->rectangle($box[0], $box[1], $box[0] + $box[2], $box[1] + $box[2]);

                continue;
            }

            $cells[] = [intdiv((int) $box[0], $pitch), intdiv((int) $box[1], $pitch)];
        }

        return [$cells, $others, $pitch];
    }

    /**
     * @param  array<int, array<int, array<int, float>>>  $ring
     * @return array{0: float, 1: float, 2: int}|null
     */
    private function squareBox(array $ring): ?array
    {
        if (count($ring) > 6) {
            return null;
        }

        $xs = [];
        $ys = [];

        foreach ($ring as [$x, $y]) {
            if ($x !== floor($x) || $y !== floor($y)) {
                return null;
            }

            $xs[$x] = true;
            $ys[$y] = true;
        }

        if (count($xs) !== 2 || count($ys) !== 2) {
            return null;
        }

        $minX = min(array_keys($xs));
        $minY = min(array_keys($ys));
        $width = (int) (max(array_keys($xs)) - $minX);

        if ($width !== (int) (max(array_keys($ys)) - $minY)) {
            return null;
        }

        return [$minX, $minY, $width];
    }

    /**
     * @return array<int, array<int, float>>
     */
    private function rectangle(float $minX, float $minY, float $maxX, float $maxY): array
    {
        return [
            [$minX, $minY],
            [$maxX, $minY],
            [$maxX, $maxY],
            [$minX, $maxY],
            [$minX, $minY],
        ];
    }
}
