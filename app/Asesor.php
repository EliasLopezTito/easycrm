<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asesor extends Model
{
    use SoftDeletes;

    protected $table = "users";
    protected $fillable = [
        'id','name'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    
}