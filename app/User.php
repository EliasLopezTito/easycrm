<?php

namespace easyCRM;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\View\View;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
      'profile_id', 'turno_id', 'name', 'last_name', 'email', 'password', 'turno', 'activo', 'recibe_lead'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function profiles()
    {
        return $this->belongsTo('\easyCRM\Profile', 'profile_id');
    }

    function turnos()
    {
        return $this->belongsTo('\easyCRM\Turno', 'turno_id');
    }
}
