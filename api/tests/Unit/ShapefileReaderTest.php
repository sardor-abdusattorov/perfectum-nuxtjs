<?php

declare(strict_types=1);

use App\Services\Geo\ShapefileReader;

function shapefileZip(array $rings, string $projection, int $type = 5): string
{
    $records = '';

    foreach ($rings as $number => $ring) {
        $body = pack('V', $type);
        $xs = array_column($ring, 0);
        $ys = array_column($ring, 1);
        $body .= pack('d4', min($xs), min($ys), max($xs), max($ys));
        $body .= pack('V2', 1, count($ring));
        $body .= pack('V', 0);

        foreach ($ring as [$x, $y]) {
            $body .= pack('d2', $x, $y);
        }

        $records .= pack('N2', $number + 1, strlen($body) / 2).$body;
    }

    $header = pack('N', 9994).str_repeat("\0", 20).pack('N', (100 + strlen($records)) / 2);
    $header .= pack('V2', 1000, $type);
    $header .= pack('d8', 0, 0, 0, 0, 0, 0, 0, 0);

    $path = tempnam(sys_get_temp_dir(), 'shp').'.zip';
    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('coverage.shp', $header.$records);
    $zip->addFromString('coverage.prj', $projection);
    $zip->close();

    return $path;
}

function shapefileZipMultipart(array $rings, string $projection): string
{
    $points = array_sum(array_map('count', $rings));
    $xs = [];
    $ys = [];

    foreach ($rings as $ring) {
        foreach ($ring as [$x, $y]) {
            $xs[] = $x;
            $ys[] = $y;
        }
    }

    $body = pack('V', 5);
    $body .= pack('d4', min($xs), min($ys), max($xs), max($ys));
    $body .= pack('V2', count($rings), $points);

    $start = 0;

    foreach ($rings as $ring) {
        $body .= pack('V', $start);
        $start += count($ring);
    }

    foreach ($rings as $ring) {
        foreach ($ring as [$x, $y]) {
            $body .= pack('d2', $x, $y);
        }
    }

    $records = pack('N2', 1, strlen($body) / 2).$body;

    $header = pack('N', 9994).str_repeat("\0", 20).pack('N', (100 + strlen($records)) / 2);
    $header .= pack('V2', 1000, 5);
    $header .= pack('d8', 0, 0, 0, 0, 0, 0, 0, 0);

    $path = tempnam(sys_get_temp_dir(), 'shp').'.zip';
    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('coverage.shp', $header.$records);
    $zip->addFromString('coverage.prj', $projection);
    $zip->close();

    return $path;
}

it('reads a polygon in degrees', function (): void {
    $path = shapefileZip([[[69.1, 41.2], [69.4, 41.2], [69.4, 41.4], [69.1, 41.2]]], 'GEOGCS["GCS_WGS_1984"]');

    $geojson = (new ShapefileReader)->fromZip($path);

    expect($geojson['type'])->toBe('FeatureCollection');
    expect($geojson['features'])->toHaveCount(1);
    expect($geojson['features'][0]['geometry']['type'])->toBe('Polygon');
    expect($geojson['features'][0]['geometry']['coordinates'][0][0])->toBe([69.1, 41.2]);

    unlink($path);
});

it('converts web mercator metres into degrees', function (): void {
    $path = shapefileZip(
        [[[7692529.0, 5039789.0], [7700000.0, 5039789.0], [7700000.0, 5045000.0], [7692529.0, 5039789.0]]],
        'PROJCS["WGS_1984_Web_Mercator_Auxiliary_Sphere",AUTHORITY["EPSG","3857"]]'
    );

    $point = (new ShapefileReader)->fromZip($path)['features'][0]['geometry']['coordinates'][0][0];

    expect($point[0])->toBeGreaterThan(69.0)->toBeLessThan(69.2);
    expect($point[1])->toBeGreaterThan(41.1)->toBeLessThan(41.4);

    unlink($path);
});

it('reads several shapes out of one archive', function (): void {
    $path = shapefileZip([
        [[69.1, 41.2], [69.4, 41.2], [69.4, 41.4], [69.1, 41.2]],
        [[66.9, 39.6], [67.1, 39.6], [67.1, 39.8], [66.9, 39.6]],
    ], 'GEOGCS["GCS_WGS_1984"]');

    expect((new ShapefileReader)->fromZip($path)['features'])->toHaveCount(2);

    unlink($path);
});

it('refuses an archive without a shapefile', function (): void {
    $path = tempnam(sys_get_temp_dir(), 'shp').'.zip';
    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('readme.txt', 'nothing here');
    $zip->close();

    expect(fn () => (new ShapefileReader)->fromZip($path))
        ->toThrow(RuntimeException::class, 'The archive holds no .shp file');

    unlink($path);
});

it('refuses a file that is not an archive', function (): void {
    $path = tempnam(sys_get_temp_dir(), 'shp');
    file_put_contents($path, 'plain text');

    expect(fn () => (new ShapefileReader)->fromZip($path))
        ->toThrow(RuntimeException::class, 'The archive could not be opened');

    unlink($path);
});

it('converts utm metres into degrees', function (): void {
    // a point sitting on the zone's central meridian resolves to exactly 63°E
    $path = shapefileZip(
        [[[500000.0, 4573000.0], [500020.0, 4573000.0], [500020.0, 4573020.0], [500000.0, 4573000.0]]],
        'PROJCS["WGS_1984_UTM_Zone_41N",PROJECTION["Transverse_Mercator"],PARAMETER["central_meridian",63],PARAMETER["scale_factor",0.9996],PARAMETER["false_easting",500000],PARAMETER["false_northing",0]]'
    );

    $point = (new ShapefileReader)->fromZip($path)['features'][0]['geometry']['coordinates'][0][0];

    expect(abs($point[0] - 63.0))->toBeLessThan(0.0001);
    expect($point[1])->toBeGreaterThan(41.2)->toBeLessThan(41.4);

    unlink($path);
});

it('collapses touching grid squares into one rectangle', function (): void {
    $cell = fn (float $x, float $y): array => [[$x, $y], [$x + 20, $y], [$x + 20, $y + 20], [$x, $y + 20], [$x, $y]];

    $path = shapefileZipMultipart(
        [$cell(500000.0, 4573000.0), $cell(500000.0, 4573020.0), $cell(500000.0, 4573040.0), $cell(500100.0, 4573000.0)],
        'PROJCS["WGS_1984_UTM_Zone_41N",PROJECTION["Transverse_Mercator"],PARAMETER["central_meridian",63],PARAMETER["scale_factor",0.9996],PARAMETER["false_easting",500000],PARAMETER["false_northing",0]]'
    );

    $geometry = (new ShapefileReader)->fromZip($path)['features'][0]['geometry'];

    expect($geometry['type'])->toBe('MultiPolygon');
    expect($geometry['coordinates'])->toHaveCount(2);

    unlink($path);
});
