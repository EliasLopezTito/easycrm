<?php

namespace NavegapComprame\Http\Controllers;

use Illuminate\Support\Facades\Input;
use NavegapComprame\Cart;
use NavegapComprame\Categorie;
use NavegapComprame\Client;
use NavegapComprame\Delivery;
use NavegapComprame\District;
use NavegapComprame\Intico;
use NavegapComprame\Order;
use NavegapComprame\OrderDetail;
use NavegapComprame\ProductComment;
use NavegapComprame\StoreLocal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use NavegapComprame\TypePay;
use NavegapComprame\User;

class OrderController extends Controller
{
    private $categorie, $client, $storeLocal, $district, $delivery, $order;
    public function __construct(Categorie $categorie, Client $client, Order $order)
    {
        $this->client = $client;
        $this->categorie = $categorie;
        $this->order = $order;
        $this->middleware('auth:client');
    }

    public function checkout(){

        if(Session::has('cart')){

            $cartExist = Session::get('cart');
            $cart = new Cart($cartExist);

            if(count($cart->items) > 0){

            $data = array('categories' => $this->categorie
                        ->orderBy('name', 'asc')->get(),
            'stores' => $this->storeLocal->all(), 'districts' => $this->district->all(),
            'products' => $cart->items, 'totalPrice' => number_format($cart->totalPrice, 2)
            );

            return view('checkout')->with($data);

            }
        }

        return redirect()->to(route('cart'));
    }

    public function checkoutStoreGet()
    {
        if(Auth::guard('client')->check() && Session::has('cart') && Session::has('Params')) {

            $cartExist = Session::get('cart');
            $cart = new Cart($cartExist);

            $data = array(
                'typePay' => TypePay::find(Session::get('Params')),
                'totalPrice' => number_format($cart->totalPrice, 2),
                'totalQuantity' => $cart->totalQuantity
            );

            return view('checkoutStore', $data);
        }

        return redirect()->route('client.login');
    }

    public function checkoutStore(Request $request)
    {
        if(Auth::guard('client')->check() && Session::has('cart')) {

            $request->session()->put('Params', Input::get('type_pay_id'));

            $cartExist = Session::get('cart');
            $cart = new Cart($cartExist);

            $data = array(
                'typePay' => TypePay::find(Input::get('type_pay_id')),
                'totalPrice' => number_format($cart->totalPrice, 2),
                'totalQuantity' => $cart->totalQuantity
            );

            return view('checkoutStore', $data);
        }

        return redirect()->route('client.login');
    }


    public function checkoutConfirm(Request $request)
    {
        if(Auth::guard('client')->check() && Session::has('cart')) {

            $client = Client::find(Auth::guard('client')->user()->id);
            $client->phone = $request->get('phone');
            $client->save();

            $cartExist = Session::get('cart');

            $cart = new Cart($cartExist);

            $StoresRepeats = [];

            $stores = []; $int = 0; $int2 = 0;
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

            foreach ($stores as $s){

                $code = 'O00000';
                $address = "Acordada con el vendedor";

                $allOrder = DB::table('orders')
                    ->select('orders.*')
                    ->get();

                if (count($allOrder) > 0) {

                    $ucode = DB::table('orders')
                        ->select('orders.*')
                        ->orderBy('orders.id', 'desc')->first();

                    $code = $ucode->code;
                }

                $code++;

                $data = $this->order->create([
                    'code' => $code,
                    'user_id' => $s['store_id'],
                    'client_id' => Auth::guard('client')->user()->id,
                    'type_pay_id' => $request->get('typePay'),
                    'type_send_id' => 3,
                    'order_status_id' => 1,
                    'document' => 'DNI',
                    'total' => floatval(str_replace(",", "", $s['store_price_total']))
                ]);

                if ($data) {

                    $products = [];

                    foreach ($s['Products'] as $item) {
                        OrderDetail::create([
                            'order_id' => $data->id,
                            'product_id' => $item['product']['item']->id,
                            'code' => $data->code,
                            'quantity' => $item['product']['quantity'],
                            'subtotal' => $item['product']['price'],
                        ]);

                        array_push($products, $item['product']['item']->name);
                    }

                    $user = User::find($s['store_id']);
                    Intico::sendSMS($user->phone,
                        "[ComprandoPe] Hola ".$user->name.", te llegó una orden desde ComprandoPe. Más detalles en: http://www.comprando.pe/auth/order/".explode("O",$code)[1]);

                    $p = array(
                        'code' => $data->code, 'client' => Auth::guard('client')->user()->name,
                        'phone' => Auth::guard('client')->user()->phone,'negocio' => $user->name,'products' => implode (", ", $products),
                        'total' => number_format($data->total, 2),
                        'comision' => number_format($data->total*Auth::guard('client')->user()->city->comision, 2)
                    );

                    Mail::send('mails.order.confirmation', $p , function ($msj){
                        $msj->from('soporte@comprando.pe', 'ComprandoPe');
                        $msj->subject('Te llegó una orden desde ComprandoPe');
                        $msj->to(['notifications@comprando.pe']);
                    });

                }
            }

            Session::forget('cart');

            return view('checkoutComplete');

        }

        return redirect()->to(route('index'));
    }

