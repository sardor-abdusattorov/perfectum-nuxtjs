<?php

namespace Database\Factories;

use App\Models\ApplicationTheme;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ApplicationTheme>
 */
class ApplicationThemeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ['ru' => $name, 'uz' => $name],
            'slug' => Str::slug($name),
            'sort' => 1,
            'status' => true,
        ];
    }
}
