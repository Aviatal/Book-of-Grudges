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
        Schema::create('skill_test_logs', function (Blueprint $table) {
            $table->id();

            // heroes/skills korzystają jeszcze z increments() (unsignedInteger), więc FK musi
            // mieć ten sam typ — foreignId() (unsignedBigInteger) nie dopasowałby się w Postgresie.
            $table->unsignedInteger('hero_id');
            $table->unsignedInteger('skill_id')->nullable();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();

            // Migawka nazwy umiejętności w chwili rzutu — czytelne statystyki nawet
            // gdyby umiejętność została kiedyś zmieniona/usunięta z kompendium.
            $table->string('skill_name')->nullable();
            $table->string('characteristic', 10);

            $table->unsignedSmallInteger('characteristic_value');
            $table->unsignedSmallInteger('effective_value');
            $table->smallInteger('modifier')->default(0);
            $table->boolean('half')->default(false);
            $table->boolean('has_modifier')->default(false);
            $table->unsignedTinyInteger('roll');
            $table->boolean('passed');

            $table->timestamps();

            $table->foreign('hero_id')->references('id')->on('heroes')->cascadeOnDelete();
            $table->foreign('skill_id')->references('id')->on('skills')->nullOnDelete();

            $table->index(['hero_id', 'characteristic']);
            $table->index(['hero_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_test_logs');
    }
};
