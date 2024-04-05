<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('character_contest', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('enemy_id')->onDelete('cascade');
            $table->foreignId('character_id')->onDelete('cascade');
            $table->foreignId('contest_id')->onDelete('cascade');

            $table->integer('hero_hp');
            $table->integer('enemy_hp');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_contest');
    }
};
