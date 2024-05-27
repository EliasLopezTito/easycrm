<?php

namespace Incidencias\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Incidencias\App;
use Incidencias\Cargo;
use Incidencias\ControlGar;
use Incidencias\ControlGarPersonal;
use Incidencias\Grupo;
use Incidencias\GrupoTurnoIncidencia;
use Incidencias\Incidencia;
use Incidencias\IncidenciaHistorial;
use Incidencias\IncidenciaParqueAutomotor;
use Incidencias\IncidenciaPersonal;
use Incidencias\IncidenciaPersonalFijo;
use Incidencias\IncidenciaSector;
use Incidencias\Macro;
use Incidencias\ParqueAutomotor;
use Incidencias\Perfil;
use Incidencias\PersonalFijo;
use Incidencias\Sector;
use Incidencias\User;

class IncidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $incidenciaPersonals = null; $incidenciaParqueAutomotors = null; $incidenciaSectors = null;

        $sectores = in_array(Auth::user()->perfil_id,
            [App::$PERFIL_ADMINISTRADOR, App::$PERFIL_JEFEOPERACION]) ? Sector::orderby('name', 'asc')->get() : Sector::where('macro_id', Auth::user()->macro_id)->orderby('name', 'asc')->get();

        $cargos = Cargo::where('visible', true)->orderBy('order', 'asc')->get();

        if(in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR])){
            $grupos = Grupo::whereNotIn('id', [App::$GRUPO_TODOS, App::$GRUPO_NINGUNO])->get();
        }else{
            $grupos = Grupo::where('id', Auth::user()->grupo_id)->get();
        }

        $incidencia = Incidencia::where('id', GrupoTurnoIncidencia::where('fecha_cierre', null)->first()->incidencia_id)
            ->with('grupos')->with('turnos')->with('historiales')->with('historiales.perfils')->with('historiales.estados')->first();


        if(!in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR])){
            if ($incidencia->grupo_id != Auth::user()->grupo_id){
                Auth::logout();
                return redirect('/login');
            }
        }

        $incidenciaSectors = IncidenciaSector::where('incidencia_id', $incidencia->id)->get();

        $parqueAutomotors = ParqueAutomotor::all();

        $macros = Macro::whereNotIn('id', [App::$MACRO_TODOS,App::$MACRO_NINGUNO])->get();

        $users = User::whereNotIn('perfil_id', [App::$PERFIL_JEFEOPERACION, App::$PERFIL_ADMINISTRADOR])
            ->where('grupo_id', $incidencia->grupo_id)->get();

        $personalFijos = PersonalFijo::all();

        $incidenciaPersonalFijos = IncidenciaPersonalFijo::where('incidencia_id', $incidencia->id)->get();

        if(!in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR, App::$PERFIL_JEFEOPERACION])){
            $incidenciaPersonals = IncidenciaPersonal::where('incidencia_id', $incidencia->id)->where('user_id', Auth::user()->id)->get();
            $incidenciaParqueAutomotors = IncidenciaParqueAutomotor::where('incidencia_id', $incidencia->id)->where('user_id', Auth::user()->id)->get();
        }else{
            $incidenciaPersonals = IncidenciaPersonal::where('incidencia_id', $incidencia->id)->get();
            $incidenciaParqueAutomotors = IncidenciaParqueAutomotor::with('users')->where('incidencia_id', $incidencia->id)->get();
        }

        $estadoPerfil_id = $incidencia->historiales->where('perfil_id', Auth::user()->perfil_id)->first();

        $controlGarPersonals = ControlGarPersonal::where('control_gar_id', ControlGar::where('incidencia_id', $incidencia->id)->first()->id)->get();

        if($estadoPerfil_id != null)
            $estadoPerfil_id = $estadoPerfil_id->estados->id;

        return view('auth.incidencia.index', ['sectores' => $sectores, 'cargos' => $cargos, 'grupos' => $grupos, 'macros' => $macros,
        'incidencia' => $incidencia, 'incidenciaSectors' => $incidenciaSectors, 'incidenciaPersonals' => $incidenciaPersonals,
        'parqueAutomotors' => $parqueAutomotors, 'incidenciaParqueAutomotors' => $incidenciaParqueAutomotors,'estadoPerfil_id' => $estadoPerfil_id,
        'controlGarPersonals' => $controlGarPersonals, 'users' => $users, 'personalFijos' => $personalFijos,  'incidenciaPersonalFijos' => $incidenciaPersonalFijos]);

    }

    public function list_all()
    {
        return response()->json(['data' => Incidencia::all()]);
    }

    public function store(Request $request)
    {
        $status = false; $return = false;
        $IncidenciaSectors = json_decode($request->get('IncidenciaSectors'));
        $IncidenciaPersonals = json_decode($request->get('IncidenciaPersonals'));
        $IncidenciaPersonalFijos = json_decode($request->get('IncidenciaPersonalFijos'));
        $IncidenciaParqueAutomotors = json_decode($request->get('IncidenciaParqueAutomotors'));
        $incidencia = Incidencia::where('id', $request->get('id'))->first();

        if(in_array(Auth::user()->perfil_id, [App::$PERFIL_JEFEOPERACION, App::$PERFIL_ADMINISTRADOR])){

            if($request->get('estado_id') == App::$ESTADO_FINALIZADO){

                $grupoTurnoIncidencia =  GrupoTurnoIncidencia::where('incidencia_id', $incidencia->id)->where('fecha_cierre',null)->first();

                if($grupoTurnoIncidencia == null)
                    return response()->json(['Success' => $status, 'Message' => 'Esta incidencia ya fue cerrada' ]);
                else
                    $incidencia->estado_id = $request->get('estado_id');
                    if(Auth::user()->perfil_id == App::$PERFIL_JEFEOPERACION){
                        $incidencia->user_id = Auth::user()->id;
                        $incidencia->user_name = Auth::user()->user_alternative ? Auth::user()->user_name_alternative . " (Suplente)" : Auth::user()->name;
                    }
                    if($incidencia->save()){
                        $grupoTurnoIncidencia->estado_id = App::$ESTADO_FINALIZADO;
                        $grupoTurnoIncidencia->fecha_cierre = Carbon::now();
                        $grupoTurnoIncidencia->save();

                        $grupo_id = $grupoTurnoIncidencia->grupo_id;
                        $turno_id = $grupoTurnoIncidencia->turno_id;

                        if($grupo_id == App::$GRUPO_3) $grupo_id = App::$GRUPO_1;
                        else $grupo_id++;

                        if($turno_id == App::$TURNO_MANIANA) $turno_id = App::$TURNO_NOCHE;
                        else if($turno_id == App::$TURNO_NOCHE) $turno_id = App::$TURNO_MANIANA;

                        $nuevaIncidencia = new Incidencia();
                        $nuevaIncidencia->grupo_id = $grupo_id;
                        $nuevaIncidencia->turno_id = $turno_id;
                        $nuevaIncidencia->estado_id = App::$ESTADO_ACTIVO;

                        if($nuevaIncidencia->save()){
                            $nuevoGrupoTunoIncidencia = new GrupoTurnoIncidencia();
                            $nuevoGrupoTunoIncidencia->grupo_id = $grupo_id;
                            $nuevoGrupoTunoIncidencia->turno_id = $turno_id;
                            $nuevoGrupoTunoIncidencia->incidencia_id = $nuevaIncidencia->id;
                            $nuevoGrupoTunoIncidencia->estado_id = App::$ESTADO_ACTIVO;
                            $nuevoGrupoTunoIncidencia->fecha_abierta = date("Y-m-d H:i:s");
                            $nuevoGrupoTunoIncidencia->save();

                            $controlGar = new ControlGar();
                            $controlGar->incidencia_id = $nuevaIncidencia->id;
                            $controlGar->grupo_id = $grupo_id;
                            $controlGar->turno_id = $turno_id;
                            $controlGar->estado_id = App::$ESTADO_ACTIVO;
                            $controlGar->save();

                            $perfiles = Perfil::whereNotIn('id', [App::$PERFIL_ADMINISTRADOR,App::$PERFIL_JEFEOPERACION,
                                App::$PERFIL_JEFEGAR, App::$PERFIL_SUPERVISORCECOM1, App::$PERFIL_SUPERVISORCECOM2, App::$PERFIL_SUPERVISORCECOM3])->get();

                            $personalFijos = PersonalFijo::all();

                            foreach ($perfiles as $perfil){
                               $incidenciaHistorial = new IncidenciaHistorial();
                               $incidenciaHistorial->incidencia_id = $nuevaIncidencia->id;
                               $incidenciaHistorial->perfil_id = $perfil->id;
                               $incidenciaHistorial->estado_id = App::$ESTADO_ACTIVO;
                               $incidenciaHistorial->save();
                            }

                            foreach ($personalFijos as $pf){
                                $incidenciaPersonalFijo = new IncidenciaPersonalFijo();
                                $incidenciaPersonalFijo->incidencia_id = $nuevaIncidencia->id;
                                $incidenciaPersonalFijo->personalFijo_id = $pf->id;
                                $incidenciaPersonalFijo->total = 0;
                                $incidenciaPersonalFijo->save();
                            }
                        }
                    }
            }
            if(!in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR, App::$PERFIL_JEFEOPERACION])){ $return = true;}
        }

        if(!in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR, App::$PERFIL_JEFEOPERACION])) {
            $incidenciaHistorial = IncidenciaHistorial::where('incidencia_id', $incidencia->id)
                ->where('perfil_id', Auth::user()->perfil_id)->first();
            $incidenciaHistorial->estado_id = $request->get('estado_perfil_id');
            $incidenciaHistorial->user_name = Auth::user()->user_alternative ? Auth::user()->user_name_alternative . " (Suplente)" : Auth::user()->name;
            $incidenciaHistorial->fecha_cierre = date("Y-m-d H:i:s");
            $incidenciaHistorial->save();
        }

        if(in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR, App::$PERFIL_JEFEOPERACION])){
            foreach ($IncidenciaPersonalFijos as $ipf){
                $incidenciaPersonalFijo = IncidenciaPersonalFijo::where('incidencia_id', $incidencia->id)
                    ->where('personalFijo_id', $ipf->personalFijo_id)->first();
                $incidenciaPersonalFijo->total = $ipf->total;
                $incidenciaPersonalFijo->save();
            }
        }

        foreach ($IncidenciaSectors as $is){
            $IncidenciaSector = IncidenciaSector::where('incidencia_id', $incidencia->id)
                ->where('cargo_id', $is->cargo_id)->where('sector_id', $is->sector_id)->first();
            if($IncidenciaSector != null) {
                $IncidenciaSector->cantidad = $is->cantidad;
            }else {
                $IncidenciaSector = new IncidenciaSector();
                $IncidenciaSector->incidencia_id = $is->incidencia_id;
                $IncidenciaSector->cargo_id = $is->cargo_id;
                $IncidenciaSector->sector_id = $is->sector_id;
                $IncidenciaSector->cantidad = $is->cantidad;
            }
            $IncidenciaSector->save();
        }

        if(in_array(Auth::user()->perfil_id, [App::$PERFIL_JEFEOPERACION, App::$PERFIL_ADMINISTRADOR])) {
            $OldIncidenciaPersonal = IncidenciaPersonal::where('incidencia_id', $incidencia->id)->pluck('id')->toArray();
        }else{
            $OldIncidenciaPersonal = IncidenciaPersonal::where('incidencia_id', $incidencia->id)
                ->where('user_id', Auth::user()->id)->pluck('id')->toArray();
        }

        $array_ids = [];

        if($IncidenciaPersonals != null && count($IncidenciaPersonals) > 0){

            foreach ($IncidenciaPersonals as $ip){
                if($ip->id != null && $ip->id != 0) {
                    $IncidenciaPersonal = IncidenciaPersonal::find($ip->id);
                    array_push($array_ids, $ip->id);
                }else {
                    $IncidenciaPersonal = new IncidenciaPersonal();
                }

                $IncidenciaPersonal->incidencia_id = $incidencia->id;

                if(in_array(Auth::user()->perfil_id, [App::$PERFIL_JEFEOPERACION, App::$PERFIL_ADMINISTRADOR])){
                    if(in_array($ip->sector_id, [App::$SECTOR_1, App::$SECTOR_2, App::$SECTOR_3])){
                        $IncidenciaPersonal->user_id = User::where('grupo_id', $incidencia->grupo_id)->where('macro_id', App::$MACRO_1)->first()->id;
                    }else if(in_array($ip->sector_id, [App::$SECTOR_4, App::$SECTOR_5, App::$SECTOR_6])){
                        $IncidenciaPersonal->user_id = User::where('grupo_id', $incidencia->grupo_id)->where('macro_id', App::$MACRO_2)->first()->id;
                    }else if(in_array($ip->sector_id, [App::$SECTOR_7, App::$SECTOR_8])) {
                        $IncidenciaPersonal->user_id = User::where('grupo_id', $incidencia->grupo_id)->where('macro_id', App::$MACRO_3)->first()->id;
                    }else if(in_array($ip->sector_id, [App::$SECTOR_9])) {
                        $IncidenciaPersonal->user_id = User::where('grupo_id', $incidencia->grupo_id)->where('macro_id', App::$MACRO_RSF)->first()->id;
                    }
                }else{
                    $IncidenciaPersonal->user_id = Auth::user()->id;
                }

                $IncidenciaPersonal->trabajador_id = $ip->trabajador_id;
                $IncidenciaPersonal->motivo_personal_id = $ip->motivo_personal_id;
                $IncidenciaPersonal->sector_id = $ip->sector_id;
                $IncidenciaPersonal->regimen = strtoupper($ip->regimen);
                $IncidenciaPersonal->save();
            }
        }

        if(count($array_ids) <= 0){
            foreach ($OldIncidenciaPersonal as $r){
                $IncidenciaPersonal = IncidenciaPersonal::find($r);
                $IncidenciaPersonal->delete();
            }
        }else{
            $result = array_diff($OldIncidenciaPersonal, $array_ids);
            if(count($result) > 0){
                foreach ($result as $r){
                    $IncidenciaPersonal = IncidenciaPersonal::find($r);
                    $IncidenciaPersonal->delete();
                }
            }
        }

        if($IncidenciaParqueAutomotors != null && count($IncidenciaParqueAutomotors) > 0) {
            foreach ($IncidenciaParqueAutomotors as $ipt) {

                $IncidenciaParqueAutomotor = IncidenciaParqueAutomotor::where('incidencia_id', $incidencia->id)
                    ->where('user_id', $ipt->user_id)->where('parque_automotor_id', $ipt->parque_automotor_id)->first();

                if ($IncidenciaParqueAutomotor != null) {
                    $IncidenciaParqueAutomotor->operativo = $ipt->operativo;
                    $IncidenciaParqueAutomotor->inoperativo = $ipt->inoperativo;
                } else {
                    $IncidenciaParqueAutomotor = new IncidenciaParqueAutomotor();
                    $IncidenciaParqueAutomotor->incidencia_id = $incidencia->id;
                    $IncidenciaParqueAutomotor->user_id = $ipt->user_id;
                    $IncidenciaParqueAutomotor->parque_automotor_id = $ipt->parque_automotor_id;
                    $IncidenciaParqueAutomotor->operativo = $ipt->operativo;
                    $IncidenciaParqueAutomotor->inoperativo = $ipt->inoperativo;
                }
                $IncidenciaParqueAutomotor->save();
            }
        }

        $status = true;

        return response()->json(['Success' => $status, 'Return' => $return  ]);
    }


    public function filtro_fecha(Request $request){

        $controlGarPersonals = null; $incidenciaParqueAutomotors = null; $users = null;
        $grupo = Auth::user()->grupo_id;

        if(in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR]))
            $grupo = $request->get('grupo');

        $incidencia = Incidencia::whereDate('created_at',
            date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
            ->where('grupo_id', $grupo)->with('personalFijos')->with('personalFijos.personalFijo')->with('historiales')->with('historiales.perfils')->with('historiales.estados')
            ->with('personales')->with('personales.trabajadores')->with('personales.sectores')->with('personales.motivosPersonal')->with('sectores')->with('sectores.cargos')->with('parqueAutomotors')->first();

        $sectores = Sector::all()->pluck('id')->toArray();
        $cargos = Cargo::where('visible', true)->orderBy('order', 'asc')->get();
        $parqueAutomotors = ParqueAutomotor::all();

        if($incidencia != null) {
            $controlGarPersonals = ControlGarPersonal::where('control_gar_id', ControlGar::where('incidencia_id', $incidencia->id)->first()->id)->get();
            $incidenciaParqueAutomotors = IncidenciaParqueAutomotor::with('parqueAutomotors')->where('incidencia_id', $incidencia->id)->get();
            $users = User::whereNotIn('macro_id', [App::$MACRO_TODOS,App::$MACRO_NINGUNO])
                ->whereNotIn('perfil_id', [App::$PERFIL_ADMINISTRADOR,App::$PERFIL_JEFEOPERACION, App::$PERFIL_JEFEGAR])
                ->where('grupo_id', $incidencia->grupo_id)->get();
        }

        return response()->json([ 'incidencia' => $incidencia, 'controlGarPersonals' => $controlGarPersonals, 'cargos' => $cargos,
            'incidenciaParqueAutomotors' => $incidenciaParqueAutomotors, 'sectores' => $sectores, 'parqueAutomotors' => $parqueAutomotors,
            'users' => $users]);

    }
}
