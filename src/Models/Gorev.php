<?php

namespace ayzamodul\projectmanagement\Models;

use App\Models\Yonetici;
use Illuminate\Notifications\Notifiable;
use ayzamodul\projectmanagement\Models\Comment;
use ayzamodul\projectmanagement\Models\Kullanici;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ayzamodul\projectmanagement\Models\Gorevler;
use Illuminate\Database\Eloquent\Model;
use ayzamodul\projectmanagement\Models\Work;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gorev extends Authenticatable
{
    use Notifiable;

    protected $table = 'project';
     const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    protected $guarded = [];


public function comments()
{
    return $this->hasMany(Comment::class)->where('deleted',0);
}



public function calisanlar()
{
    return $this->belongsToMany(Kullanici::class,'kullanici_gorev');
}

public function tasks()
{
    return $this->hasMany(Gorevler::class)->where('deleted',0);
}
    public function work()
    {
        return $this->hasMany(Work::class, 'project_name', 'project_name')->orderByRaw('FIELD(status,"3","0","1","2","4")')->where('deleted',0);
    }
    public function leader()
    {
        return $this->hasMany(Yonetici::class, 'id', 'project_leader');
    }





}
