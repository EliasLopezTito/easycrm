<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Modalidad;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ModalidadController extends Controller
{
    public function index()
    {
        return view('auth.modalidad.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Modalidad::orderby('id', 'desc')->get()]);
    }

    public function partialView($id)
    {
        $entity = null;

        if($id != 0) $entity = Modalidad::find($id);

        return view('auth.modalidad._Mantenimiento', ['Modalidad' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;

        if($request->id != 0)
            $Modalidad = Modalidad::find($request->id);
        else
            $Modalidad = new Modalidad();

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:modalidads,name,'.($request->id != 0 ? $request->id : "NULL").',id,deleted_at,NULL'
        ]);

        if (!$validator->fails()){
            $Modalidad->name = strtoupper(trim($request->name));
            if($Modalidad->save()) $status = true;
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = Modalidad::find($request->get('id'));

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
