<?php

namespace App\Console;

use App\Actions\Game\CreateCrashGame;
use App\Enums\GameTypes;
use App\Models\Game;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            // TODO: Move this function to a Action.
            $game = Game::whereType(GameTypes::CRASH)
                ->where('created_at', '>', now()->subMinutes(2))
                ->exists();

            if (! $game) {
                CreateCrashGame::execute();
            }
        })->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
