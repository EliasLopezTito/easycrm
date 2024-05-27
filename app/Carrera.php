<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrera extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'modalidad_id', 'name', 'alias', 'semipresencial'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    function modalidades()
    {
        return $this->belongsTo('\easyCRM\Modalidad', 'modalidad_id');
    }
}
