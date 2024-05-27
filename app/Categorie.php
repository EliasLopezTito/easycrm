<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'image_id', 'name' ];

    protected $dates = ['deleted_at'];

    function image() {
        return $this->belongsTo('\NavegapComprame\Image', 'image_id');
    }

    public function productCategorie()
    {
        return $this->hasMany(ProductCategorie::class);
    }
}
