<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComment extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'client_id', 'product_id', 'comment' ];

    protected $dates = ['deleted_at'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
