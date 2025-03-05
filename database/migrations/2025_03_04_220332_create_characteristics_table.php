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
        Schema::create('hero_characteristics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hero_id');
            $table->string('name', 25);
            $table->string('short_name', 3);
            $table->tinyInteger('start_value');
            $table->tinyInteger('advancement')->nullable();
            $table->tinyInteger('current_value');
            $table->timestamps();

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_characteristics', function (Blueprint $table) {
            $table->dropForeign(['hero_id']);
        });
        Schema::dropIfExists('hero_characteristics');
    }
};
