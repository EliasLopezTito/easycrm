<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Sede;
use easyCRM\Local;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;

class LocalController extends Controller
{
    public function filtroLocal($id)
    {
        return response()->json(Local::where('sede_id', $id)->orderBy('name', 'asc')->get());
    }

}
