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
        Schema::create('hero_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hero_id');
            $table->smallInteger('age');
            $table->enum('gender', ['M', 'K']);
            $table->string('eye_color')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('star_sign')->nullable();
            $table->smallInteger('weight')->nullable();
            $table->smallInteger('height')->nullable();
            $table->string('siblings')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('distinguishing_signs')->nullable();
            $table->timestamps();

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_descriptions', function (Blueprint $table) {
            $table->dropForeign(['hero_id']);
        });
        Schema::dropIfExists('hero_descriptions');
    }
};
