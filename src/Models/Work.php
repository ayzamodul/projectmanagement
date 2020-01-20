<?php

namespace ayzamodul\projectmanagement\Models;

use ayzamodul\projectmanagement\Models\Gorev;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use ayzamodul\projectmanagement\Models\Gorevler;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Work extends Authenticatable
{
    protected $table ='work_calender';
    protected $guarded = [];
    public function tasks()
    {
        return $this->belongsTo(Gorev::class,'project_name','project_name');
    }
    public function work(){
        return $this->belongsTo(Gorevler::class,'task_id');
    }
    public function user()
    {
        return $this->hasMany(Yonetici::class, 'id', 'user_id');
    }
    public function add_user()
    {
        return $this->hasMany(Yonetici::class, 'id', 'add_id');
    }
    public function end_date()
    {
        return $this->belongsTo(Time::class, 'work_id', 'id')->where('status',2);
    }
}
