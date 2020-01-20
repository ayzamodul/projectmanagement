<?php

namespace ayzamodul\projectmanagement\Models;

use ayzamodul\projectmanagement\Models\Gorev;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use ayzamodul\projectmanagement\Models\Gorevler;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Feedback extends Authenticatable
{
    protected $table = 'work_feedback';
    protected $guarded = [];
}