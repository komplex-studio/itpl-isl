<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "fixtures" = individual matches/bouts (avoids the reserved word MATCH on MySQL)
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('round');             // e.g. "Round of 16", "Quarter-final", "Final"
            $table->unsignedTinyInteger('round_order')->default(0); // 1=earliest .. higher=later
            $table->unsignedTinyInteger('slot')->default(0);        // position within the round
            $table->foreignId('athlete_a_id')->nullable()->constrained('athletes')->nullOnDelete();
            $table->foreignId('athlete_b_id')->nullable()->constrained('athletes')->nullOnDelete();
            $table->dateTime('scheduled_at')->nullable();
            $table->string('venue')->nullable();
            $table->string('status')->default('scheduled'); // scheduled | live | completed
            $table->foreignId('winner_id')->nullable()->constrained('athletes')->nullOnDelete();
            $table->string('score_a')->nullable();
            $table->string('score_b')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
