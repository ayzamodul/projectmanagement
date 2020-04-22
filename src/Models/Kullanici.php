<?php

namespace ayzamodul\projectmanagement\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use ayzamodul\projectmanagement\Models\Gorev;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Kullanici extends Authenticatable
{



    protected $table = "administrator";

    protected $guarded = [];


    public function gorevler()
    {
        return $this->belongsToMany(Gorev::class, 'kullanici_gorev');
    }



}
