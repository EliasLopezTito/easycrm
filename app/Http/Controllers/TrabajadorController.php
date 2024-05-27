<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Incidencias\App;
use Incidencias\Contrato;
use Incidencias\Grupo;
use Incidencias\Image;
use Incidencias\Macro;
use Incidencias\PersonalCargo;
use Incidencias\Trabajador;
use Incidencias\Turno;

class TrabajadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.trabajador.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Trabajador::with('personalCargos')
            ->with('macros')->with('grupos')->with('contratos')->with('turnos')->with('turnosSuplente')
            ->get()]);
    }

    public function listadoFiltro(Request $request){

        $data = array();
        $trabajadores = Trabajador::where('name', 'like', '%' .$request->get('name') . '%')
            ->with('contratos')->get();
        foreach ($trabajadores as $t){
            $array  = array('id' => $t->id, 'text' => $t->name);
            array_push($data, $array);
        }

        return response()->json(['data' => $data]);
    }

    public function obtener(Request $request)
    {
        $entity = Trabajador::where('id', $request->get('id'))->with('contratos')->first();
        return response()->json(['data' => $entity]);
    }

    public function partialView($id)
    {
        $entity = null;

        if($id != 0) $entity = Trabajador::where('id', $id)->with('images')
            ->with('personalCargos')
            ->with('macros')->with('grupos')->with('contratos')->first();

        $personalCargos = PersonalCargo::all();
        $macros = Macro::all();
        $grupos = Grupo::where('id', '!=', 1)->get();
        $contratos = Contrato::all();
        $turnos = Turno::all();

        return view('auth.trabajador._Maintenance', ['trabajador' => $entity, 'turnos' => $turnos])
            ->with('personalCargos', $personalCargos)
            ->with('macros', $macros)
            ->with('grupos', $grupos)->with('contratos', $contratos);
    }

    public function store(Request $request)
    {
        $status = false;$image_id = null; $existPersonalCargo = false; $grupo = null; $macro_id = $request->get('macro_id');
        $random = Str::upper(str_random(4)); $messagePersonalCargo = null; $nameGrupo = null;

        if($request->get('personalCargo_id') == App::$PERSONALCARGO_JEFEOPERACION){
            $entity = null;
            switch ($request->get('grupo_id')){
                case App::$GRUPO_1: $grupo = App::$GRUPO_1; $nameGrupo = "Grupo 1";break;
                case App::$GRUPO_2: $grupo = App::$GRUPO_2; $nameGrupo = "Grupo 2";break;
                case App::$GRUPO_3: $grupo = App::$GRUPO_3; $nameGrupo = "Grupo 3";break;
                default: $grupo = App::$GRUPO_NINGUNO;break;
            }

            $entity = Trabajador::where('personalCargo_id', App::$PERSONALCARGO_JEFEOPERACION)->where('id', '!=', $request->get('id'))
                ->where('grupo_id', $grupo)->where('deleted_at', NULL)->first();

            if($entity && $macro_id != App::$MACRO_NINGUNO && $grupo != App::$GRUPO_NINGUNO){
                $existPersonalCargo = true;  $messagePersonalCargo = "Ya existe un trabajador con cargo Jefe de Operaciones del ".$nameGrupo." !!!";
            }

        }else if($request->get('personalCargo_id') ==  App::$PERSONALCARGO_MACRO1){
            $entity = null;
            switch ($request->get('grupo_id')){
                case App::$GRUPO_1: $grupo = App::$GRUPO_1; $nameGrupo = "Grupo 1";break;
                case App::$GRUPO_2: $grupo = App::$GRUPO_2; $nameGrupo = "Grupo 2";break;
                case App::$GRUPO_3: $grupo = App::$GRUPO_3; $nameGrupo = "Grupo 3";break;
                default: $grupo = App::$GRUPO_NINGUNO;break;
            }

            $entity = Trabajador::where('personalCargo_id', App::$PERSONALCARGO_MACRO1)->where('id', '!=', $request->get('id'))
                ->where('grupo_id', $grupo)->where('deleted_at', NULL)->first();

            if($entity && $macro_id != App::$MACRO_NINGUNO && $grupo != App::$GRUPO_NINGUNO){
                $existPersonalCargo = true;  $messagePersonalCargo = "Ya existe un trabajador con cargo Supervisor Macro 1 del ".$nameGrupo." !!!";
            }

        }else if($request->get('personalCargo_id') == App::$PERSONALCARGO_MACRO2){
            $entity = null;
            switch ($request->get('grupo_id')){
                case App::$GRUPO_1: $grupo = App::$GRUPO_1; $nameGrupo = "Grupo 1";break;
                case App::$GRUPO_2: $grupo = App::$GRUPO_2; $nameGrupo = "Grupo 2";break;
                case App::$GRUPO_3: $grupo = App::$GRUPO_3; $nameGrupo = "Grupo 3";break;
                default: $grupo = App::$GRUPO_NINGUNO;break;
            }

            $entity = Trabajador::where('personalCargo_id', App::$PERSONALCARGO_MACRO2)->where('id', '!=', $request->get('id'))
                ->where('grupo_id', $grupo)->where('deleted_at', NULL)->first();

            if($entity && $macro_id != App::$MACRO_NINGUNO && $grupo != App::$GRUPO_NINGUNO){
                $existPersonalCargo = true;  $messagePersonalCargo = "Ya existe un trabajador con cargo Supervisor Macro 2 del ".$nameGrupo." !!!";
            }

        }else if($request->get('personalCargo_id') == App::$PERSONALCARGO_MACRO3){
            $entity = null;
            switch ($request->get('grupo_id')){
                case App::$GRUPO_1: $grupo = App::$GRUPO_1; $nameGrupo = "Grupo 1";break;
                case App::$GRUPO_2: $grupo = App::$GRUPO_2; $nameGrupo = "Grupo 2";break;
                case App::$GRUPO_3: $grupo = App::$GRUPO_3; $nameGrupo = "Grupo 3";break;
                default: $grupo = App::$GRUPO_NINGUNO;break;
            }

            $entity = Trabajador::where('personalCargo_id', App::$PERSONALCARGO_MACRO3)->where('id', '!=', $request->get('id'))
                ->where('grupo_id', $grupo)->where('deleted_at', NULL)->first();

            if($entity && $macro_id != App::$MACRO_NINGUNO && $grupo != App::$GRUPO_NINGUNO){
                $existPersonalCargo = true;  $messagePersonalCargo = "Ya existe un trabajador con cargo Supervisor Macro 3 del ".$nameGrupo." !!!";
            }

        }else if($request->get('personalCargo_id') == App::$PERSONALCARGO_RSF){
            $entity = null;
            switch ($request->get('grupo_id')){
                case App::$GRUPO_1: $grupo = App::$GRUPO_1; $nameGrupo = "Grupo 1";break;
                case App::$GRUPO_2: $grupo = App::$GRUPO_2; $nameGrupo = "Grupo 2";break;
                case App::$GRUPO_3: $grupo = App::$GRUPO_3; $nameGrupo = "Grupo 3";break;
                default: $grupo = App::$GRUPO_NINGUNO;break;
            }

            $entity = Trabajador::where('personalCargo_id', App::$PERSONALCARGO_RSF)->where('id', '!=', $request->get('id'))
                ->where('grupo_id', $grupo)->where('deleted_at', NULL)->first();

            if($entity && $macro_id != App::$MACRO_NINGUNO && $grupo != App::$GRUPO_NINGUNO){
                $existPersonalCargo = true;  $messagePersonalCargo = "Ya existe un trabajador con cargo RSF del ".$nameGrupo." !!!";
            }
        }

        if($existPersonalCargo)
            return response()->json(['Success' => $status, 'Message' => $messagePersonalCargo]);


        if($request->file('image') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image')->move(public_path('uploads/trabajadores/'), $file_name);

            $image_id = $image->id;

        }else{
            $image_id = 1;
        }

        $exist = Trabajador::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Trabajador::find($request->get('id'));
            if($image_id == null || $image_id == 1)
                $image_id = $entity->image_id;

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Trabajador();
        }

        $macro_id = $request->get('macro_id');
        /*switch (intval($request->get('personalCargo_id'))){
            case App::$PERSONALCARGO_JEFEOPERACION : $macro_id = App::$MACRO_TODOS; break;
            case App::$PERSONALCARGO_MACRO1 : $macro_id = App::$MACRO_1; break;
            case App::$PERSONALCARGO_MACRO2 : $macro_id = App::$MACRO_2; break;
            case App::$PERSONALCARGO_MACRO3 : $macro_id = App::$MACRO_3; break;
            case App::$PERSONALCARGO_RSF : $macro_id = App::$MACRO_RSF; break;
        }*/

        $entity->image_id = $image_id;
        $entity->name = strtoupper($request->get('name'));
        $entity->dni = $request->get('dni');
        $entity->direccion = strtoupper($request->get('direccion'));
        $entity->referencia = strtoupper($request->get('referencia'));
        $entity->telefono = strtoupper($request->get('telefono'));
        $entity->persona1 = strtoupper($request->get('persona1'));
        $entity->telefono1 = strtoupper($request->get('telefono1'));
        $entity->persona2 = strtoupper($request->get('persona2'));
        $entity->telefono2 = strtoupper($request->get('telefono2'));
        $entity->personalCargo_id = $request->get('personalCargo_id');
        $entity->turno_id = $request->get('turno_id');
        $entity->turno_suplente_id = $request->get('turno_suplente_id');
        $entity->macro_id =  $macro_id;
        $entity->grupo_id = $request->get('grupo_id');
        $entity->contrato_id = $request->get('contrato_id');

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Trabajador::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
