<?php

namespace ayzamodul\projectmanagement\Models;
use ayzamodul\projectmanagement\Models\Gorev;
use Illuminate\Database\Eloquent\SoftDeletes;
use ayzamodul\projectmanagement\Models\Work;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Time extends Authenticatable
{
    protected $table = "working_times";

    protected $guarded = [];



    public function time()
    {
        return $this->belongsTo(Work::class,'work_id','id');
    }



}
