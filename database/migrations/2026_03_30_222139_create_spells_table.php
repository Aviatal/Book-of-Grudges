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
        Schema::create('spells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_id');
            $table->enum('type', ['PROSTA', 'TAJEMNA', 'POWSZECHNA', 'KAPŁAŃSKA', 'CZARNOKSIĘSKA']);
            $table->string('specialization')->nullable();
            $table->string('name')->unique();
            $table->string('ingredient');
            $table->text('casting_duration');
            $table->text('effect_duration');
            $table->text('description');
            $table->tinyInteger('casting_number')->comment('Wymagany poziom mocy');
            $table->timestamps();

            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spells');
    }
};
