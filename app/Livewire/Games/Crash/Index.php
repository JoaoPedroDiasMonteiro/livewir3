<?php

namespace App\Livewire\Games\Crash;

use App\Enums\GameTypes;
use App\Models\Bet;
use App\Models\Game;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Index extends Component
{
    #[Rule('required|numeric|min:1|max:100000')]
    public $amount = '0.00';

    #[Rule('required|numeric|min:0.01|max:100')]
    public $predict = '0.00';

    public function bet(): void
    {
        if ($this->game->finished) {
            return;
        }

        $this->validate();

        Bet::create([
            'user_id' => auth()->user()->id,
            'game_id' => $this->game->id,
            'predicted_result' => $this->predict,
            'bet_amount' => $this->amount,
        ]);

        $this->reset('predict', 'amount');

        /** TODO: Notification */
    }

    #[Computed] // TODO: Add cache
    public function game(): Game
    {
        return Game::query()
            ->with('bets')
            ->whereType(GameTypes::CRASH)
            ->latest('id')
            ->first();
    }

    #[Computed] // TODO: Add cache
    public function lastGamesResults(): array
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
        return view('livewire.games.crash.index');
    }
}
