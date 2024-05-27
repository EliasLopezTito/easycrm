<?php

namespace NavegapComprame\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use NavegapComprame\App;
use NavegapComprame\Categorie;
use NavegapComprame\Http\Controllers\Controller;
use NavegapComprame\Image;
use NavegapComprame\Product;
use NavegapComprame\ProductCategorie;
use NavegapComprame\User;
use NavegapComprame\UserCategorie;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.product.index');
    }

    public function list_all()
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            return response()->json(['data' => Product::with('user')->get() ]);

        return response()->json(['data' => Product::with('user')->where('user_id', Auth::user()->id)->get() ]);
    }

    public function view($id)
    {
        $entity = null;

        $users = User::all();

        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            $categories = Categorie::all();
        else
            $categories = UserCategorie::where('user_id', Auth::user()->id)->whereHas('categories', function ($q){
                $q->whereNull('deleted_at');
            })->with('categories')->get();

        $productCategories = ProductCategorie::where('product_id', $id)->pluck('categorie_id')->toArray();

        if($id != 0) {
            $entity = Product::where('id', $id)->with('image')->first();
        }

    if( (Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR) ||
            (Auth::user()->perfil_id == App::$PERFIL_EMPRESA && ( ($entity!= null && $entity->user_id == Auth::user()->id) || $entity == null) )) {
        return view('auth.product._Maintenance', ['Users' => $users, 'Product' => $entity, 'Categories' => $categories, 'ProductCategories' => $productCategories]);
    }else{
        return redirect(route('auth.product'));
    }

    }

    public function store(Request $request)
    {
        $status = false; $image_id = null;
        $random = Str::upper(str_random(4));
        $exist = Product::where('name', $request->get('name'))->first();

        if($request->file('image') != null){

            $file_name = uniqid($random . "_") . '.' . $request->file('image')->getClientOriginalExtension();

            $image = new Image();
            $image->name = $file_name;

            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen !!!']);

            $request->file('image')->move('uploads/products/', $file_name);

            $image_id = $image->id;

        }else{
            $image_id = 1;
        }


        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity =  Product::find($request->get('id'));
            if($image_id == null || $image_id == 1)
                $image_id = $entity->image_id;

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El nombre '.$request->get('name'). ' ya está registrada.']);

            $entity = new Product();
        }

        $entity->user_id = Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR ? $request->get('user_id') : Auth::user()->id;
        $entity->image_id = $image_id;
        $entity->name = $request->get('name');
        $entity->description = $request->get('description');
        $entity->price = $request->get('price');
        $entity->information = $request->get('information');
        $entity->patrocinado = $request->get('patrocinado');
        $entity->discount = $request->get('discount');


        if($entity->discount){
            $entity->porcentage_discount = $request->get('porcentage_discount');
            $entity->price_venta = $request->get('price_venta');
        }else{
            $entity->porcentage_discount = 0;
            $entity->price_venta = 0;
        }

        if($entity->save()){

            ProductCategorie::where('product_id', $entity->id)->delete();

            foreach (explode(',', $request->get('category_id')) as $s){
                $productCategorie = new ProductCategorie();
                $productCategorie->product_id = $entity->id;
                $productCategorie->categorie_id =  $s;
                $productCategorie->save();
            }

            $status = true;
        }

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = Product::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
