<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Vehiculo;

class VehiculoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.vehiculo.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Vehiculo::all()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = vehiculo::find($id);

        return view('auth.vehiculo._Maintenance', ['Vehiculo' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = vehiculo::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  vehiculo::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new vehiculo();
        }

        $entity->name = strtoupper($request->get('name'));


        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = vehiculo::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
