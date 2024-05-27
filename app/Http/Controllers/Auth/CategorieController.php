<?php

namespace NavegapComprame\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use NavegapComprame\App;
use NavegapComprame\Categorie;
use Illuminate\Http\Request;
use NavegapComprame\Http\Controllers\Controller;
use NavegapComprame\Image;

class CategorieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            return view('auth.categorie.index');
        else
            return redirect(route('auth.home'));
    }

    public function list_all()
    {
        return response()->json(['data' => Categorie::with('image')->get()]);
    }

    public function view($id)
    {
        $entity = null;
        if($id != 0) $entity = Categorie::with('image')->where('id', $id)->first();

        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            return view('auth.categorie._Maintenance', ['Categorie' => $entity]);
        else
            return redirect(route('auth.home'));
    }

    public function store(Request $request)
    {
        $status = false;$image_id = null;
        $random = Str::upper(str_random(4));
        $exist = Categorie::where('name', $request->get('name'))->where('deleted_at', NULL)->first();

        if($request->file('image') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image')->move('uploads/categories/', $file_name);

            $image_id = $image->id;

        }else{
            $image_id = 1;
        }

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Categorie::find($request->get('id'));
            if($image_id == null || $image_id == 1)
                $image_id = $entity->image_id;

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Categorie();
        }

        $entity->image_id = $image_id;
        $entity->name = $request->get('name');

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Categorie::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
