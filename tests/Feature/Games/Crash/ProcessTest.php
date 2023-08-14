<?php

namespace Tests\Feature\Games\Crash;

use App\Actions\Game\CreateCrashGame;
use App\Actions\Game\ProcessCrashResultGame;
use App\Enums\GameTypes;
use App\Jobs\CreateCrashGameJob;
use App\Models\Game;
use Exception;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessTest extends TestCase
{
    public function test_can_process_crash_game(): void
    {
        Queue::fake([
            CreateCrashGameJob::class,
        ]);

        $game = CreateCrashGame::execute();

        $game->refresh();

        $this->assertNotNull($game->result);

        Queue::assertPushed(CreateCrashGameJob::class);
    }

    public function test_can_ensure_game_type_is_crash(): void
    {
        $this->expectException(Exception::class);

        $game = Game::factory()->create([
            'type' => GameTypes::DICE,
        ]);

        ProcessCrashResultGame::execute($game);
    }

    public function test_can_ensure_game_has_not_finished(): void
    {
        $this->expectException(\Exception::class);

        $game = Game::factory()->create([
            'finished' => true,
        ]);

        ProcessCrashResultGame::execute($game);
    }

    /** @dataProvider processCrashGameTests */
    public function test_ensure_process_crash_game_return_correct_value(string $serverSeed, string $clientSeed, float $result): void
    {
        Queue::fake();

        $game = Game::create([
            'type' => GameTypes::CRASH,
            'server_seed' => $serverSeed,
            'client_seed' => $clientSeed,
        ]);

        $game = ProcessCrashResultGame::execute($game);

        $this->assertSame($game->result, $result);
    }

    public static function processCrashGameTests(): array
    {
        return [
            ['server_seed' => '48148487-b6f4-47f4-b4e6-173e9361a26e', 'client_seed' => 'c9350007-3d0e-4d22-b6f0-d088fee77c9b', 'result' => 15576.69],
            ['server_seed' => 'fdfaab0a-8c16-4bf5-a5d5-434ca7efdbe9', 'client_seed' => '6a5ec628-d50c-4ebe-b04a-8ab0de1ee63f', 'result' => 33.72],
            ['server_seed' => '8dd105d6-67ea-4870-9171-591c89a860bf', 'client_seed' => 'dc9e4ce3-d83c-44be-a3c0-f4d99bcfb1e8', 'result' => 0],
            ['server_seed' => '88b3ba46-45ef-4de8-a9df-8af084f1ed33', 'client_seed' => '5b30e724-6f46-451a-b8c1-fe9bcc68271b', 'result' => 2.46],
            ['server_seed' => '88b3ba46-45ef-4de8-a9df-8af084f1ed33', 'client_seed' => '5b30e724-6f46-451a-b8c1-fe9bcc68271b', 'result' => 2.46],
            ['server_seed' => 'c5fa7e64-773d-4f26-b009-b49a67502643', 'client_seed' => '650c5cd4-e280-432a-b213-14c94597d17a', 'result' => 98.78],
            ['server_seed' => '4b13716d-2366-4eec-84b3-b8e258c1fb2d', 'client_seed' => 'd693f45f-1081-4ce0-94da-75b438882ee8', 'result' => 0],
            ['server_seed' => '4e275143-c4dc-4c10-af5d-88250382e70d', 'client_seed' => 'abe8f047-0d3f-435f-a770-74e6469c23fe', 'result' => 0],
            ['server_seed' => 'aeaa4bef-f452-4dc7-b715-78dac30e64a8', 'client_seed' => 'd0c294fd-6354-4bd0-a351-9e49045a69dd', 'result' => 29.54],
            ['server_seed' => 'a33fa7fc-5d82-4881-8084-66a01191b15f', 'client_seed' => '68245564-4436-44b5-a323-37557eb5649c', 'result' => 550.38],
            ['server_seed' => '064c4624-a867-40aa-af4c-20c9b963a426', 'client_seed' => '8f831c98-7ba7-4f1c-b512-5411ffb6e681', 'result' => 2.00],

            ['server_seed' => '0000000000000000000415ebb64b0d51ccee0bb55826e43846e5bea777d91966', 'client_seed' => '16fcfdf842d6bfef2ad44ad2a24827fe4dff265c8dbf0e2ee720363a1a0480ae', 'result' => 652.62],
        ];
    }
}
