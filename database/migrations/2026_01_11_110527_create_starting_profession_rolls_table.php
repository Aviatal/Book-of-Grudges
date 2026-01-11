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
        Schema::create('starting_profession_rolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profession_id')->constrained('professions', 'id');
            $table->enum('race', ['human', 'elf', 'dwarf', 'halfling']);
            $table->tinyInteger('min_roll');
            $table->tinyInteger('max_roll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('starting_profession_rolls', function (Blueprint $table) {
            $table->dropForeign(['profession_id']);
        });
        Schema::dropIfExists('starting_profession_rolls');
    }
};
