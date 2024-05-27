<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCategorie extends Model
{

    protected $fillable = [ 'user_id', 'categorie_id' ];

    public $timestamps = false;

    function users() {
        return $this->belongsTo('\NavegapComprame\User', 'user_id');
    }

    function categories() {
        return $this->belongsTo('\NavegapComprame\Categorie', 'categorie_id');
    }

}