    public function delivery_find($id){

        $d= $this->delivery->find($id);

        $cartExist = Session::get('cart');
        $cart = new Cart($cartExist);

        $data = array(
            'delivery' => $d,
            'totalPrice' => number_format($cart->totalPrice, 2) + $d->price,
        );

        return response()->json([ 'data' => $data ]);

    }

    public function order_total(){

        $cartExist = Session::get('cart');
        $cart = new Cart($cartExist);

        $data = array(
            'totalPrice' => number_format($cart->totalPrice, 2),
        );

        return response()->json([ 'data' => $data ]);

    }

    public function store_find($id){

        $data = $this->storeLocal->find($id);

        return response()->json([ 'data' => $data ]);

    }

    public function order_deposit(Request $request)
    {
        $store_local_id = 99;
        $delivery_id = 99;
        $code = 'P00000';
        $address = null;

        $allOrder = DB::table('orders')
            ->select('orders.*')
            ->get();

        if(count($allOrder) > 0){

            $ucode = DB::table('orders')
                ->select('orders.*')
                ->orderBy('orders.id','desc')->first();

            $code  = $ucode->code;
        }

        $code++;

        if($request['order'][0] == 'Delivery'){

            $find_delivery = $this->delivery->where('district_id', $request['order'][1])->first();
            $delivery_id = $find_delivery->id;

            $address = $request['order'][7];

        }else if($request['order'][0] == 'Tienda'){

            $store_local_id = $request['order'][2];

            $address = $request['order'][6];

        }

        $data =  $this->order->create([
            'code' => $code,
            'client_id' => Auth::guard('client')->user()->id,
            'store_local_id' => $store_local_id,
            'delivery_id' => $delivery_id,
            'delivery_men_id' => 1,
            'order_status_id' => 1,
            'address' => $address,
            'document' => 'DNI',
            'ruc' => $request['order'][3],
            'raeson' => $request['order'][4],
            'addressbusiness' => $request['order'][5],
            'type' =>  $request['order'][0],
            'total' => $request['order'][8]
          ]);

        if($data){

            $c = $this->client->find(Auth::guard('client')->user()->id);
            $c->dni = $request['order'][9];
            $c->phone = $request['order'][10];
            $c->save();

            $cartExist = Session::get('cart');
            $cart = new Cart($cartExist);

            foreach ($cart->items as $item){
                OrderDetail::create([
                    'order_id' => $data->id,
                    'product_id' => $item['item']->id,
                    'code' => $data->code,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'],
                ]);
            }

            $p = array(
                'code' => $data->code,
                'total' => $data->total,
            );

            Mail::send('mails.order.confirmation', $p , function ($msj){
                $msj->from('soporte@comprando.pe', 'ComprandoPe');
                $msj->subject('Pedido registrado - Pendiente ');
                $msj->to(Auth::guard('client')->user()->email);
            });

            Session::forget('cart');

            return response()->json([ 'status' => true ]);

        }

        return response()->json([ 'status' => false ]);

    }


