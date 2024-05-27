<?php

namespace Incidencias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Incidencias\Image;
use Incidencias\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.user.index');
    }

    public function list_all()
    {
        return response()->json(['data' => User::where('id', '!=', 1)->with('perfils')
            ->with('macros')->with('grupos')->get()]);
    }

    public function partialView($id)
    {
        $entity = null;

        if($id != 0) {
            $entity = User::where('id', $id)->with('images')->with('perfils')
                ->with('macros')->with('grupos')->first();
        }
        return view('auth.user._Maintenance', ['user' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false; $image_id = null;
        $random = Str::upper(str_random(4));
        $exist = User::where('name', $request->get('name'))->first();

        if($request->get('user_alternative') && $request->get('user_name_alternative') == "")
            return response()->json(['Success' => $status, 'Message' => 'Seleccione un Usuario Suplente !!!']);
        else
            if($request->get('name') == $request->get('user_name_alternative'))
                return response()->json(['Success' => $status, 'Message' => 'El Usuario Suplente no puede ser el mismo que el Usuario Principal !!!']);

        if($request->file('image') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image')->move(public_path('uploads/users/'), $file_name);

            $image_id = $image->id;

        }else{
            $image_id = 1;
        }

        if($request->get('id') != 0){
            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El Usuario '.$request->get('name'). ' ya tiene un cargo asignado.']);

            $entity =  User::find($request->get('id'));
            if($image_id == null || $image_id == 1)
                $image_id = $entity->image_id;
            if($request->get('password'))
                $entity->password =  Hash::make($request->get('password'));

        }else{
            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El E-Mail '.$request->get('email'). ' ya está registrada.']);

            $entity = new User();
            $entity->password =  Hash::make($request->get('password'));
        }

        $entity->image_id = $image_id;
        $entity->name = strtoupper($request->get('name'));
        $entity->user_alternative = $request->get('user_alternative');
        $entity->user_name_alternative = strtoupper($request->get('user_name_alternative'));

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = User::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
