<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Incidencias\App;
use Incidencias\Organizacion;
use Incidencias\OrganizacionPersonalServicio;
use Incidencias\OrganizacionServicio;
use Incidencias\Sector;
use Incidencias\Servicio;
use Incidencias\Turno;
use Incidencias\TurnoOrganizacion;

class OperadorServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $turnoOrganizacion = TurnoOrganizacion::where('fecha_cierre', null)->first();
        $organizacion = Organizacion::where('id', $turnoOrganizacion->organizacion_id)->with('turnos')->first();
        $organizacionPersonalsServicio = OrganizacionPersonalServicio::where('organizacion_id', $organizacion->id)->get();
        $turnos = Turno::whereNotIn('id', [App::$TURNO_NINGUNO])->get();

        return view('auth.operadorServicio.index', ['organizacion' => $organizacion, 'turnos' => $turnos,
            'organizacionPersonalsServicio' => $organizacionPersonalsServicio]);
    }

    public function filtro_fecha(Request $request){

        $turno = $request->get('turno');

        if(in_array(Auth::user()->perfil_id , [App::$PERFIL_ADMINISTRADOR, App::$PERFIL_GERENTE])){
            $organizacion = Organizacion::whereDate('created_at',
                date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                ->where('turno_id', $turno)->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->latest()->first();

        }

        $sectores = Sector::all()->pluck('id')->toArray();
        $servicios = Servicio::all()->pluck('id')->toArray();

        return response()->json([ 'organizacion' => $organizacion, 'sectores' => $sectores, 'servicios' => $servicios ]);

    }

}
