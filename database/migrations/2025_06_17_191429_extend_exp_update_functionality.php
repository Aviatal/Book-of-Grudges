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
        Schema::rename('exp_update', 'hero_update');
        Schema::table('hero_update', static function (Blueprint $table) {
            $table->renameColumn('exp_amount', 'added_amount');
            $table->string('type')->after('hero_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('hero_update', 'exp_update');
        Schema::table('exp_update', static function (Blueprint $table) {
            $table->renameColumn('added_amount', 'exp_amount');
            $table->dropColumn('type');
        });
    }
};
