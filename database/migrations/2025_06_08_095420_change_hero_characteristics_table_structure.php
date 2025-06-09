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
        Schema::table('hero_characteristics', static function (Blueprint $table) {
            $table->dropColumn('current_value');
        });

        Schema::create('profession_characteristics', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profession_id');
            $table->unsignedInteger('characteristic_id');
            $table->tinyInteger('available_advancement');

            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('CASCADE');
            $table->foreign('characteristic_id')->references('id')->on('characteristics')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_characteristics', static function (Blueprint $table) {
            $table->tinyInteger('current_value')->after('advancement');
        });

        Schema::table('profession_characteristics', static function (Blueprint $table) {
            $table->dropForeign(['profession_id']);
            $table->dropForeign(['characteristic_id']);
        });
        Schema::dropIfExists('profession_characteristics');
    }
};
