<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Incidencias\Categoria;
use Incidencias\ClasificacionIncidencia;
use Incidencias\Image;
use Incidencias\ModalidadIncidencia;

class ModalidadIncidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.modalidadIncidencia.index');
    }

    public function list_all()
    {
        return response()->json(['data' => ModalidadIncidencia::with('clasificacionIncidencias')->get()]);
    }

    public function partialView($id)
    {
        $entity = null;
        if($id != 0) $entity = ModalidadIncidencia::with('images')->where('id', $id)->first();

        $clasificacionIncidencias = ClasificacionIncidencia::all();

        return view('auth.modalidadIncidencia._Maintenance', ['ModalidadIncidencia' => $entity])
            ->with('clasificacionIncidencias', $clasificacionIncidencias);
    }

    public function filtroByClasification($id)
    {
        return response()->json([ 'data' => ModalidadIncidencia::where('clasificacionIncidencia_id', $id)->get() ]);
    }

    public function store(Request $request)
    {
        $status = false;
        $exist = ModalidadIncidencia::where('name', $request->get('name'))->where('deleted_at', NULL)
            ->with('clasificacionIncidencias')->first();
        $random = Str::upper(str_random(4));

        if($request->file('image') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image')->move(public_path('uploads/modalidadIncidencias/'), $file_name);

            $image_id = $image->id;

        }else{
            $image_id = 1;
        }

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  ModalidadIncidencia::find($request->get('id'));

            if($image_id == null || $image_id == 1)
                $image_id = $entity->image_id;

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new ModalidadIncidencia();
        }

        $entity->clasificacionIncidencia_id = $request->get('clasificacionIncidencia_id');
        $entity->name = strtoupper($request->get('name'));
        $entity->image_id = $image_id;

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = ModalidadIncidencia::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
