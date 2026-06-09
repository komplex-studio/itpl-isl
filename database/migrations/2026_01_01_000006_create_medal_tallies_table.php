<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medal_tallies', function (Blueprint $table) {
            $table->id();
            $table->string('state');
            $table->unsignedInteger('gold')->default(0);
            $table->unsignedInteger('silver')->default(0);
            $table->unsignedInteger('bronze')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medal_tallies');
    }
};
