<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Incidencias\Servicio;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.servicio.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Servicio::all()]);
    }

    public function listadoFiltro(Request $request){

        $data = array();
        $servicios = Servicio::where('name', 'like', '%' .$request->get('name') . '%')->orderby('name', 'asc')
            ->whereNull('deleted_at')->get();
        foreach ($servicios as $t){
            $array  = array('id' => $t->id, 'text' => $t->name);
            array_push($data, $array);
        }

        return response()->json(['data' => $data]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = Servicio::find($id);

        return view('auth.servicio._Maintenance', ['Servicio' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = Servicio::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Servicio::find($request->get('id'));

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Servicio();
        }

        $entity->name = strtoupper($request->get('name'));


        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Servicio::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
