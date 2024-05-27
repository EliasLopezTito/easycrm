<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'estado'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];
}
