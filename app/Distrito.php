<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distrito extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'provincia_id', 'name'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    function provincias()
    {
        return $this->belongsTo('\easyCRM\Provincia', 'provincia_id');
    }
}
