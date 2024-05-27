<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Contrato;

class ContratoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.contrato.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Contrato::all()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = Contrato::find($id);

        return view('auth.contrato._Maintenance', ['Contrato' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = Contrato::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Contrato::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Contrato();
        }

        $entity->name = strtoupper($request->get('name'));


        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Contrato::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
