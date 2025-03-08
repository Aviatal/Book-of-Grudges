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
        Schema::create('armors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('category', ['SKÓRZANA', 'KOLCZA', 'PŁYTOWA']);
            $table->unsignedInteger('price');
            $table->unsignedInteger('loading');
            $table->unsignedTinyInteger('armor_points');
            $table->string('availability');
            $table->timestamps();
        });
        Schema::create('armor_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        Schema::create('armor_armor_location', function (Blueprint $table) {
            $table->unsignedInteger('armor_id');
            $table->unsignedInteger('armor_location_id');

            $table->foreign('armor_id')->references('id')->on('armors')->onDelete('CASCADE');
            $table->foreign('armor_location_id')->references('id')->on('armor_locations')->onDelete('CASCADE');
        });
        Schema::create('hero_armors', function (Blueprint $table) {
            $table->unsignedInteger('hero_id');
            $table->unsignedInteger('armor_id');

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('CASCADE');
            $table->foreign('armor_id')->references('id')->on('armors')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('armor_armor_location', function (Blueprint $table) {
            $table->dropForeign(['armor_id']);
            $table->dropForeign(['armor_location_id']);
        });
        Schema::table('hero_armors', function (Blueprint $table) {
            $table->dropForeign(['hero_id']);
            $table->dropForeign(['armor_id']);
        });
        Schema::dropIfExists('armor');
        Schema::dropIfExists('armor_locations');
        Schema::dropIfExists('armor_armor_location');
        Schema::dropIfExists('hero_armors');
    }
};
