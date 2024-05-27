<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Local extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sede_id', 'name'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    function sedes()
    {
        return $this->belongsTo('\easyCRM\Sede', 'sede_id');
    }
}
