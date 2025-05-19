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
        Schema::create('footer_texts', static function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->mediumText('text');
            $table->timestamps();
        });
        DB::table('footer_texts')->insert([
            [
                'text' => 'Wszystko poszłoby zgodnie z planem, gdyby nie te kilka kropel deszczu...',
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_texts');
    }
};
