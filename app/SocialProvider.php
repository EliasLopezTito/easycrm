<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model
{

    protected $fillable = [ 'provider_id', 'provider' ];

    public function client(){

        return $this->belongsTo(Client::class);

    }

}
