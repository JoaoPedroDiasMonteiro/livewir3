<?php

namespace App\Support;

use Livewire\Component;

final class Notification
{
    private string $type = 'info';

    public function __construct(private string $content, private Component $livewire)
    {
    }

    public function fire(): void
    {
        $this->livewire->dispatch('notify', [
            'content' => $this->content,
            'type' => $this->type,
        ]);
    }

    public function success(): static
    {
        $this->type = 'success';

        return $this;
    }

    public function error(): static
    {
        $this->type = 'error';

        return $this;
    }

    public function info(): static
    {
        $this->type = 'info';

        return $this;
    }
}
