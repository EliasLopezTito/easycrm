<?php

namespace NavegapComprame\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use NavegapComprame\App;
use NavegapComprame\Categorie;
use NavegapComprame\City;
use NavegapComprame\Departament;
use NavegapComprame\Http\Controllers\Controller;
use NavegapComprame\Image;
use NavegapComprame\TypePay;
use NavegapComprame\TypeSend;
use NavegapComprame\User;
use NavegapComprame\UserCategorie;
use NavegapComprame\UserTypePay;
use NavegapComprame\UserTypeSend;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            return view('auth.user.index');
        else
            return redirect(route('auth.home'));
    }

    public function list_all()
    {
        return response()->json(['data' => User::where('id', '!=', 1)->with('perfil')->with('city')->get()]);
    }

    public function view($id)
    {
        $entity = null;
        $departaments = Departament::all();
        $Categories = Categorie::all();
        $TypesPay = TypePay::all();
        $TypesSend = TypeSend::all();

        $userCategorie = UserCategorie::where('user_id', $id)->pluck('categorie_id')->toArray();
        $userEntrega = UserTypeSend::where('user_id', $id)->pluck('type_send_id')->toArray();
        $userPago = UserTypePay::where('user_id', $id)->pluck('type_pay_id')->toArray();

        if($id != 0) {
            $entity = User::where('id', $id)->with('perfil')
                ->with('image_banner')->with('image_logo')->with('city')->first();
        }

        $cities = $id != 0 ? City::where('departament_id',$entity->departament_id)->get() :  City::all();

        if( (Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR) ||
            (Auth::user()->perfil_id == App::$PERFIL_EMPRESA && $entity != null && $entity->id == Auth::user()->id )){
            return view('auth.user._Maintenance', ['User' => $entity, 'Cities' => $cities, 'Categories' => $Categories, 'UserCategorie' => $userCategorie,
                'UserEntrega' => $userEntrega, 'UserPago' => $userPago, 'TypesSend' => $TypesSend, 'TypesPay' => $TypesPay, 'Departaments' => $departaments]);
        }else{
            return redirect(route('auth.home'));
        }
    }

    public function store(Request $request)
    {
        $status = false; $image_banner_id = null; $image_logo_id = null;
        $random = Str::upper(str_random(4));
        $exist = User::where('email', $request->get('email'))->first();

        if($request->file('image_logo') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image_logo')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image_logo')->move('uploads/users/', $file_name);

            $image_logo_id = $image->id;

        }else{
            $image_logo_id = 1;
        }

        if($request->file('image_banner') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image_banner')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image_banner')->move('uploads/users/', $file_name);

            $image_banner_id = $image->id;

        }else{
            $image_banner_id = 1;
        }

        if($request->get('id') != 0){
            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El E-Mail '.$request->get('email'). ' ya tiene un cargo asignado.']);

            $entity =  User::find($request->get('id'));

            if($image_banner_id == null || $image_banner_id == 1) $image_banner_id = $entity->image_banner_id;
            if($image_logo_id == null || $image_logo_id == 1) $image_logo_id = $entity->image_logo_id;

            if($request->get('password'))
                $entity->password =  Hash::make($request->get('password'));

        }else{
            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El E-Mail '.$request->get('email'). ' ya está registrada.']);

            $entity = new User();
            $entity->password =  Hash::make($request->get('password'));
        }

        $entity->perfil_id = ($request->get('id') != 0 &&  $entity->perfil_id == App::$PERFIL_ADMINISTRADOR) ?  App::$PERFIL_ADMINISTRADOR : App::$PERFIL_EMPRESA;
        $entity->image_banner_id = $image_banner_id;
        $entity->image_logo_id = $image_logo_id;
        $entity->departament_id = $request->get('departament_id');
        $entity->city_id = $request->get('city_id');
        $entity->name = $request->get('name');
        $entity->ruc = $request->get('ruc');
        $entity->phone = str_replace(' ', '', trim($request->get('phone')));
        $entity->address = $request->get('address');
        $entity->city_id = $request->get('city_id');
        $entity->description = $request->get('description');
        $entity->email = $request->get('email');
        $entity->statu = $request->get('statu');


        if($entity->save()){

            UserCategorie::where('user_id', $entity->id)->delete();

            foreach (explode(',', $request->get('category_id')) as $s){
                $userCategorie = new UserCategorie();
                $userCategorie->user_id = $entity->id;
                $userCategorie->categorie_id =  $s;
                $userCategorie->save();
            }

            UserTypePay::where('user_id', $entity->id)->delete();

            foreach (explode(',', $request->get('typePay_id')) as $s){
                $userPago = new UserTypePay();
                $userPago->user_id = $entity->id;
                $userPago->type_pay_id =  $s;
                $userPago->save();
            }

            UserTypeSend::where('user_id', $entity->id)->delete();

            foreach (explode(',', $request->get('typeSend_id')) as $s){
                $userTypeSend = new UserTypeSend();
                $userTypeSend->user_id = $entity->id;
                $userTypeSend->type_send_id =  $s;
                $userTypeSend->save();
            }

            $entity->typeSend_id = $request->get('typeSend_id');
            $entity->typePay_id = $request->get('typePay_id');

            $status = true;
        }

        return response()->json(['Success' => $status , 'Return' => ($request->get('id') != 0 ? false : true), 'Url' => '/auth/user/view/0']);
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
