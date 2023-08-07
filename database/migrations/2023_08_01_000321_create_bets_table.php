<?php

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Game::class)->index()->constrained();
            $table->foreignIdFor(User::class)->index()->constrained();
            $table->string('predicted_result')->index();
            $table->float('bet_amount', 12, 4)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
