<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizacionSector extends Model
{
    protected $fillable = [
        'organizacion_id', 'sector_id', 'cantidad'
    ];

    public $timestamps = false;

    function organizacions() {
        return $this->belongsTo('\Incidencias\Organizacion', 'organizacion_id');
    }

    function sectores() {
        return $this->belongsTo('\Incidencias\Sector', 'sector_id');
    }
}
