<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoDetalle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'estado_id', 'name'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    function estados()
    {
        return $this->belongsTo('\easyCRM\Estado', 'estado_id');
    }
}
