<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subtheme>
 */
class SubthemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'theme_id' => null, // Поле theme_id будем задавать при создании через связь
            'name'     => $this->faker->word,
            'content'  => $this->faker->sentence,
        ];
    }
}
