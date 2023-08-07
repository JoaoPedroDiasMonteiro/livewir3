<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Bet
 *
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 * @property string $predicted_result
 * @property float $bet_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\BetFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Bet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bet onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bet whereBetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet wherePredictedResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bet withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bet withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Bet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function predictedResult(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => number_format($value, 2)
        );
    }

    public function betAmount(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => number_format($value, 2)
        );
    }
}
