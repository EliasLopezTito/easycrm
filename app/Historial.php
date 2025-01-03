<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Historial extends Model
{
    use SoftDeletes;

    protected $table = 'historial_reasignars'; // Nombre de la tabla


    protected $fillable = [
        'cliente_id','user_id','vendedor_id','observacion'
    ];

    public $timestamps = false;



    protected $dates = ['created_at', 'updated_at','deleted_at']; // Esto asegura que las fechas se traten como Carbon


    // Historial.php
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendedores()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

}
