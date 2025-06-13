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
        Schema::table('characteristics', static function (Blueprint $table) {
            $table->enum('type', ['MAIN', 'SECONDARY'])->default('MAIN');
        });
        \App\Models\Characteristic::whereIn('short_name', ['A', 'Zyw', 'S', 'Wt', 'Sz', 'Mag', 'PO', 'PP'])->update(['type' => 'SECONDARY']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characteristics', static function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
