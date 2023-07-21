<?php

namespace App\Livewire\Post;

use Illuminate\Support\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Count extends Component
{
    #[Reactive]
    public Collection $posts;

    public function render()
    {
        return view('livewire.post.count');
    }
}
