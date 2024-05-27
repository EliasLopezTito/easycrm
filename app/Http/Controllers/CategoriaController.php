<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Categoria;
use Incidencias\ClasificacionIncidencia;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.categoria.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Categoria::all()]);
    }

    public function list_clasification($id)
    {
        $clasificionIncidencias = ClasificacionIncidencia::where('categoria_id', $id)->get();
        return response()->json($clasificionIncidencias);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = categoria::find($id);

        return view('auth.categoria._Maintenance', ['Categoria' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = Categoria::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Categoria::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Categoria();
        }

        $entity->name = strtoupper($request->get('name'));


        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Categoria::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
