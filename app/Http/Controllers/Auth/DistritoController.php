<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Distrito;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;

class DistritoController extends Controller
{
    public function filtroDistrito($id)
    {
        return response()->json(Distrito::where('provincia_id', $id)->orderBy('name', 'asc')->get());
    }
}
