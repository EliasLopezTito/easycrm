<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;

class UserTypeSend extends Model
{
    protected $fillable = [ 'user_id', 'type_send_id' ];

    public $timestamps = false;

    public function type_sends()
    {
        return $this->belongsTo('\NavegapComprame\TypeSend', 'type_send_id');
    }
}
