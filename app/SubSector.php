<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubSector extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sector_id', 'name'
    ];

    protected $dates = ['deleted_at'];
}
