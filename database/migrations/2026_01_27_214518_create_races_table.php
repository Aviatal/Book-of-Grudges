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
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->string('icon');
            $table->string('description');
            $table->tinyInteger('random_talents')->default(0);
        });
        Schema::create('race_bonuses', function (Blueprint $table) {
            $table->foreignId('race_id')->constrained('races', 'id');
            $table->string('bonus_name');
        });
        Schema::create('race_base_stats', function (Blueprint $table) {
            $table->foreignId('race_id')->constrained('races', 'id');
            $table->foreignId('characteristic_id')->constrained('characteristics', 'id');
            $table->integer('value');
        });
        Schema::create('race_skills', function (Blueprint $table) {
            $table->foreignId('race_id')->constrained('races', 'id');
            $table->foreignId('skill_id')->constrained('skills', 'id');
            $table->tinyInteger('group_id');
            $table->string('additional_name');
        });
        Schema::create('race_talents', function (Blueprint $table) {
            $table->foreignId('race_id')->constrained('races', 'id');
            $table->foreignId('talent_id')->constrained('talents', 'id');
            $table->tinyInteger('group_id');
            $table->string('additional_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_talents');
        Schema::dropIfExists('race_skills');
        Schema::dropIfExists('race_base_stats');
        Schema::dropIfExists('race_bonuses');
        Schema::dropIfExists('races');
    }
};
