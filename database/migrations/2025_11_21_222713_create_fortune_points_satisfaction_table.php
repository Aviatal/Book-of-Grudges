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
        Schema::create('fortune_points_satisfaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('hero_id');
            $table->boolean('satisfied');
            $table->timestamps();

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fortune_points_satisfaction', function (Blueprint $table) {
            $table->dropForeign(['hero_id']);
        });
        Schema::dropIfExists('fortune_points_satisfaction');
    }
};
