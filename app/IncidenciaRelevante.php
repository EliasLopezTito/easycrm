<?php

namespace Incidencias;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidenciaRelevante extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'turno_id', 'user_id', 'nro_incidencia', 'fecha', 'hora', 'lugar_incidencia_id', 'nro_calle', 'longitud', 'latitud',
        'categoria_id', 'clasificacionIncidencia_id', 'modalidadIncidencia_id', 'vehiculo_id', 'placa', 'arma_id', 'objeto',
        'macro_id', 'sector_id', 'subsector_id', 'descripcion_incidencia', 'trabajador_id', 'fecha_registro', 'image_one_id',
        'image_two_id', 'image_three_id'
    ];

    protected $dates = ['deleted_at'];

    function turnos() {
        return $this->belongsTo('\Incidencias\Turno', 'turno_id');
    }

    function users() {
        return $this->belongsTo('\Incidencias\User', 'user_id');
    }

    function categories() {
        return $this->belongsTo('\Incidencias\Categoria', 'categoria_id');
    }

    function trabajadors() {
        return $this->belongsTo('\Incidencias\Trabajador', 'trabajador_id');
    }

    function lugares() {
        return $this->belongsTo('\Incidencias\LugarIncidencia', 'lugar_incidencia_id');
    }

    function clasificacionIncidencias(){
        return $this->belongsTo('\Incidencias\ClasificacionIncidencia', 'clasificacionIncidencia_id');
    }

    function modalidadIncidencias(){
        return $this->belongsTo('\Incidencias\ModalidadIncidencia', 'modalidadIncidencia_id');
    }

    function vehiculos() {
        return $this->belongsTo('\Incidencias\Vehiculo', 'vehiculo_id');
    }

    function armas() {
        return $this->belongsTo('\Incidencias\Arma', 'arma_id');
    }

    function macros() {
        return $this->belongsTo('\Incidencias\Macro', 'macro_id');
    }

    function sectors() {
        return $this->belongsTo('\Incidencias\Sector', 'sector_id');
    }

    function subsectors() {
        return $this->belongsTo('\Incidencias\SubSector', 'subsector_id');
    }

    function imagesOne() {
        return $this->belongsTo('\Incidencias\Image', 'image_one_id');
    }

    function imagesTwo() {
        return $this->belongsTo('\Incidencias\Image', 'image_two_id');
    }

    function imagesThree() {
        return $this->belongsTo('\Incidencias\Image', 'image_three_id');
    }

}
