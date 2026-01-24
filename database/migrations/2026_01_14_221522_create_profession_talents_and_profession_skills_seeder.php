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
        Schema::create('profession_skills', function (Blueprint $table) {
            $table->foreignId('profession_id')->constrained('professions', 'id');
            $table->tinyInteger('group_id');
            $table->foreignId('skill_id')->constrained('skills', 'id');
            $table->string('additional_name')->nullable();
        });
        Schema::create('profession_talents', function (Blueprint $table) {
            $table->foreignId('profession_id')->constrained('professions', 'id');
            $table->tinyInteger('group_id');
            $table->foreignId('talent_id')->constrained('talents', 'id');
            $table->string('additional_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profession_skills');
        Schema::dropIfExists('profession_talents');
    }
};
