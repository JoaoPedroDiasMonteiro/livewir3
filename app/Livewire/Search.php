<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Search extends Component
{
    #[Modelable, Rule(rule: 'string|max:255')]
    public ?string $input;

    public function render()
    {
        return view('livewire.search');
    }
}
