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
}
