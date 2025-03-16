<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSkill extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $table = 'hero_skill';
}
