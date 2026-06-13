<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            $table->float('x')->default(100)->change();
            $table->float('y')->default(100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            $table->integer('x')->default(100)->change();
            $table->integer('y')->default(100)->change();
        });
    }
};
