<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        $search = "%{$search}%";

        return $query->where('title', 'like', $search)
            ->orWhere('content', 'like', $search);
    }
}
