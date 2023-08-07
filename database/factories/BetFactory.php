<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'game_id' => Game::query()->exists() ? Game::query()->inRandomOrder()->first() : Game::factory()->create(),
            'user_id' => User::query()->exists() ? User::query()->inRandomOrder()->first() : User::factory()->create(),
            'predicted_result' => $this->faker->randomFloat(2, 1, 9),
            'bet_amount' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
