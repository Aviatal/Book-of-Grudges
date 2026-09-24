<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Wprowadzamy wymóg potwierdzenia adresu e-mail (middleware "verified").
     * Konta założone wcześniej uznajemy za potwierdzone, żeby obecni gracze
     * nie zostali zablokowani na ekranie weryfikacji.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        // Nie da się odróżnić kont potwierdzonych przez tę migrację od potwierdzonych mailem.
    }
};
