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
        Schema::table('hero_weapons', function (Blueprint $table) {
            $table->string('additional_weapon_name')->after('weapon_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_weapons', function (Blueprint $table) {
            $table->dropColumn('additional_weapon_name');
        });
    }
};
