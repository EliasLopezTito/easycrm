<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'order', 'background'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    function estadoDetalles()
    {
        return $this->hasMany('\easyCRM\EstadoDetalle', 'estado_id');
    }

}
