<?php

namespace App\Models;

use App\Enums\GameTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property GameTypes $type
 * @property string $server_seed
 * @property string $client_seed
 * @property string|null $result
 * @property int $finished
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bet> $bets
 * @property-read int|null $bets_count
 *
 * @method static \Database\Factories\GameFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereClientSeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereServerSeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Game withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const CRASH_GAME_INTERVAL = 5;

    protected $appends = [
        'encryptedServerSeed',
    ];

    protected $casts = [
        'type' => GameTypes::class,
        'ends_at' => 'datetime',
    ];

    protected $guarded = [];

    protected $hidden = [
        'server_seed',
        'result',
    ];

    public static function createSeed(): string
    {
        return Str::uuid();
    }

    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    /** For some reason we're not able to use use Illuminate\Database\Eloquent\Casts\Attribute; using appends  */
    protected function getEncryptedServerSeedAttribute(): string
    {
        return $this->encryptServerSeed();
    }

    private function encryptServerSeed(): string
    {
        return hash('sha256', $this->server_seed);
    }
}
