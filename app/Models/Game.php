<?php

namespace App\Models;

use App\Enums\GameTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = [
        'encryptedServerSeed',
    ];

    protected $casts = [
        'type' => GameTypes::class,
        'ends_at' => 'datetime',
    ];

    protected $hidden = [
        'server_seed',
        'result',
    ];

    public static function createSeed(): string
    {
        return Str::uuid();
    }

    /** For some reason we're not able to use use Illuminate\Database\Eloquent\Casts\Attribute; using appends  */
    protected function getEncryptedServerSeedAttribute(): string
    {
        return $this->encryptServerSeed();
    }

    protected function getHasFinishedAttribute(): string
    {
        return ! is_null($this->result);
    }

    private function encryptServerSeed(): string
    {
        return hash('sha256', $this->server_seed);
    }
}
