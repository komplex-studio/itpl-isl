<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('season')->default('2026');
            $table->string('city');
            $table->string('state');
            $table->string('venue');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('upcoming'); // upcoming | ongoing | completed
            $table->boolean('registration_open')->default(true);
            $table->unsignedInteger('prize_pool')->default(0);
            $table->string('gradient')->default('from-ink-800 to-ink-950'); // hero tint
            $table->text('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
