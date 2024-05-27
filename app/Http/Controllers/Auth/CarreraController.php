<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Carrera;
use easyCRM\Modalidad;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CarreraController extends Controller
{
    public function index()
    {
        return view('auth.carrera.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Carrera::with('modalidades')->orderby('id', 'desc')->get()]);
    }

    public function filtroCurso($id)
    {
        return response()->json(Carrera::where('modalidad_id', $id)->orderBy('name', 'asc')->get());
    }

    public function partialView($id)
    {
        $entity = null;

        if($id != 0) $entity = Carrera::find($id);

        $Modalidades = Modalidad::all();

        return view('auth.carrera._Mantenimiento', ['Modalidades' => $Modalidades, 'Carrera' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;

        if($request->id != 0)
            $Carrera = Carrera::find($request->id);
        else
            $Carrera = new Carrera();

        $validator = Validator::make($request->all(), [
            'modalidad_id' => 'required',
            'name' => 'required|unique:carreras,name,'.($request->id != 0 ? $request->id : "NULL").',id,deleted_at,NULL'
        ]);

        if (!$validator->fails()){
            $Carrera->modalidad_id = $request->modalidad_id;
            $Carrera->name = strtoupper(trim($request->name));
            if($Carrera->save()) $status = true;
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = Carrera::find($request->get('id'));

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
