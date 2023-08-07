@props([
    'label' => null,
    'color' => 'primary',
    'disabled' => false,
])

<button type="{{ $attributes->get('type') ?? 'button' }}"
        @if($disabled) disabled @endif
        {{ $attributes->except(['class', 'disabled']) }}
        @class([
            'block rounded-md bg-gradient-to-r hover:bg-gradient-to-b px-3 py-2 text-center text-sm font-semibold text-white shadow-sm transition-all hover:scale-95',
            'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline active:scale-100',
            'disabled:opacity-80 disabled:cursor-not-allowed disabled:scale-100',
            'from-indigo-500 via-purple-500 to-pink-500 hover:bg-indigo-500 focus-visible:outline-indigo-600' => $color === 'primary',
        ])
>
    {{ $slot }}
    {{ $label }}
</button>
