<?php

namespace App\Console\Commands;

use App\Models\Characteristic;
use App\Models\Hero;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:reset-fortune-points')]
#[Description('Codzienny reset punktów szczęścia')]
class ResetFortunePoints extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::statement("
            UPDATE heroes
            SET fortune_points = hc.start_value + hc.advancement
            FROM hero_characteristics AS hc
            WHERE heroes.id = hc.hero_id
            AND hc.characteristic_id = :char_id
        ", ['char_id' => Characteristic::FATE_POINTS_CHARACTERISTIC_ID]);
    }
}
