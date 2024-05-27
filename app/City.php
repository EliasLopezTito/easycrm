<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'departament_id', 'name', 'comision' ];

    protected $dates = ['deleted_at'];

    public function departaments()
    {
        return $this->belongsTo(Departament::class);
    }

}
