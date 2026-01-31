<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    public $timestamps = false;
    const array DEVELOPED_WITHOUT_EXPERIENCE = [
        'PO',
        'PP'
    ];
    const array DEVELOP_EXP_NEEDED = [
        'BASIC' => 100,
        'OUTSIDE_SCHEMA' => 200
    ];

    const array PRIMARY_CHARACTERISTICS = [
        'WW',
        'US',
        'K',
        'ODP',
        'ZR',
        'INT',
        'SW',
        'OGD'
    ];
    const array SECONDARY_CHARACTERISTICS = [
        'A',
        'Żyw',
        'S',
        'Wt',
        'Sz',
        'Mag',
        'PO',
        'PP'
    ];
}
