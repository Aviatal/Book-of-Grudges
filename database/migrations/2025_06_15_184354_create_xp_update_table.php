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
        Schema::create('exp_update', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('hero_id');
            $table->boolean('read')->default(false);
            $table->smallInteger('exp_amount');
            $table->text('additional_note')->nullable();
            $table->timestamps();

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exp_update', static function (Blueprint $table) {
            $table->dropForeign(['hero_id']);
        });
        Schema::dropIfExists('exp_update');
    }
};
