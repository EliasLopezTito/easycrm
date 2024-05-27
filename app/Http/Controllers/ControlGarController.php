<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Incidencias\App;
use Incidencias\ControlGar;
use Incidencias\ControlGarPersonal;
use Incidencias\Grupo;
use Incidencias\GrupoTurnoIncidencia;

class ControlGarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR])){
            $grupos = Grupo::where('id', '!=', 1)->get();
        }else{
            $grupos = Grupo::where('id', Auth::user()->grupo_id)->get();
        }

        $controlGar = ControlGar::where('incidencia_id', GrupoTurnoIncidencia::where('fecha_cierre', '=', null)->first()->incidencia_id)
            ->with('grupos')->with('turnos')->first();

        $controlGarPersonals = ControlGarPersonal::where('control_gar_id', $controlGar->id)->get();

        return view('auth.controlGar.index', ['grupos' => $grupos, 'controlGar' => $controlGar,
            'controlGarPersonals' => $controlGarPersonals ]);
    }

    public function store(Request $request)
    {
        $status = false;
        $ControlGarPersonals = json_decode($request->get('ControlGarPersonals'));
        $ControlGar = ControlGar::find($request->get('id'));
        $ControlGar->estado_id =  $request->get('estado_id');
        $ControlGar->user_id =  Auth::user()->id;
        $ControlGar->user_name =  Auth::user()->user_alternative ? Auth::user()->user_name_alternative. " (Suplente)" : Auth::user()->name;

        $OldControlGarPersonal = ControlGarPersonal::where('control_gar_id', $ControlGar->id)->pluck('id')->toArray();
        $array_ids = [];

        if($ControlGar->save()){

            if($ControlGarPersonals != null && count($ControlGarPersonals) > 0){

                foreach ($ControlGarPersonals as $cp){
                    if($cp->id != null && $cp->id != 0) {
                        $ControlGarPersonal = ControlGarPersonal::find($cp->id);
                        array_push($array_ids, $cp->id);
                    }else {
                        $ControlGarPersonal = new ControlGarPersonal();
                    }
                    $ControlGarPersonal->control_gar_id = $ControlGar->id;
                    $ControlGarPersonal->trabajador_id = $cp->trabajador_id;
                    $ControlGarPersonal->motivo_personal_id = $cp->motivo_personal_id;
                    if($cp->radio == null || $cp->radio == "")
                        $ControlGarPersonal->radio = 0;
                    else
                        $ControlGarPersonal->radio = $cp->radio;
                    $ControlGarPersonal->regimen = strtoupper($cp->regimen);
                    $ControlGarPersonal->save();
                }

                $result = array_diff($OldControlGarPersonal, $array_ids);
                if(count($result) > 0){
                    foreach ($result as $r){
                        $ControlGarPersonal = ControlGarPersonal::find($r);
                        $ControlGarPersonal->delete();
                    }
                }
            }

            if(count($array_ids) <= 0){
                foreach ($OldControlGarPersonal as $r){
                    $ControlGarPersonal = ControlGarPersonal::find($r);
                    $ControlGarPersonal->delete();
                }
            }else{
                $result = array_diff($OldControlGarPersonal, $array_ids);
                if(count($result) > 0){
                    foreach ($result as $r){
                        $ControlGarPersonal = ControlGarPersonal::find($r);
                        $ControlGarPersonal->delete();
                    }
                }
            }

            $status = true;
        }

        return response()->json(['Success' => $status, 'data' => $ControlGarPersonals ]);
    }

    public function filtro_fecha(Request $request){

        $grupo = Auth::user()->grupo_id;

        if(in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR]))
            $grupo = $request->get('grupo');

        $controlGar = ControlGar::whereDate('created_at',
            date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha"))) ))
            ->where('grupo_id', $grupo)->with('grupos')->with('personales')->with('personales.trabajadores')
            ->with('personales.motivosPersonal')->first();

        return response()->json([ 'controlGar' => $controlGar ]);

    }

    public function deletePersonal(Request $request){
        $status = false;

        $id = $request->get('id');
        $entity = ControlGarPersonal::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
