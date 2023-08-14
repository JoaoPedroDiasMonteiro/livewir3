<?php

namespace Tests\Feature\Games\Crash;

use App\Actions\Game\CreateCrashGame;
use App\Enums\GameTypes;
use App\Jobs\CreateCrashGameJob;
use App\Jobs\ProcessCrashResultGameJob;
use App\Models\Game;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_can_create_crash_game(): void
    {
        Queue::fake();

        $this->assertDatabaseEmpty(Game::class);

        CreateCrashGame::execute();

        $this->assertDatabaseHas(Game::class, [
            'type' => GameTypes::CRASH,
        ]);
    }

    public function test_can_create_crash_game_using_job(): void
    {
        Queue::fake([
            ProcessCrashResultGameJob::class,
        ]);

        $this->assertDatabaseEmpty(Game::class);

        CreateCrashGameJob::dispatch();

        $this->assertDatabaseHas(Game::class, [
            'type' => GameTypes::CRASH,
        ]);
    }

    public function test_can_dispatch_process_game_result_creating_a_new_game(): void
    {
        Queue::fake();

        $game = CreateCrashGame::execute();

        Queue::assertPushed(function (ProcessCrashResultGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });
    }

    public function test_can_create_game_in_schedule_if_something_went_wrong(): void
    {
        $this->markTestIncomplete('TODO:');
    }
}
