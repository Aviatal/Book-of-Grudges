<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('profession_equipment');

        Schema::create('profession_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profession_id')->constrained('professions')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('marketplace_items')->onDelete('cascade');
            $table->string('item_name')->nullable();
            $table->unsignedInteger('group_id')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profession_equipment');
    }
};
