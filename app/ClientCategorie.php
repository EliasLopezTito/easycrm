<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientCategorie extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'client_id', 'categorie_id' ];

    protected $dates = ['deleted_at'];

    public function categories()
    {
        return $this->belongsTo(Categorie::class);
    }
}
