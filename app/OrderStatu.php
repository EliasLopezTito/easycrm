<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatu extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'name' ];

    protected $dates = ['deleted_at'];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
