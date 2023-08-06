<?php

namespace App\Jobs;

use App\Actions\Game\ProcessCrashResultGame;
use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCrashResultGameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Game $game)
    {
    }

    public function handle(): void
    {
        ProcessCrashResultGame::execute($this->game);
    }
}
