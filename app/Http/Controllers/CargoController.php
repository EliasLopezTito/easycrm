<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Cargo;

class CargoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.cargo.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Cargo::orderBy('order', 'asc')->get()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = Cargo::find($id);

        return view('auth.cargo._Maintenance', ['Cargo' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false; $valueOrder = 1;
        $exist = Cargo::where('name', $request->get('name'))->where('deleted_at', NULL)->first();
        $order = Cargo::orderby('order', 'desc')->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Cargo::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Cargo();
            if($order)
                $valueOrder =  $order->order + 1;

            $entity->order = $valueOrder;
        }

        $entity->name = strtoupper($request->get('name'));
        $entity->visible = $request->get('visible');

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function partialViewRefreshOrder()
    {
        return view('auth.cargo._RefreshOrder', ['cargos' => Cargo::orderBy('order', 'asc')->get()]);
    }

    public function saveRefreshOrder(Request $request){

        $iG = 0;
        if($request->get('jsonString')){
            $cargos = json_decode($request->get('jsonString'),true);
            foreach ($cargos as $c){
                $iG++;
                $cargo = Cargo::find($c['id']);
                $cargo->order = $iG;
                $cargo->save();
            }
        }

        $status = true;

        return response()->json(['Success' => $status, 'Message' => 'El orden de los cargos se han actualizado.']);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Cargo::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
