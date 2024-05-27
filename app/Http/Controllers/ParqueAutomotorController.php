<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\ParqueAutomotor;

class ParqueAutomotorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.parqueAutomotor.index');
    }

    public function list_all()
    {
        return response()->json(['data' => ParqueAutomotor::all()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = ParqueAutomotor::find($id);

        return view('auth.parqueAutomotor._Maintenance', ['ParqueAutomotor' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = ParqueAutomotor::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  ParqueAutomotor::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new ParqueAutomotor();
        }

        $entity->name = strtoupper($request->get('name'));


        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = ParqueAutomotor::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
