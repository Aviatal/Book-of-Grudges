<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $characteristics = \App\Models\HeroCharacteristic::query()->get()->groupBy('hero_id');
        Schema::create('characteristics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_name');
        });
        DB::table('hero_characteristics')->truncate();
        Schema::table('hero_characteristics', function (Blueprint $table) {
            $table->dropColumn(['name', 'short_name']);
            $table->unsignedInteger('characteristic_id')->after('hero_id');
            $table->foreign('characteristic_id')->references('id')->on('characteristics')->onDelete('CASCADE');
        });
        $wwId = 1;
        $usId = 2;
        $kId = 3;
        $odpId = 4;
        $zrId = 5;
        $intId = 6;
        $swId = 7;
        $ogdId = 8;
        $aId = 9;
        $zywId = 10;
        $sId = 11;
        $wtId = 12;
        $szId = 13;
        $magId = 14;
        $poId = 15;
        $ppId = 16;
        DB::table('characteristics')->insert([
            [
                'name' => 'Walka wręcz',
                'short_name' => 'WW',
            ],
            [
                'name' => 'Umiejętności strzeleckie',
                'short_name' => 'US',
            ],
            [
                'name' => 'Krzepa',
                'short_name' => 'K',
            ],
            [
                'name' => 'Odporność',
                'short_name' => 'Odp',
            ],
            [
                'name' => 'Zręczność',
                'short_name' => 'Zr',
            ],
            [
                'name' => 'Inteligencja',
                'short_name' => 'Int',
            ],
            [
                'name' => 'Siła woli',
                'short_name' => 'SW',
            ],
            [
                'name' => 'Ogłada',
                'short_name' => 'Ogd',
            ],
            [
                'name' => 'Ataki',
                'short_name' => 'A',
            ],
            [
                'name' => 'Żywotność',
                'short_name' => 'Zyw',
            ],
            [
                'name' => 'Siła',
                'short_name' => 'S',
            ],
            [
                'name' => 'Wytrzymałość',
                'short_name' => 'Wt',
            ],
            [
                'name' => 'Szybkość',
                'short_name' => 'Sz',
            ],
            [
                'name' => 'Magia',
                'short_name' => 'Mag',
            ],
            [
                'name' => 'Punkty obłędu',
                'short_name' => 'PO',
            ],
            [
                'name' => 'Punkty przeznaczenia',
                'short_name' => 'PP',
            ],
        ]);
        $insertData = [];
        foreach ($characteristics as $heroId => $hero) {
            foreach ($hero as $characteristic) {
                $insertData[] = [
                    'hero_id' => $heroId,
                    'characteristic_id' => ${strtolower($characteristic->short_name) . 'Id'},
                    'start_value' => $characteristic->start_value,
                    'advancement' => $characteristic->advancement,
                    'current_value' => $characteristic->current_value,
                    'updated_at' => now(),
                    'created_at' => now(),
                ];

            }
        }
        DB::table('hero_characteristics')->insert($insertData);
    }
};
