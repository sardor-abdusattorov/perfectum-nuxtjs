<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceInstallment;
use App\Models\InstallmentPartner;
use Illuminate\Database\Seeder;

/**
 * The old site quoted the instalment plans partner by partner, with the
 * monthly payment typed in by hand for every term. The figures move with the
 * partners' rates, so they are content: seeded once and edited in the admin.
 */
class InstallmentSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/installments.json')), true);

        $partners = collect($data['partners'] ?? [])
            ->mapWithKeys(fn (array $partner): array => [
                $partner['slug'] => InstallmentPartner::updateOrCreate(
                    ['slug' => $partner['slug']],
                    [
                        'name' => $partner['name'],
                        'logo' => $partner['logo'] ?? null,
                        'sort' => $partner['sort'],
                        'status' => true,
                    ],
                )->getKey(),
            ]);

        foreach ($data['devices'] ?? [] as $row) {
            $device = Device::query()->where('slug', $row['device'])->first();
            $partner = $partners[$row['partner']] ?? null;

            if ($device === null || $partner === null) {
                continue;
            }

            DeviceInstallment::updateOrCreate(
                ['device_id' => $device->getKey(), 'installment_partner_id' => $partner],
                ['options' => $row['options'], 'sort' => $row['sort']],
            );
        }
    }
}