    public function order_tcredit(Request $request)
    {
        $store_local_id = 99;
        $delivery_id = 99;
        $code = 'P00000';
        $address = null;

        $allOrder = DB::table('orders')
            ->select('orders.*')
            ->get();

        if(count($allOrder) > 0){

           $ucode = DB::table('orders')
                ->select('orders.*')
                ->orderBy('orders.id','desc')->first();

            $code  = $ucode->code;
        }

        $code++;

        if($request['order'][0] == 'Delivery'){

            $find_delivery = $this->delivery->where('district_id', $request['order'][1])->first();
            $delivery_id = $find_delivery->id;

            $address = $request['order'][7];

        }else if($request['order'][0] == 'Tienda'){

            $store_local_id = $request['order'][2];

            $address = $request['order'][6];

        }

        $data =  $this->order->create([
            'code' => $code,
            'client_id' => Auth::guard('client')->user()->id,
            'store_local_id' => $store_local_id,
            'delivery_id' => $delivery_id,
            'delivery_men_id' => 1,
            'order_status_id' => 2,
            'address' => $address,
            'document' => 'DNI',
            'ruc' => $request['order'][3],
            'raeson' => $request['order'][4],
            'addressbusiness' => $request['order'][5],
            'type' =>  $request['order'][0],
            'total' => $request['order'][8]
        ]);

        if($data){

            $c = $this->client->find(Auth::guard('client')->user()->id);
            $c->dni = $request['order'][9];
            $c->phone = $request['order'][10];
            $c->save();

            $cartExist = Session::get('cart');
            $cart = new Cart($cartExist);

            foreach ($cart->items as $item){
                OrderDetail::create([
                    'order_id' => $data->id,
                    'product_id' => $item['item']->id,
                    'code' => $data->code,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'],
                ]);
            }

            $p = array(
                'code' => $data->code,
                'total' => $data->total,
            );

            Mail::send('mails.order.preparation', $p , function ($msj){
                $msj->from('soporte@comprando.pe', 'ComprandoPe');
                $msj->subject('Pedido registrado - Preparación ');
                $msj->to(Auth::guard('client')->user()->email);
            });

            Session::forget('cart');

            return response()->json([ 'status' => true ]);

        }

        return response()->json([ 'status' => false ]);

    }


    public function detail_order($code)
    {
        $data = $this->order->where('code', $code)->first();
        $data_order = null;

        if($data->store_local_id != 99){

            $data_order = DB::table('orders')
                ->join('store_locals', 'orders.store_local_id', '=', 'store_locals.id')
                ->join('delivery_men', 'orders.delivery_men_id', '=', 'delivery_men.id')
                ->select('orders.*', 'store_locals.name as store', 'store_locals.address as store_address',
                    'delivery_men.name as repartierName')
                ->where('orders.code', $code)
                ->first();

        }else if($data->delivery_id != 99){

            $data_order = DB::table('orders')
                ->join('deliveries', 'orders.delivery_id', '=', 'deliveries.id')
                ->join('districts', 'deliveries.district_id', '=', 'districts.id')
                ->join('delivery_men', 'orders.delivery_men_id', '=', 'delivery_men.id')
                ->select('orders.*', 'districts.name as district', 'deliveries.price as price_delivery',
                    'delivery_men.name as repartierName')
                ->where('orders.code', $code)
                ->first();

            //return response()->json([ 'data_order' => "entro",  ]);

        }

        $data_dorder = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('order_details.*', 'products.name as product', 'products.price as product_price',
                'products.image as product_image')
            ->where('order_details.code', $code)
            ->get();

        return response()->json([ 'data_order' => $data_order, 'data_dorder' => $data_dorder ]);

    }

    public function product_comment(Request $request){

        $comment = new ProductComment();
        $comment->client_id = Auth::guard('client')->user()->id;
        $comment->product_id = $request->get('product_id');
        $comment->comment = $request->get('comment');
        $comment->save();

        return redirect()->to('/product/product-'.$comment->product_id);

    }

    public function product_view_comment(Request $request){

        if(Session::get('History'))
            return redirect()->to(Session::get('History'));

        return redirect()->to(route('index'));

    }


}
