<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LugarIncidencia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $dates = ['deleted_at'];
}
