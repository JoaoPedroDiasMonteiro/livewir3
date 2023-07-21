<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Index extends Component
{
    #[Rule(rule: 'string|max:255')]
    public string $search = '';

    #[Rule(rule: 'string|in:title')]
    public ?string $sort = null;

    #[Rule(rule: 'in:title')]
    public ?string $direction = 'asc';

    public function updatedSort(?string $value, ?string $oldValue): void
    {
        if ($value === $oldValue) {
            $this->direction = 'desc';
        } else {
            $this->direction = 'asc';
        }
    }

    #[Computed]
    public function posts(): Collection // Paginate
    {
        return Post::query()
            ->search($this->search)
            ->when($this->sort,
                fn (Builder $query) => $query->orderBy($this->sort, $this->direction))
            ->get();
    }

    public function render(): View
    {
        return view('livewire.post.index');
    }
}
