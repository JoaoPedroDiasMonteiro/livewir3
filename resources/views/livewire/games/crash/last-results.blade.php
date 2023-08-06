<section class="flex items-center gap-4 overflow-hidden text-center">
    @foreach ($this->lastResults as $result)
        <button data-cy="game-result-{{ $result }}" @class([
            'bg-red-500' => $result < 2,
            'bg-green-500' => $result >= 2,
            'inline-block text-white px-2 rounded-lg w-full whitespace-nowrap shadow',
        ])>
            {{ number_format($result, 2) }} X
        </button>
    @endforeach
</section>
