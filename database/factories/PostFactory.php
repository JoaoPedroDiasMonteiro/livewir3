<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->realTextBetween(10, 20),
            'content' => $this->faker->realText(),
        ];
    }
}
