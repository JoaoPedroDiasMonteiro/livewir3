<?php

namespace Database\Factories;

use App\Enums\GameTypes;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => GameTypes::CRASH,
            'server_seed' => Game::createSeed(),
            'client_seed' => Game::createSeed(),
            'result' => $this->faker->randomFloat(2, 1, 9),
            'finished' => true,
        ];
    }
}
