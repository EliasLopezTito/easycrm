<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;

class UserTypePay extends Model
{
    protected $fillable = [ 'user_id', 'type_pay_id' ];

    public $timestamps = false;

    public function type_pays()
    {
        return $this->belongsTo('\NavegapComprame\TypePay', 'type_pay_id');
    }
}
