<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departament extends Model
{
    use SoftDeletes ;

    protected $fillable = [ 'name' ];

    protected $dates = ['deleted_at'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
