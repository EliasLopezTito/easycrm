<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialReasignar extends Model
{
    use SoftDeletes;

    protected $fillable = [
       'cliente_id', 'user_id',  'vendedor_id', 'observacion'
    ];

    protected $dates = ['deleted_at'];

    function clientes()
    {
        return $this->belongsTo('\easyCRM\Cliente', 'cliente_id');
    }

    function users()
    {
        return $this->belongsTo('\easyCRM\User', 'user_id');
    }

    function vendedores()
    {
        return $this->belongsTo('\easyCRM\User', 'vendedor_id');
    }

}
