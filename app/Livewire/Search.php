<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    /**
     * We get the error bellow if remove it
     *
     * Property [$paginators] not found on component: [search]
     */
    use WithPagination;

    #[Modelable]
    public ?string $input;

    public function render(): View
    {
        return view('livewire.search');
    }
}
