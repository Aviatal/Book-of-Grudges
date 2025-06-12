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
        DB::table('hero_characteristics')->whereNull('advancement')->update(['advancement' => 0]);
        Schema::table('hero_characteristics', static function (Blueprint $table) {
            $table->tinyInteger('advancement')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_characteristics', static function (Blueprint $table) {
            $table->tinyInteger('advancement')->change();
        });
    }
};
