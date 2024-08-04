<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'publication_date' => $this->faker->date(),
            'slug' => $this->faker->slug(),
            'meta_description' => $this->faker->sentence(),
            'is_featured' => $this->faker->boolean(),
            'user_id' => $this->faker->numberBetween(1, 2),
        ];
    }
}
