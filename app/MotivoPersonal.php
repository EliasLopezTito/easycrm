<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class MotivoPersonal extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $dates = ['deleted_at'];
}
