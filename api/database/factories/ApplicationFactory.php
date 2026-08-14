<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationTheme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => '+998 (90) '.fake()->numerify('###-##-##'),
            'email' => fake()->safeEmail(),
            'theme_id' => ApplicationTheme::factory(),
            'message' => fake()->sentence(),
            'status' => Application::STATUS_NEW,
            'ip_address' => fake()->ipv4(),
        ];
    }
}
