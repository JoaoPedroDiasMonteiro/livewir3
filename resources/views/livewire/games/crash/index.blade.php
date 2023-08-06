<div wire:poll.300ms class="flex flex-col gap-5">

    <header class="flex flex-col gap-2">
        <h1 class="text-2xl font-bold">Crash Game</h1>
        <p>Game: <span class="text-gray-500">#{{ $this->game->id }}</span></p>
        <p>Server seed: <span class="text-gray-500">{{ $this->game->encrypted_server_seed }}</span></p>
        <p>Client seed: <span class="text-gray-500">{{ $this->game->client_seed }}</span></p>
    </header>

    <section class="flex rounded-lg shadow">
        {{-- TODO: transform in component --}}
        <div class="w-1/3">
            Bet input
        </div>

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

    {{-- TODO: transform in component --}}
    <section class="flex items-center text-center gap-4 overflow-hidden">
        @foreach ($this->lastGamesResults as $result)
            <div @class([
                'bg-red-500' => $result < 2,
                'bg-green-500' => $result >= 2,
                'inline-block text-white px-2 rounded-lg w-full whitespace-nowrap shadow'
            ])>
               {{ number_format($result, 2) }} X
            </div>
        @endforeach
    </section>


    <section class="h-20 rounded-lg bg-slate-50 shadow">
        Bets component
    </section>
</div>
