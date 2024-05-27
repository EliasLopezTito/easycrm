<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'order'
    ];

    protected $dates = ['deleted_at'];
}
