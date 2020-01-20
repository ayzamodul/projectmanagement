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

class KullaniciGorev extends Authenticatable
{
    protected $table = 'kullanici_gorev';


    protected $fillable = ['kullanici_id','gorev_id','gorevler_id'];

}
