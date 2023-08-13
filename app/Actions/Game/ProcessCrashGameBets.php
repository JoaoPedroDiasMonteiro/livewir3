<?php

namespace App\Actions\Game;

use App\Enums\GameTypes;
use App\Models\Bet;
use App\Models\Game;
use App\Models\User;

final class ProcessCrashGameBets
{
    public function __construct(private Game $game)
    {
    }

    public static function execute(Game $game): void
    {
        $instance = new self($game);

        $instance->ensureIsCrashGame();

        $instance->processGameBets();
    }

    private function processGameBets(): void
    {
        $this->game->load('bets.user');

        $this->game->bets->each(function (Bet $bet) {

            // TODO: Create something to show the bets receipts

            if (! $this->checkUserWonBet($this->game->result, $bet->predicted_result)) {
                return;
            }

            $user = $bet->user;

            try {
                static::processWonBet($user, $bet);
            } catch (\Throwable $th) {
                // TODO: Notification
                logger()->critical('Something was wrong with payment', [
                    'user' => $user->toArray(),
                    'bet' => $bet->toArray(),
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace(),
                ]);
            }
        });
    }

    private function processWonBet(User $user, Bet $bet): void
    {
        $predict = floatval($bet->predicted_result);

        $amount = $bet->raw_bet_amount * $predict;

        $user->addBalance($amount);
    }

    private function checkUserWonBet(float $gameResult, float $betPredict): bool
    {
        if ($betPredict <= $gameResult) {
            return true;
        }

        return false;
    }

    private function ensureIsCrashGame(): void
    {
        if ($this->game->type !== GameTypes::CRASH) {
            throw new \Exception('Oops! Wrong game type... Game: '.$this->game->id);
        }

        if (! $this->game->finished) {
            throw new \Exception('Oops! The game isn\'t finished... Game: '.$this->game->id);
        }
    }
}
