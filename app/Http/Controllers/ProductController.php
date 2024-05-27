<?php

namespace NavegapComprame\Http\Controllers;

use NavegapComprame\Categorie;
use NavegapComprame\Product;
use NavegapComprame\ProductCategorie;
use NavegapComprame\ProductFlavor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    private $categorie, $product, $productCategorie, $productFlavor;
    public function __construct(Categorie $categorie, Product $product, ProductCategorie $productCategorie)
    {
        $this->categorie = $categorie;
        $this->product = $product;
        $this->productCategorie = $productCategorie;
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.templates.product.views.getdata');
    }

    public function product_list()
    {
        $p = $this->product->orderBy('created_at', 'desc')->get();
        return response()->json( $p );

    }

    public function create()
    {
        return view('auth.templates.product.views.register');

    }

    public function store()
    {
        $catgs = Input::get('categorie');
        $flavs = Input::get('flavor');

        $p =  $this->product->create(array(
            'name' => Input::get('name'),
            'price' => Input::get('price'),
            'floor' => Input::get('floor'),
            'proportion' => Input::get('proportion'),
            'shape' => Input::get('shape'),
            'information' => Input::get('information')
        ));

        if($p){
            for($i=0; $i < count($catgs); $i++ ){
                $this->productCategorie->create(array(
                    'product_id' => $p->id,
                    'categorie_id' => $catgs[$i]
                ));
            }

            for($j=0; $j < count($flavs); $j++ ){
                $this->productFlavor->create(array(
                    'product_id' => $p->id,
                    'flavor_id' => $flavs[$j]
                ));
            }
        }

        return response()->json([ 'success' =>  true, 'message' => 'Datos guardados con éxito',
            'product' => $p ]);
    }

    public function uploadfile_create(Request $request)
    {
        if($request->file('image_file')){

            $productid = $request->get('product_id');
            $random = Str::upper(str_random(4));
            $namefile = uniqid($random . "_") . '.' . $request->file('image_file')->getClientOriginalName();

             $p = $this->product->find($productid);
             $p->image = $namefile;
             $p->save();

            $request->file('image_file')->move('auth/upload/products/', $namefile );
        }

        return response()->json([ 'success' => true, 'message' => 'Archivo subido' ]);

    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->product->destroy($id);
        return response()->json([ 'success' =>  true, 'message' => 'Registro eliminado']);
    }
}
