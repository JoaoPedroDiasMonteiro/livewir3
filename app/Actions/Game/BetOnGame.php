<?php

namespace App\Actions\Game;

use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class BetOnGame
{
    public static function execute(Game $game, string $predict, float $amount, ?User $user = null): Bet
    {
        $user ??= user();

        self::ensureGameHasNotFinished($game);

        self::ensureUserBalanceHasCredit($user, $amount);

        DB::beginTransaction();

        try {
            $user->withdrawBalance($amount);

            $bet = Bet::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'predicted_result' => $predict,
                'bet_amount' => $amount,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw new \Exception($th->getMessage());
        }

        return $bet;
    }

    private static function ensureGameHasNotFinished(Game $game): void
    {
        if ($game->finished) {
            throw new \Exception("Oops! You can't bet on a finished game");
        }
    }

    private static function ensureUserBalanceHasCredit(User $user, float $value)
    {
        if ($user->balance < $value) {
            throw new \Exception("Oops! You haven't enough founds");
        }
    }
}
