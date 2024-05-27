<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Arma;

class ArmaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.arma.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Arma::all()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = Arma::find($id);

        return view('auth.arma._Maintenance', ['Arma' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = Arma::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Arma::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Arma();
        }

        $entity->name = strtoupper($request->get('name'));


        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Arma::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
