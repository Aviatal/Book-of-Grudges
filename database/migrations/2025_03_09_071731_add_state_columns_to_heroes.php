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
        Schema::table('heroes', function (Blueprint $table) {
            $table->unsignedSmallInteger('current_wounds')->default(0)->after('all_experience');
            $table->unsignedSmallInteger('gold_crowns')->default(0)->after('current_wounds');
            $table->unsignedSmallInteger('silver_shillings')->default(0)->after('gold_crowns');
            $table->unsignedSmallInteger('brass_pennies')->default(0)->after('silver_shillings');
            $table->unsignedTinyInteger('fortune_points')->default(0)->after('brass_pennies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->dropColumn('current_wounds');
            $table->dropColumn('gold_crowns');
            $table->dropColumn('silver_shillings');
            $table->dropColumn('brass_pennies');
            $table->dropColumn('fortune_points');
        });
    }
};
