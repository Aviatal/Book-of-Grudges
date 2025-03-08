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
        Schema::create('hero_skill', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('skill_id');
            $table->unsignedInteger('hero_id');
            $table->string('additional_skill_name')->nullable();
            $table->boolean('first_level')->default(0);
            $table->boolean('second_level')->default(0);

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('CASCADE');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_skill', function (Blueprint $table) {
            $table->dropForeign(['skill_id']);
            $table->dropForeign(['hero_id']);
        });
        Schema::dropIfExists('hero_skill');
    }
};
