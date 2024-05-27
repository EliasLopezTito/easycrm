<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;

class PatrullajeIntegradoSector extends Model
{
    protected $fillable = [
        'patrullajeIntegrado_id', 'sector_id'
    ];

    function sectors() {
        return $this->belongsTo('\Incidencias\Sector', 'sector_id');
    }


    public $timestamps = false;
}
