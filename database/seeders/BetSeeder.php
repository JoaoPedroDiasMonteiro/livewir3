<?php

namespace Database\Seeders;

use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Seeder;

class BetSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $games = Game::all();

        $games->each(function (Game $game) use ($users) {
            $users->each(function (User $user) use ($game) {
                Bet::factory()->for($game)->for($user)->create();
            });
        });
    }
}
