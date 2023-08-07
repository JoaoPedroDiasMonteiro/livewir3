<div wire:poll.300ms class="flex flex-col gap-5">

    <header class="flex flex-col gap-2">
        <h1 class="text-2xl font-bold">Crash Game</h1>
        <p>Game: <span class="text-gray-500">#{{ $this->game->id }}</span></p>
        <p>Server seed: <span class="text-gray-500">{{ $this->game->encrypted_server_seed }}</span></p>
        <p>Client seed: <span class="text-gray-500">{{ $this->game->client_seed }}</span></p>
    </header>

    <section class="flex rounded-lg shadow overflow-hidden">
        {{-- TODO: transform in component --}}
        <form wire:submit='bet' class="flex w-1/3 flex-col gap-4 p-4">
            <label class="flex flex-col">
                Amount $
                <input wire:model='amount' class="rounded-lg" type="text">
                @error('amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

            </label>

            <label class="flex flex-col">
                Bet
                <input wire:model='predict' class="rounded-lg" type="text">
                @error('predict') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </label>

            <x-button :disabled="$this->game->finished" type="submit" label="Save" />
        </form>

        {{-- TODO: transform in component --}}
        <div class="flex h-80 flex-1 items-center justify-center bg-blue-300">
            @if ($this->game->finished)
                <div class="flex flex-col">
                    <p>Result: {{ $this->game->result }}</p>
                    <p>Loading a new game...</p>
                </div>
            @else
                <div>Make your bets...</div>
            @endif
        </div>
    </section>

    <livewire:games.crash.last-results wire:key="last-results-{{ $this->game->id }}" />

    {{-- TODO: Bets component --}}
    <section class="rounded-lg shadow space-y-2 overflow-hidden p-4">
        @foreach ($this->game->bets as $bet)
            <div @class([
                'flex items-center rounded-lg p-2',
                'bg-red-200' => $this->game->finished && $bet->predicted_result > $this->game->result,
                'bg-green-200' => $this->game->finished && $bet->predicted_result <= $this->game->result,
            ]) wire:key='game-{{ $this->game->id }}-bet-{{ $bet->id }}'>
                <div>
                    {{ $bet->user->name }}
                </div>

                <div class="ml-auto">
                    <span class="bg-gray-200 p-2 rounded-lg">{{ $bet->predicted_result }} X</span>
                    <span class="bg-gray-200 p-2 rounded-lg ml-4">$ {{ $bet->bet_amount }}</span>
                </div>
            </div>
        @endforeach
    </section>
</div>
