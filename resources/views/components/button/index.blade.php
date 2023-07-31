@props([
    'label' => null,
    'color' => 'primary'
])

<button type="button"
        {{ $attributes->except('class') }}
        @class([
            'block rounded-md bg-gradient-to-r hover:bg-gradient-to-b px-3 py-2 text-center text-sm font-semibold text-white shadow-sm transition-all hover:scale-110',
            'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline active:scale-95',
            'from-indigo-500 via-purple-500 to-pink-500 hover:bg-indigo-500 focus-visible:outline-indigo-600' => $color === 'primary',
        ])
>
    {{ $slot }}
    {{ $label }}
</button>
