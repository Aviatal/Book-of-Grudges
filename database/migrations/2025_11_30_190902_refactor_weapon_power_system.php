<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->boolean('add_hero_power')->default(false);
        });
        DB::table('weapons')->where('power', '=', 'S')->orWhereNull('power')->update(['power' => 0]);
        DB::table('weapons')->whereLike('power', 'S+_')->update(['power' => DB::raw("REPLACE(power, 'S+', '')"), 'add_hero_power' => true]);;
        DB::table('weapons')->whereLike('power', 'S-_')->update(['power' => DB::raw("REPLACE(power, 'S', '')"), 'add_hero_power' => true]);
        DB::statement('ALTER TABLE weapons ALTER COLUMN power TYPE smallint USING power::smallint');
    }
};
