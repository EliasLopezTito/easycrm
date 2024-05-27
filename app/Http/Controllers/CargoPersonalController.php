<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\PersonalCargo;

class CargoPersonalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.personalCargo.index');
    }

    public function list_all()
    {
        return response()->json(['data' => PersonalCargo::all()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = PersonalCargo::find($id);

        return view('auth.personalCargo._Maintenance', ['PersonalCargo' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = PersonalCargo::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  PersonalCargo::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new PersonalCargo();
        }

        $entity->name = strtoupper($request->get('name'));

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = PersonalCargo::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
