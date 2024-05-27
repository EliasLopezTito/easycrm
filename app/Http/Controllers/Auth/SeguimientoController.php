<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Accion;
use easyCRM\App;
use easyCRM\Carrera;
use easyCRM\Cliente;
use easyCRM\ClienteMatricula;
use easyCRM\ClienteSeguimiento;
use easyCRM\Enterado;
use easyCRM\Estado;
use easyCRM\EstadoDetalle;
use easyCRM\Fuente;
use easyCRM\Modalidad;
use easyCRM\Provincia;
use easyCRM\Turno;
use easyCRM\User;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguimientoController extends Controller
{
    public function index()
    {
        $Vendedores = User::whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA])->where('activo', true)
            ->orderby('name', 'asc')->get();
    
        return view('auth.seguimiento.index', ['Vendedores' => $Vendedores]);
    }

    public function usuarios()
    {
        return response()->json(['data' => User::all()]);
    }
}
