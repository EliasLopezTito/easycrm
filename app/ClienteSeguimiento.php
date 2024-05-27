<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteSeguimiento extends Model
{
    use SoftDeletes;

    protected $fillable = [
       'cliente_id', 'accion_id', 'estado_id', 'estado_detalle_id', 'comentario', 'accion_realizar_id', 'fecha_accion_realizar',
       'hora_accion_realizar','user_id_register'
    ];

    protected $dates = ['deleted_at'];

    function clientes()
    {
        return $this->belongsTo('\easyCRM\Cliente', 'cliente_id');
    }

    function acciones()
    {
        return $this->belongsTo('\easyCRM\Accion', 'accion_id');
    }

    function estados()
    {
        return $this->belongsTo('\easyCRM\Estado', 'estado_id');
    }

    function estadoDetalle()
    {
        return $this->belongsTo('\easyCRM\EstadoDetalle', 'estado_detalle_id');
    }

    function accionRealizar()
    {
        return $this->belongsTo('\easyCRM\Accion', 'accion_realizar_id');
    }

    function users()
    {
        return $this->belongsTo('\easyCRM\User', 'user_id_register');
    }

}
