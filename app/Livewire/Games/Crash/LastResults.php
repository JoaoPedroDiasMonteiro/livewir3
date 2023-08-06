<?php

namespace App\Livewire\Games\Crash;

use App\Enums\GameTypes;
use App\Models\Game;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LastResults extends Component
{
    #[Computed] // TODO: Add cache
    public function lastResults(): array
    {
        return Game::query()
            ->select('result')
            ->whereType(GameTypes::CRASH)
            ->whereFinished(true)
            ->latest('id')
            ->take(9)
            ->pluck('result')
            ->toArray();
    }

    public function render(): View
    {
        return view('livewire.games.crash.last-results');
    }
}
