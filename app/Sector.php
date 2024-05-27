<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'macro_id', 'name'
    ];

    protected $dates = ['deleted_at'];

    function macros() {
        return $this->belongsTo('\Incidencias\Macro', 'macro_id');
    }
}
