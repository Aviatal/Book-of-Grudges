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
        Schema::dropIfExists('hero_update');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('hero_update', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('hero_id');
            $table->string('type')->after('hero_id');
            $table->boolean('read')->default(false);
            $table->smallInteger('added_amount');
            $table->text('additional_note')->nullable();
            $table->timestamps();

            $table->foreign('hero_id')->references('id')->on('heroes')->onDelete('cascade');
        });
    }
};
