<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

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
    public function posts(): LengthAwarePaginator
    {
        return Post::query()
            ->search($this->search)
            ->when($this->sort,
                fn (Builder $query) => $query->orderBy($this->sort, $this->direction))
            ->paginate(5);
    }

    public function render(): View
    {
        return view('livewire.post.index');
    }
}
