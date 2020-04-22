<?php

namespace ayzamodul\projectmanagement\Models;

use Illuminate\Notifications\Notifiable;
use ayzamodul\projectmanagement\Models\Comment;
use ayzamodul\projectmanagement\Models\Kullanici;
use ayzamodul\projectmanagement\Models\Gorev;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gorevler extends Authenticatable{
    protected $table = 'tasks';


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = true;


    public function entry()
    {
        return $this->belongsTo(Gorev::class);
    }
    public function users()
{
    return $this->belongsToMany(Kullanici::class,'kullanici_gorev');
}
    public function calisanlar()
    {
        return $this->belongsToMany(Kullanici::class,'kullanici_gorev');
    }

    public function work(){
        return $this->belongsTo(Work::class,'task_id')->where('isDelete',0);
    }
}
