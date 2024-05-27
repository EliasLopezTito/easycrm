<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semestre extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'division'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

}
