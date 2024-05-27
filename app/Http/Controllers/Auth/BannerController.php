<?php

namespace NavegapComprame\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use NavegapComprame\App;
use NavegapComprame\Banner;
use NavegapComprame\City;
use NavegapComprame\Departament;
use NavegapComprame\Http\Controllers\Controller;
use NavegapComprame\Image;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            return view('auth.banner.index');
        else
            return redirect(route('auth.home'));
    }

    public function list_all()
    {
        return response()->json(['data' => Banner::all()]);
    }

    public function view($id)
    {
        $entity = null;

        if($id != 0) {
            $entity = Banner::where('id', $id)->with('image')->first();
        }

        $departaments = Departament::all();
        $cities = $id != 0 ? City::where('departament_id',$entity->departament_id)->get() :  City::all();

        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            return view('auth.banner._Maintenance', ['Banner' => $entity, 'Departaments' => $departaments, 'Cities' => $cities]);
        else
            return redirect(route('auth.home'));
    }

    public function store(Request $request)
    {
        $status = false; $image_id = null;
        $random = Str::upper(str_random(4));
        $exist = Banner::where('name', $request->get('name'))->first();

        if($request->file('image') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image')->move('uploads/banners/', $file_name);

            $image_id = $image->id;

        }else{
            $image_id = 1;
        }
        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Banner::find($request->get('id'));
            if($image_id == null || $image_id == 1)
                $image_id = $entity->image_id;

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Banner();
        }

        $entity->image_id = $image_id;
        $entity->name = $request->get('name');
        $entity->departament_id = $request->get('departament_id');
        $entity->city_id = $request->get('city_id');
        $entity->href = $request->get('href');
        $entity->statu = $request->get('statu');

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Banner::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
