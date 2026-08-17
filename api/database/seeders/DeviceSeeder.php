<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Network;
use App\Models\Device;
use App\Models\DeviceBrand;
use App\Models\DeviceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class DeviceSeeder extends Seeder
{
    private const CATEGORIES = [
        'cdma' => ['name' => ['ru' => 'Устройства CDMA', 'uz' => 'CDMA qurilmalari'], 'network' => Network::Cdma, 'sort' => 1],
        '5g' => ['name' => ['ru' => 'Устройства 5G SA', 'uz' => '5G SA qurilmalari'], 'network' => Network::FiveG, 'sort' => 2],
        'routers' => ['name' => ['ru' => 'Роутеры', 'uz' => 'Routerlar'], 'network' => Network::FiveG, 'sort' => 3],
    ];

    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/devices.json')), true);

        $categories = $this->seedCategories();
        $brands = $this->seedBrands($data['brands'] ?? []);

        foreach ($data['models'] ?? [] as $sort => $model) {
            Device::updateOrCreate(['slug' => $model['slug']], [
                ...Arr::except($model, ['category', 'brand', 'slug', 'sort']),
                ...$this->extras($model['slug']),
                'category_id' => $categories[$model['category']] ?? null,
                'brand_id' => $brands[$model['brand']] ?? null,
                'sort' => $sort + 1,
                'status' => true,
            ]);
        }
    }

    /**
     * @return array<string, int>
     */
    private function seedCategories(): array
    {
        return collect(self::CATEGORIES)
            ->map(fn (array $category): int => DeviceCategory::updateOrCreate(
                ['name->ru' => $category['name']['ru']],
                [...$category, 'status' => true],
            )->getKey())
            ->all();
    }

    /**
     * The catalogue browses CDMA brand by brand, so a brand is a record with a
     * logo an editor can replace rather than a name typed onto every device.
     *
     * @param  array<int, array<string, mixed>>  $brands
     * @return array<string, int>
     */
    private function seedBrands(array $brands): array
    {
        return collect($brands)
            ->mapWithKeys(fn (array $brand): array => [
                $brand['slug'] => DeviceBrand::updateOrCreate(
                    ['slug' => $brand['slug']],
                    ['name' => $brand['name'], 'sort' => $brand['sort'], 'status' => true],
                )->getKey(),
            ])
            ->all();
    }

    /**
     * The old catalogue listed the specification headings and none of their
     * values, so the router the site actually sells keeps its own table and
     * the photo that ships with the seeder.
     *
     * @return array<string, mixed>
     */
    private function extras(string $slug): array
    {
        return match ($slug) {
            'tozed-zlt-x25-max2' => [
                'specs' => $this->tozedSpecs(),
                'image' => $this->attachImage('tozed-zlt-x25-max2.jpg'),
            ],
            default => [],
        };
    }

    /**
     * @return array<int, array<string, array<string, string>>>
     */
    private function tozedSpecs(): array
    {
        $rows = [
            ['Тип устройства:', 'Qurilma turi:', 'Домашний 5G роутер (Indoor CPE)', 'Uy 5G routeri (Indoor CPE)'],
            ['Сетевой стандарт:', 'Tarmoq standarti:', '5G NR SA/NSA, 4G LTE Cat 19, Wi-Fi 6 (AX3000)', '5G NR SA/NSA, 4G LTE Cat 19, Wi-Fi 6 (AX3000)'],
            ['Мощность передатчика:', 'Uzatgich quvvati:', 'До 23 dBm', '23 dBm gacha'],
            ['Полосы частот:', 'Chastota polosalari:', 'до 200 МГц (5G SA), поддержка агрегации частот (2CA NR, 5CC LTE)', '200 MGts gacha (5G SA), chastota agregatsiyasi (2CA NR, 5CC LTE)'],
            ['Количество доступных пользователей:', 'Foydalanuvchilar soni:', 'до 128 устройств по Wi-Fi (64 на 2.4 ГГц + 64 на 5 ГГц)', 'Wi-Fi orqali 128 tagacha qurilma (2.4 GGts da 64 + 5 GGts da 64)'],
            ['Пропускная способность:', 'Oʻtkazish qobiliyati:', 'До 4.7 Гбит/с (загрузка), до 1.25 Гбит/с (отдача)', '4.7 Gbit/s gacha (yuklab olish), 1.25 Gbit/s gacha (yuklash)'],
            ['Дополнительные функции:', 'Qoʻshimcha funksiyalar:', 'CA (5CC LTE, 2CA NR), MIMO 4×4 DL, Mesh, WPS', 'CA (5CC LTE, 2CA NR), MIMO 4×4 DL, Mesh, WPS'],
            ['Доступные порты:', 'Mavjud portlar:', '2 × 2.5GE LAN/WAN, 1 × RJ-11 (опционально)', '2 × 2.5GE LAN/WAN, 1 × RJ-11 (ixtiyoriy)'],
            ['Безопасность:', 'Xavfsizlik:', 'WPA2/WPA3, NAT, VPN, TR-069', 'WPA2/WPA3, NAT, VPN, TR-069'],
            ['Тип антенн:', 'Antenna turi:', 'Встроенные', 'Ichki'],
            ['Крепление:', 'Oʻrnatish:', 'Настольное размещение', 'Stol usti joylashuvi'],
            ['Размеры:', 'Oʻlchamlari:', '190 × 90 × 90 мм', '190 × 90 × 90 mm'],
            ['Вес:', 'Vazni:', '500 г', '500 g'],
            ['Материал корпуса:', 'Korpus materiali:', 'Пластик', 'Plastik'],
        ];

        return array_map(fn (array $row): array => [
            'label' => ['ru' => $row[0], 'uz' => $row[1]],
            'value' => ['ru' => $row[2], 'uz' => $row[3]],
        ], $rows);
    }

    /**
     * The photo ships with the seeder so a fresh install renders the card the
     * moment it is up; a file already uploaded through the admin stays as is.
     */
    private function attachImage(string $file): string
    {
        $disk = Storage::disk('public');
        $path = 'uploads/devices/'.$file;

        if (! $disk->exists($path)) {
            $disk->put($path, (string) file_get_contents(database_path('data/devices/'.$file)));
        }

        return $path;
    }
}
