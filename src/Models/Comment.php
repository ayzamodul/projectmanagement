<?php

namespace ayzamodul\projectmanagement\Models;
use ayzamodul\projectmanagement\Models\Gorev;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Comment extends Authenticatable
{

const CREATED_AT = 'created_at';
const UPDATED_AT = 'updated_at';
    protected $table = 'comments';
    protected $guarded = [];
    public $timestamps = true;


    public function entry()
    {
      return $this->belongsTo(Gorev::class);
    }



}
