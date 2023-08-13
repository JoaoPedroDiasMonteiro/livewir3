<?php

namespace App\Console;

use App\Actions\Game\CreateCrashGame;
use App\Enums\GameTypes;
use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

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

        // TODO: Move to action
        if (! App::isProduction()) {
            $schedule->call(function () {
                $latestCrashGame = Game::query()
                    ->whereType(GameTypes::CRASH)
                    ->latest('id')
                    ->whereFinished(false)
                    ->first();

                if (! $latestCrashGame) {
                    return;
                }

                try {
                    $users = User::query()
                        ->inRandomOrder()
                        ->where('id', '!=', 1)
                        ->take(rand(1, 3))
                        ->get();

                    $users->each(function (User $user) use ($latestCrashGame) {
                        usleep(rand(30000, 300000));

                        $predict = fake()->randomFloat(2, 1.1, 3);
                        $amount = fake()->randomFloat(2, 1, 20);

                        $user->withdrawBalance($amount);

                        Bet::create([
                            'user_id' => $user->id,
                            'game_id' => $latestCrashGame->id,
                            'predicted_result' => $predict,
                            'bet_amount' => $amount,
                        ]);
                    });

                } catch (\Throwable $th) {
                    echo $th->getMessage();
                }

            })->everySecond();
        }

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
