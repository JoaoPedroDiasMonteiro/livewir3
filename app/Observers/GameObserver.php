<?php

namespace App\Observers;

use App\Enums\GameTypes;
use App\Jobs\ProcessCrashResultGameJob;
use App\Models\Game;

class GameObserver
{
    public function created(Game $game): void
    {
        if ($game->type === GameTypes::CRASH) {
            ProcessCrashResultGameJob::dispatch($game)->delay(now()->addSeconds(Game::CRASH_GAME_INTERVAL));
        }
    }
}
