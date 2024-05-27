<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\LugarIncidencia;

class LugarIncidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.lugarIncidencia.index');
    }

    public function list_all()
    {
        return response()->json(['data' => LugarIncidencia::all()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = LugarIncidencia::find($id);

        return view('auth.lugarIncidencia._Maintenance', ['LugarIncidencia' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = LugarIncidencia::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  LugarIncidencia::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new LugarIncidencia();
        }

        $entity->name = strtoupper($request->get('name'));


        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = LugarIncidencia::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
