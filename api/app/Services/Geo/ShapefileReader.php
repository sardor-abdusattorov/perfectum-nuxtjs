<?php

declare(strict_types=1);

namespace App\Services\Geo;

use RuntimeException;
use ZipArchive;

/**
 * Turns the zipped ESRI shapefile the network team exports into GeoJSON.
 *
 * Only the geometry file is read: the map draws outlines, so the attribute
 * table beside it has nothing the site shows. Polygons and polylines are
 * supported because that is what a coverage export contains.
 */
class ShapefileReader
{
    private const TYPE_POLYLINE = 3;

    private const TYPE_POLYGON = 5;

    private const TYPE_POLYLINE_Z = 13;

    private const TYPE_POLYGON_Z = 15;

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

        return $this->parse($shape, $this->isMercator((string) $projection));
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
     * A coverage export arrives either in degrees or in the web mercator
     * metres the tiling uses; anything else is left to the caller to notice.
     */
    private function isMercator(string $projection): bool
    {
        return str_contains($projection, '3857')
            || str_contains($projection, 'Web_Mercator')
            || str_contains($projection, 'Pseudo-Mercator');
    }

    /**
     * @return array<string, mixed>
     */
    private function parse(string $shape, bool $isMercator): array
    {
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

            $rings = $this->rings($record, $isMercator);

            if ($rings === []) {
                continue;
            }

            $isPolygon = in_array($type, [self::TYPE_POLYGON, self::TYPE_POLYGON_Z], true);

            $features[] = [
                'type' => 'Feature',
                'properties' => (object) [],
                'geometry' => [
                    'type' => $isPolygon ? 'Polygon' : 'LineString',
                    'coordinates' => $isPolygon ? $rings : $rings[0],
                ],
            ];
        }

        return ['type' => 'FeatureCollection', 'features' => $features];
    }

    /**
     * @return array<int, array<int, array<int, float>>>
     */
    private function rings(string $record, bool $isMercator): array
    {
        $meta = unpack('Vtype/dminX/dminY/dmaxX/dmaxY/Vparts/Vpoints', substr($record, 0, 44));
        $partCount = $meta['parts'];
        $pointCount = $meta['points'];

        $partsAt = 44;
        $pointsAt = $partsAt + $partCount * 4;

        if (strlen($record) < $pointsAt + $pointCount * 16) {
            return [];
        }

        $starts = array_values(unpack("V{$partCount}", substr($record, $partsAt, $partCount * 4)));
        $starts[] = $pointCount;

        $rings = [];

        for ($part = 0; $part < $partCount; $part++) {
            $ring = [];

            for ($point = $starts[$part]; $point < $starts[$part + 1]; $point++) {
                $pair = unpack('dx/dy', substr($record, $pointsAt + $point * 16, 16));

                $ring[] = $isMercator
                    ? $this->fromMercator($pair['x'], $pair['y'])
                    : [round($pair['x'], 6), round($pair['y'], 6)];
            }

            if (count($ring) > 1) {
                $rings[] = $ring;
            }
        }

        return $rings;
    }

    /**
     * @return array<int, float>
     */
    private function fromMercator(float $x, float $y): array
    {
        $radius = 6378137.0;

        return [
            round($x / $radius * 180 / M_PI, 6),
            round((2 * atan(exp($y / $radius)) - M_PI_2) * 180 / M_PI, 6),
        ];
    }
}
