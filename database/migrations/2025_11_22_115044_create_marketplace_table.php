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
        Schema::create('marketplace_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tradeable_id');
            $table->string('tradeable_type');
        });
        Schema::create('common_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->smallInteger('loading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_items');
        Schema::dropIfExists('common_items');
    }
};
