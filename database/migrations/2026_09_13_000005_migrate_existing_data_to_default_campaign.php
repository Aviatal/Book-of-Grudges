<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const array TENANT_TABLES = ['heroes', 'tokens', 'drawings', 'messages', 'assets'];

    public function up(): void
    {
        $this->ensurePartialUniqueIndex();

        $ownerId = DB::table('users')->where('is_admin', true)->value('id');

        if ($ownerId !== null) {
            $campaignId = DB::table('campaigns')->insertGetId([
                'name'         => 'Kampania oryginalna',
                'owner_id'     => $ownerId,
                'invite_code'  => Str::random(10),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $memberRows = DB::table('users')->pluck('id')->map(fn (int $userId): array => [
                'campaign_id' => $campaignId,
                'user_id'     => $userId,
                'role'        => $userId === $ownerId ? 'gm' : 'player',
                'joined_at'   => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ])->all();
            DB::table('campaign_members')->insert($memberRows);

            foreach (self::TENANT_TABLES as $table) {
                DB::table($table)->update(['campaign_id' => $campaignId]);
            }

            DB::table('users')->where('id', $ownerId)->update(['is_superadmin' => true]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('is_active');
        });

        $gmUserIds = DB::table('campaign_members')->where('role', 'gm')->pluck('user_id');
        DB::table('users')->whereIn('id', $gmUserIds)->update(['is_admin' => true]);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE heroes DROP CONSTRAINT IF EXISTS heroes_user_id_campaign_id_unique');
        }
        DB::statement('DROP INDEX IF EXISTS heroes_user_id_campaign_id_unique');
    }

    /**
     * heroes ma SoftDeletes — zwykły unikalny indeks (user_id, campaign_id) blokowałby
     * stworzenie nowej postaci po usunięciu starej, a przy backfillu istniejących danych
     * (usunięci + aktywni bohaterowie tego samego usera trafiający do tej samej kampanii)
     * od razu naruszyłby unikalność. Indeks częściowy liczy tylko aktywnych bohaterów.
     * `DROP INDEX IF EXISTS` na starcie czyści ewentualny nie-częściowy indeks z wcześniejszej
     * wersji tej serii migracji, jeśli już zdążył powstać.
     */
    private function ensurePartialUniqueIndex(): void
    {
        $driver = DB::getDriverName();

        // Na pgsql `$table->unique()` tworzy CONSTRAINT (nie goły indeks) — indeks stojący
        // za nim nie da się skasować przez DROP INDEX, dopóki constraint go trzyma.
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE heroes DROP CONSTRAINT IF EXISTS heroes_user_id_campaign_id_unique');
        }
        DB::statement('DROP INDEX IF EXISTS heroes_user_id_campaign_id_unique');

        if (in_array($driver, ['pgsql', 'sqlite'], true)) {
            DB::statement('CREATE UNIQUE INDEX heroes_user_id_campaign_id_unique ON heroes (user_id, campaign_id) WHERE deleted_at IS NULL');
        }
    }
};
