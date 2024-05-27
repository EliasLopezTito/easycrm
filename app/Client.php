<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NavegapComprame\Notifications\ClientResetPasswordNotification;

class Client extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $guard = 'client';

    protected $fillable = [ 'departament_id', 'city_id', 'name', 'last_name', 'dni', 'email', 'phone', 'date_birthday',  'password'];

    protected $hidden = [ 'password', 'remember_token' ];

    protected $dates = ['deleted_at'];

    public function socialProviders()
    {
        return $this->hasMany(SocialProvider::class);
    }

    public function clientFavorite()
    {
        return $this->hasMany(ClientFavorite::class);
    }

    public function clientCategories()
    {
        return $this->hasMany(ClientCategorie::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ClientResetPasswordNotification($token));
    }

    function departament() {
        return $this->belongsTo('\NavegapComprame\Departament', 'departament_id');
    }

    function city() {
        return $this->belongsTo('\NavegapComprame\City', 'city_id');
    }

}
