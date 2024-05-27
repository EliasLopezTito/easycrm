<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_id', 'departament_id','city_id','name', 'href', 'statu'
    ];

    protected $dates = ['deleted_at'];

    function image() {
        return $this->belongsTo('\NavegapComprame\Image', 'image_id');
    }

    function departament() {
        return $this->belongsTo('\NavegapComprame\Departament', 'departament_id');
    }

    function city() {
        return $this->belongsTo('\NavegapComprame\City', 'city_id');
    }
}
