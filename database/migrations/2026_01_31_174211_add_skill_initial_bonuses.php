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
        Schema::create('talent_initial_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('talent_id')->constrained('talents')->cascadeOnDelete();
            $table->foreignId('characteristic_id')->constrained('characteristics')->cascadeOnDelete();
            $table->tinyInteger('bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_initial_bonuses');
    }
};
