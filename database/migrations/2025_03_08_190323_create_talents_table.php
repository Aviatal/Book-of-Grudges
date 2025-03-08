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
        Schema::create('talents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
        });
        Schema::create('hero_talent', function (Blueprint $table) {
            $table->unsignedInteger('talent_id');
            $table->unsignedInteger('hero_id');
            $table->string('additional_talent_name')->nullable();

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('CASCADE');
            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_talent', function (Blueprint $table) {
            $table->dropForeign(['hero_id']);
            $table->dropForeign(['talent_id']);
        });
        Schema::dropIfExists('hero_talent');
        Schema::dropIfExists('talents');
    }
};
