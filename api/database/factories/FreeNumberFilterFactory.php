<?php

namespace Database\Factories;

use App\Models\FreeNumberFilter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FreeNumberFilter>
 */
class FreeNumberFilterFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $value = fake()->unique()->numerify('###');

        return [
            'type' => FreeNumberFilter::TYPE_PREFIX,
            'name' => $value,
            'value' => $value,
            'status' => true,
        ];
    }

    public function price(int $price): static
    {
        return $this->state([
            'type' => FreeNumberFilter::TYPE_PRICE,
            'name' => number_format($price, 0, '', ' '),
            'value' => (string) $price,
        ]);
    }
}
