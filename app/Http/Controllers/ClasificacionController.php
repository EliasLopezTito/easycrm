<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Categoria;
use Incidencias\ClasificacionIncidencia;
use Incidencias\ModalidadIncidencia;

class ClasificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.clasificacionIncidencia.index');
    }

    public function list_all()
    {
        return response()->json(['data' => ClasificacionIncidencia::with('categorias')->get()]);
    }

    public function listadoFiltro(Request $request){

        $data = array();
        $clasificaiones = ClasificacionIncidencia::where('categoria_id',1)->where('name', 'like', '%' .$request->get('name') . '%')->orderby('name', 'asc')
            ->whereNull('deleted_at')->get();
        foreach ($clasificaiones as $t){
            $array  = array('id' => $t->id, 'text' => $t->name);
            array_push($data, $array);
        }

        return response()->json(['data' => $data]);
    }

    public function list_modalidad($id)
    {
        $modalidadIncidencia = ModalidadIncidencia::where('clasificacionIncidencia_id', $id)->get();
        return response()->json($modalidadIncidencia);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = ClasificacionIncidencia::find($id);

        $categorias = Categoria::all();

        return view('auth.clasificacionIncidencia._Maintenance', ['ClasificacionIncidencia' => $entity])
            ->with('categorias', $categorias);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = ClasificacionIncidencia::where('name', $request->get('name'))->where('deleted_at', NULL)
            ->with('categorias')->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  ClasificacionIncidencia::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new ClasificacionIncidencia();
        }

        $entity->categoria_id = $request->get('categoria_id');
        $entity->name = strtoupper($request->get('name'));

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = ClasificacionIncidencia::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
