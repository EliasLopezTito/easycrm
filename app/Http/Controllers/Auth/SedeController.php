<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Sede;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SedeController extends Controller
{
    public function index()
    {
        return view('auth.sede.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Sede::orderby('id', 'desc')->get()]);
    }

    public function partialView($id)
    {
        $entity = null;

        if($id != 0) $entity = Sede::find($id);

        return view('auth.sede._Mantenimiento', ['Sede' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;

        if($request->id != 0) {
            $Sede = Sede::find($request->id);
        }else {
            $Sede = new Sede();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sedes,name,'.($request->id != 0 ? $request->id : "NULL").',id,deleted_at,NULL'
        ]);

        if (!$validator->fails()){
            $Sede->name = strtoupper(trim($request->name));
            if($Sede->save()) $status = true;
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = Sede::find($request->get('id'));

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
