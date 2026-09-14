<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const array TABLES = ['heroes', 'tokens', 'drawings', 'messages', 'assets'];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('campaign_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }

        // Unikalność (user_id, campaign_id) na heroes jest tworzona w kolejnej migracji jako
        // indeks częściowy (WHERE deleted_at IS NULL) — heroes ma SoftDeletes, więc zwykły
        // unikalny indeks blokowałby ponowne stworzenie postaci po usunięciu poprzedniej.
    }

    public function down(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('campaign_id');
            });
        }
    }
};
