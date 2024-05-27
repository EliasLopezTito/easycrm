<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Camioneta;

class CamionetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.camioneta.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Camioneta::all()]);
    }

    public function placaporcamioneta($id)
    {
        $placa = Camioneta::find($id)->placa;
        return response()->json($placa);
    }

    public function camionetaPorCodigo($id)
    {
        $camioneta = Camioneta::find($id);
        return response()->json($camioneta);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = Camioneta::find($id);

        return view('auth.camioneta._Maintenance', ['Camioneta' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = Camioneta::where('placa', $request->get('placa'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Camioneta::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Camioneta();
        }

        $entity->marca = strtoupper($request->get('marca'));
        $entity->placa = strtoupper($request->get('placa'));
        $entity->numeroCamioneta = strtoupper($request->get('numeroCamioneta'));
        $entity->anio = $request->get('anio');
        $entity->name = strtoupper($request->get('name'));
        $entity->vinculado = $request->get('vinculado');

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Camioneta::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
