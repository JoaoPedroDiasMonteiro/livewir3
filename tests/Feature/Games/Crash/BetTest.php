<?php

namespace Tests\Feature\Games\Crash;

use App\Actions\Game\BetOnGame;
use App\Actions\Game\ProcessCrashGameBets;
use App\Actions\Game\ProcessCrashResultGame;
use App\Enums\GameTypes;
use App\Models\Game;
use App\Models\User;
use Exception;
use Queue;
use Tests\TestCase;

class BetTest extends TestCase
{
    public function test_can_ensure_is_crash_game(): void
    {
        // TODO: Create a custom exception for all generic exceptions
        $this->expectException(Exception::class);

        $game = Game::factory()->create([
            'type' => GameTypes::DICE,
        ]);

        ProcessCrashGameBets::execute($game);
    }

    public function test_ensure_user_has_enough_founds_before_bet(): void
    {
        $this->expectException(Exception::class);

        $user = User::factory()->withBalance(0)->create();

        $game = $this->createCrashGameWithResultTwo();

        BetOnGame::execute($game, 2.00, 1000, $user);
    }

    public function test_ensure_game_is_not_finished_before_bet(): void
    {
        $this->expectException(Exception::class);

        $user = User::factory()->withBalance(1000)->create();

        $game = $this->createCrashGameWithResultTwo();

        $game->update([
            'result' => 2,
            'finished' => true,
        ]);

        BetOnGame::execute($game, 2.00, 1000, $user);
    }

    /** @dataProvider crashGameBetsResultTests */
    public function test_can_process_bets_with_correct_result(float $betAmount, float $betPredict, float $expectedUserBalance): void
    {
        Queue::fake();

        $user = User::factory()->withBalance(1000)->create();

        $game = $this->createCrashGameWithResultTwo();

        BetOnGame::execute($game, $betPredict, $betAmount, $user);

        $this->assertSame(1000 - $betAmount, $user->balance);

        ProcessCrashResultGame::execute($game);

        $user->refresh();

        $this->assertSame($expectedUserBalance, $user->balance);
    }

    public static function crashGameBetsResultTests(): array
    {
        return [
            /** winning results */
            ['bet_amount' => 1000, 'bet_predict' => 1, 'expected_user_balance' => 1000],
            ['bet_amount' => 500, 'bet_predict' => 1, 'expected_user_balance' => 1000],
            ['bet_amount' => 10, 'bet_predict' => 2, 'expected_user_balance' => 1010],
            ['bet_amount' => 9.99, 'bet_predict' => 2, 'expected_user_balance' => 1009.99],
            ['bet_amount' => 1000, 'bet_predict' => 1.5, 'expected_user_balance' => 1500],
            ['bet_amount' => 1000, 'bet_predict' => 2, 'expected_user_balance' => 2000],
            ['bet_amount' => 1000, 'bet_predict' => 1.99998, 'expected_user_balance' => 2000],
            // ['bet_amount' => 1000, 'bet_predict' => 0.01, 'expected_user_balance' => 1000], TODO: Check this

            /** lost results */
            ['bet_amount' => 1000, 'bet_predict' => 3, 'expected_user_balance' => 0],
            ['bet_amount' => 500, 'bet_predict' => 3, 'expected_user_balance' => 500],
            ['bet_amount' => 10, 'bet_predict' => 3, 'expected_user_balance' => 990],
            ['bet_amount' => 9.99, 'bet_predict' => 3, 'expected_user_balance' => 990.01],
            ['bet_amount' => 1000, 'bet_predict' => 3, 'expected_user_balance' => 0],
        ];
    }

    private function createCrashGameWithResultTwo(): Game
    {
        return Game::forceCreateQuietly([
            'type' => GameTypes::CRASH,
            'server_seed' => '064c4624-a867-40aa-af4c-20c9b963a426',
            'client_seed' => '8f831c98-7ba7-4f1c-b512-5411ffb6e681',
            'result' => null,
            'finished' => false,
        ]);
    }
}
