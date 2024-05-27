<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'order_id', 'product_id', 'code',  'quantity', 'subtotal' ];

    protected $dates = ['deleted_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo('\NavegapComprame\Product', 'product_id');
    }

}
