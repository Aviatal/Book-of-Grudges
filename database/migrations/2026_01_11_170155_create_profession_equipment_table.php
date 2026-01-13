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
        Schema::create('profession_equipment', function (Blueprint $table) {
            $table->string('item_name');
            $table->foreignId('profession_id')->constrained('professions', 'id');
            $table->foreignId('option_1')->constrained('marketplace_items', 'id');
            $table->foreignId('option_2')->nullable()->constrained('marketplace_items', 'id');
            $table->foreignId('option_3')->nullable()->constrained('marketplace_items', 'id');
        });
    }
/**/
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profession_equipment', function (Blueprint $table) {
            $table->dropForeign(['profession_id']);
            $table->dropForeign(['option_1']);
            $table->dropForeign(['option_2']);
            $table->dropForeign(['option_3']);
        });
        Schema::dropIfExists('profession_equipment');
    }
};
