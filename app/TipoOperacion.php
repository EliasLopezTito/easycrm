<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoOperacion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

}
