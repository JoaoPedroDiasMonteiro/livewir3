<?php

namespace Tests\Feature\Livewire\Games\Crash;

use App\Livewire\Games\Crash\LastResults;
use App\Models\Game;
use Livewire\Livewire;
use Tests\TestCase;

class LastResultsTest extends TestCase
{
    public function test_renders_successfully(): void
    {
        Livewire::test(LastResults::class)
            ->assertStatus(200);
    }

    public function test_it_should_load_last_games_correctly(): void
    {
        $resultSequence = collect(['0.00', '1.20', '5.40', '7.0', '3.15']);

        Game::factory($resultSequence->count())
            ->sequence(...$resultSequence->map(fn (string $result) => [
                'result' => $result,
            ]))
            ->createQuietly();

        Livewire::test(LastResults::class)
            ->assertSeeInOrder(
                $resultSequence->reverse()
                    ->map(fn (string $result) => "game-result-{$result}")
                    ->toArray()
            );
    }
}
