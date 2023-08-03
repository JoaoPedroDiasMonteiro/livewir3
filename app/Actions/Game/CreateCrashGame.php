<?php

namespace App\Actions\Game;

use App\Enums\GameTypes;
use App\Models\Game;

final class CreateCrashGame
{
    public static function execute(): Game
    {
        return Game::query()->create([
            'type' => GameTypes::CRASH,
            'server_seed' => Game::createSeed(),
            'client_seed' => Game::createSeed(),
        ]);
    }
}
