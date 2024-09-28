<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FilterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'options' => $this->generateRandomOptions(),
        ];
    }

    protected function generateRandomOptions($count = 5): array
    {
        return array_map(fn () => fake()->word(), range(1, $count));
    }
}
