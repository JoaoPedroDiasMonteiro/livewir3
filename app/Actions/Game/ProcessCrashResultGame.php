<?php

namespace App\Actions\Game;

use App\Enums\GameTypes;
use App\Jobs\CreateCrashGameJob;
use App\Models\Game;

final class ProcessCrashResultGame
{
    public function __construct(private Game $game)
    {
    }

    public static function execute(Game $game): Game
    {
        $instance = new self($game);

        $instance->ensureIsCrashGame();

        $instance->processGameResult();

        CreateCrashGameJob::dispatch()->delay(now()->addSeconds(Game::CRASH_GAME_INTERVAL));

        // TODO: Process bets for this game.

        return $instance->game;
    }

    private function processGameResult(): void
    {
        $hash = hash_hmac('sha256', $this->game->server_seed, $this->game->client_seed);

        $point = $this->getPoint(hash: $hash);

        $this->game->update([
            'result' => $point,
            'finished' => true,
        ]);
    }

    private function getPoint(string $hash): float
    {
        // In 1 of 15 games the game crashes instantly.
        if ($this->shouldInstaCrash($hash, 15)) {
            return 0;
        }

        // Use the most significant 52-bit from the hash to calculate the crash point
        $h = intval(substr($hash, 0, 52 / 4), 16);
        $e = pow(2, 52);

        $point = floor((100 * $e - $h) / ($e - $h)) / 100;

        return number_format($point, 2);
    }

    private function shouldInstaCrash(string $hash, int $mod): bool
    {
        $val = 0;

        $o = strlen($hash) % 4;

        for ($i = $o > 0 ? $o - 4 : 0; $i < strlen($hash); $i += 4) {
            $val = (($val << 16) + hexdec(substr($hash, $i, 4))) % $mod;
        }

        return $val === 0;
    }

    private function ensureIsCrashGame(): void
    {
        if ($this->game->type !== GameTypes::CRASH) {
            throw new \Exception('Oops! Wrong game type... Game: '.$this->game->id);
        }

        if ($this->game->finished) {
            throw new \Exception('Oops! The game is already finished... Game: '.$this->game->id);
        }
    }
}
