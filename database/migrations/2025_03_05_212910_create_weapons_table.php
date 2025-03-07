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
        Schema::create('weapon_traits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('weapons', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_ranged');
            $table->string('name');
            $table->integer('price')->comment('in_pennies')->nullable();
            $table->unsignedSmallInteger('loading')->nullable();
            $table->enum('category', [
                    'zwykła', 'dwuręczna', 'parująca', 'kawaleryjska', 'korbacz', 'szermiercza', 'unieruchamiająca',
                    'długi łuk', 'palna', 'kusza', 'mechaniczna', 'rzucana', 'proca'
                ])->default('zwykła');
            $table->string('power', 10)->nullable();
            $table->tinyInteger('short_range')->nullable();
            $table->tinyInteger('long_range')->nullable();
            $table->string('reload_time')->nullable();
            $table->string('availability')->nullable();
            $table->timestamps();
        });

        Schema::create('weapon_traits_weapons', function (Blueprint $table) {
            $table->unsignedInteger('trait_id');
            $table->unsignedInteger('weapon_id');

            $table->foreign('trait_id')->references('id')->on('weapon_traits')->onDelete('CASCADE');
            $table->foreign('weapon_id')->references('id')->on('weapons')->onDelete('CASCADE');
        });
        Schema::create('hero_weapons', function (Blueprint $table) {
            $table->unsignedInteger('hero_id');
            $table->unsignedInteger('weapon_id');

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('CASCADE');
            $table->foreign('weapon_id')->references('id')->on('weapons')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_weapons', function (Blueprint $table) {
            $table->dropForeign(['hero_id', 'weapon_id']);
        });
        Schema::table('weapons_weapon_traits', function (Blueprint $table) {
            $table->dropForeign(['trait_id', 'weapon_id']);
        });
        Schema::dropIfExists('hero_weapons');
        Schema::dropIfExists('weapons_weapon_traits');
        Schema::dropIfExists('weapons');
        Schema::dropIfExists('weapon_traits');
    }
};
