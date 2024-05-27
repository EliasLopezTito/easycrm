<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'departament_id', 'city_id', 'user_id', 'image_id', 'name', 'price', 'description',  'information', 'patrocinado', 'discount', 'porcentage_discount', 'price_venta'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    function image() {
        return $this->belongsTo('\NavegapComprame\Image', 'image_id');
    }

    public function productCategorie()
    {
        return $this->hasMany('\NavegapComprame\ProductCategorie', 'product_id');
    }

    public function clientFavorite()
    {
        return $this->hasMany('\NavegapComprame\ClientFavorite', 'product_id');
    }

    public function clientComments()
    {
        return $this->hasMany('\NavegapComprame\ProductComment', 'product_id');
    }

    static function product_favorites()
    {
        $items = Product::with('image')->with('user')->with('clientFavorite')
            ->whereHas('user', function ($q) {
                $q->where('city_id', Auth::guard('client')->check() ? Auth::guard('client')->user()->city_id : intval(Session::get('city')));
            })->whereNull('deleted_at')->where('discount', false)->get();

        $products = [];

        if ($items != null && count($items) > 0) {
            $int = 0;
            foreach ($items as $product) {
                if ($product->user->deleted_at == null && $product->user->statu) {
                    $products[$int] = [];
                    $products[$int]['id'] = $product->id;
                    $products[$int]['name'] = $product->name;
                    $products[$int]['image'] = $product->image->name;
                    $products[$int]['user'] = $product->user->name;
                    $products[$int]['price'] = $product->price;
                    $products[$int]['discount'] = $product->discount;
                    $products[$int]['price_venta'] = $product->price_venta;
                    $products[$int]['clientFavorite'] = count($product->clientFavorite);
                    $products[$int]['clientComments'] = count($product->clientComments);
                    $int++;
                }
            }

            if($products != null && count($products) > 0){
                usort($products, function ($a, $b) {
                    return -1 * strcmp($a['clientFavorite'], $b['clientFavorite']);
                });
            }

            return $products;
        }
    }


    static function product_favorites_top($city)
    {
       $items =  Product::with('image')->with('user')->with('clientFavorite')
            ->whereHas('user', function ($q){
                $q->where('city_id', Auth::guard('client')->check() ? Auth::guard('client')->user()->city_id : intval(Session::get('city')));
            })->whereNull('deleted_at')->where('discount', false)->get();

        if($items != null && count($items) > 0) {
            $products = [];
            $int = 0;
            foreach ($items as $product) {

                if ($product->user->deleted_at == null && $product->user->statu) {
                    $products[$int] = [];
                    $products[$int]['id'] = $product->id;
                    $products[$int]['name'] = $product->name;
                    $products[$int]['image'] = $product->image->name;
                    $products[$int]['user'] = $product->user->name;
                    $products[$int]['price'] = $product->price;
                    $products[$int]['discount'] = $product->discount;
                    $products[$int]['price_venta'] = $product->price_venta;
                    $products[$int]['clientFavorite'] = count($product->clientFavorite);
                    $products[$int]['clientComments'] = count($product->clientComments);
                    $int++;
                }
            }

        usort($products, function($a, $b) {
            return -1 * strcmp($a['clientFavorite'], $b['clientFavorite']);
        });

            /*if(count($items) == 1)
                return [$products[0]];
            elseif (count($items) == 2)
                return [$products[0], $products[1]];
            elseif (count($items) == 3)
                return [$products[0], $products[1], $products[2]];
            elseif (count($items) >= 4)
                return [$products[0], $products[1], $products[2], $products[3]];*/

            return $products;

        }

        return [];
    }

    static function product_ofertas()
    {
        $products =  Product::with('image')->with('user')->with('clientFavorite')
            ->with('clientComments')->where('discount', true)->whereHas('user', function ($q){
                $q->where('city_id', Auth::guard('client')->check() ? Auth::guard('client')->user()->city_id : intval(Session::get('city')));
            })->whereNull('deleted_at')->orderByDesc('porcentage_discount')->get();

        return $products;
    }
    static function product_ofertas_top()
    {
        $products =  Product::with('image')->with('user')->with('clientFavorite')
            ->with('clientComments')->where('discount', true)->whereHas('user', function ($q){
                $q->where('city_id', Auth::guard('client')->check() ? Auth::guard('client')->user()->city_id : intval(Session::get('city')));
            })->whereNull('deleted_at')->orderByDesc('porcentage_discount')->take(4)->get();

        return $products;
    }

    /*static function product_recomends()
    {
      $items =  DB::table('client_favorites')
          ->join('products', 'client_favorites.product_id', '=', 'products.id')
          ->join('images', 'products.image_id', '=', 'images.id')
          ->join('users', 'products.user_id', '=', 'users.id')
          ->select('products.*', 'client_favorites.*')
          ->whereNull('products.deleted_at')
          ->whereNull('users.deleted_at')
          ->where('users.statu', true)
          ->get();

          $products = []; $int = 0; $int2 = 0;
            foreach ($cart->items as $c){
                $store_price_total = 0;
                if(!in_array($c['item']->user_id, $StoresRepeats)){
                    $stores[$int] = [];
                    $stores[$int]['store_id'] = $c['item']->user_id;
                    $stores[$int]['store_name'] = $c['item']->user->name;

                    foreach ($cart->items as $p){
                        if($c['item']->user_id == $p['item']->user_id ){
                            $stores[$int]['Products'][$int2] = [];
                            $stores[$int]['Products'][$int2]['product'] = $p;
                            $store_price_total += $p['price'];
                            $int2++;
                        }
                    }
                    $stores[$int]['store_price_total'] = number_format($store_price_total, 2);
                    array_push($StoresRepeats, $c['item']->user_id);
                    $int++;
                }
            }
    }*/

    static function product_find($id){

        $fp = DB::table('products')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'product_categories.categorie_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as categorie')
            ->where('products.id', '=', $id)
            ->get();

        $arreglo = [];
        $int=0;

        foreach($fp as $f){

            $arreglo[$int] = [];
            $arreglo[$int]['id'] = $f->id;
            $arreglo[$int]['name'] = $f->name;
            $arreglo[$int]['price'] = $f->price;
            $arreglo[$int]['image'] = $f->image;
            $arreglo[$int]['information'] = $f->information;
            $arreglo[$int]['categorie'] = [];
            $arreglo[$int]['flavor'] = [];

            $int_2=0 ; $catgArray = [];
            foreach($fp as $fc){
                if(!in_array($fc->categorie, $catgArray)){
                $arreglo[$int]['categorie'][$int_2] = [];
                $arreglo[$int]['categorie'][$int_2]['name'] = $fc->categorie;
                array_push($catgArray, $fc->categorie);
                $int_2++;
                }
            }

            $int_3=0; $flavArray = [];
            foreach($fp as $ff){
                if(!in_array($ff->flavor, $flavArray)) {
                $arreglo[$int]['flavor'][$int_3] = [];
                $arreglo[$int]['flavor'][$int_3]['name'] = $ff->flavor;
                array_push($flavArray, $ff->flavor);
                $int_3++;
                }
            }

            break;

        }

        return $arreglo;

    }
}
