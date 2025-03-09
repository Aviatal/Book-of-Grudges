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
        Schema::create('hero_inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hero_id');
            $table->string('name');
            $table->smallInteger('loading')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('hero_id')->references('id')->on('heroes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_inventory', function (Blueprint $table) {
            $table->dropForeign(['hero_id']);
        });
        Schema::dropIfExists('hero_inventory');
    }
};
