<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategorie extends Model
{
    protected $fillable = [ 'product_id', 'categorie_id' ];

    public $timestamps = false;

    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

}
