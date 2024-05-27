<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeSend extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'name' ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];
}
