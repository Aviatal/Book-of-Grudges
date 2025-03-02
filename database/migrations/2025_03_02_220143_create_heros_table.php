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
        Schema::create('professions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('heros', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->enum('race', ['Krasnolud', 'Elf', 'Niziołek', 'Człowiek']);
            $table->unsignedInteger('current_profession_id');
            $table->unsignedInteger('previous_profession_id');
            $table->timestamps();

            $table->foreign('current_profession_id')->references('id')->on('professions');
            $table->foreign('previous_profession_id')->references('id')->on('professions');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heros', function (Blueprint $table) {
            $table->dropForeign(['current_profession_id', 'previous_profession_id', 'user_id']);
        });
        Schema::dropIfExists('heros');
        Schema::dropIfExists('professions');
    }
};
