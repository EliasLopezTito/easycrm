<?php

namespace NavegapComprame\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use NavegapComprame\Categorie;
use NavegapComprame\City;
use NavegapComprame\Client;
use NavegapComprame\ClientCategorie;
use NavegapComprame\ClientFavorite;
use NavegapComprame\ClientStoreFavorite;
use NavegapComprame\DeliveryMan;
use NavegapComprame\Departament;
use NavegapComprame\Order;
use NavegapComprame\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    private $categorie, $client, $clientFavorite, $clientStoreFavorite, $order;
    public function __construct(Categorie $categorie, Client $client, ClientFavorite $clientFavorite,
                              ClientStoreFavorite $clientStoreFavorite,  Order $order)
    {
        $this->client = $client;
        $this->categorie = $categorie;
        $this->clientFavorite = $clientFavorite;
        $this->clientStoreFavorite = $clientStoreFavorite;
        $this->order = $order;
        $this->middleware('auth:client');
    }

    public function account()
    {
        $data = array(
            'Departaments' => Departament::all(),
            'Cities' => Auth::guard('client')->user()->city_id ? City::where('departament_id', Auth::guard('client')->user()->departament_id)->get() : City::all(),
            'orders' =>  $this->order->getListOrders(),
            'orders_details' => OrderDetail::all(),
            'Categories' => $this->categorie->orderBy('name', 'asc')->get(),
            'UserCategorie' => Auth::guard('client')->user()->clientCategories->pluck('categorie_id')->toArray()
    );

        return view('account')->with($data);
    }

    public function information(Request $request){

        $this->validate($request, [
            'name' => 'required|min:2',
            'phone' => 'required|min:5',
        ]);

        $data = $this->client->find(Auth::guard('client')->user()->id);

        $data->departament_id = Input::get('departament_id');
        $data->city_id = Input::get('city_id');
        $data->name = Input::get('name');
        $data->phone = Input::get('phone');
        $data->dni = Input::get('dni');
        $data->password  = $request->get('password') == null ? $data->password : bcrypt($request->get('password'));
        $data->date_birthday  = $request->get('date_birthday');

        if($data->save()){

            ClientCategorie::where('client_id', $data->id)->delete();

            foreach ($request->get('categorie_id') as $s){
                $clientCategorie = new ClientCategorie();
                $clientCategorie->client_id = $data->id;
                $clientCategorie->categorie_id =  $s;
                $clientCategorie->save();
            }

           /* Mail::send('mails.information', $request->all() , function ($msj) use ($data) {
                $msj->from('cakestore@sptstudio.com', 'CakeStore');
                $msj->subject('Actualización de información');
                $msj->to($data->email);
            });*/

            return redirect()->route('client.account')
                ->with('status', 'Se guardarón los cambios correctamente.');
        }else{
            return redirect()->back()->withInput($request->only('name', 'lastname', 'dni', 'phone'));
        }
    }

    public function favoriteStore(){

        return view('favoriteStore');
    }

    public function add_to_store_favorite($id){

        $data = $this->clientStoreFavorite->where('client_id', Auth::guard('client')
            ->user()->id)->where('user_id', $id)->first();

        if(!$data){

            $this->clientStoreFavorite->create([ 'client_id' => Auth::guard('client')->user()->id,
                'user_id' => $id]);

            return response()->json([ 'status' => true, 'Favorites' => count(ClientStoreFavorite::where('user_id', $id)->get()) ]);
        }

        return response()->json([ 'status' => false ]);

    }

    public function get_items_store_favorite(){

        $data =  ClientStoreFavorite::storesFavorites(Auth::guard('client')->user()->id);
        if(count($data) > 0){
            return response()->json([ 'data' => $data ]);
        }
        return response()->json([ 'data' => null ]);
    }

    public function remove_item_store_favorite($id){
        $data = ClientStoreFavorite::where('user_id',$id)->where('client_id', Auth::guard('client')->user()->id)->firstOrFail();
        ClientStoreFavorite::destroy($data->id);
        return response()->json([ 'status' => true ]);
    }

    public function favorite(){

        return view('favorite');
    }

    public function shopping()
    {
        $shopping = Order::with('orderStatus')->where('client_id', Auth::guard('client')->user()->id)
            //->with('orderDetail')
            ->orderByDesc('created_at')->paginate(10);

        return view('shopping', ['Shoppings' => $shopping]);
    }

    public function shoppingDetail($id)
    {
        $shopping = Order::find($id);

        if($shopping != null && $shopping->client_id == Auth::guard('client')->user()->id){
            $shoppingDetail = OrderDetail::with('order')->with('product')
                ->where('order_id', $id)->orderByDesc('created_at')->get();
            return view('shoppingDetail', ['Shopping' => $shopping, 'ShoppingDetail' => $shoppingDetail]);
        }

        return redirect(route('index'));
    }

    public function add_to_favorite($id){

     $data = $this->clientFavorite->where('client_id', Auth::guard('client')
         ->user()->id)->where('product_id', $id)->first();

        if(!$data){

            $this->clientFavorite->create([ 'client_id' => Auth::guard('client')->user()->id,
                'product_id' => $id]);

            return response()->json([ 'status' => true, 'Favorites' => count(ClientFavorite::where('product_id', $id)->get()) ]);
        }

        return response()->json([ 'status' => false ]);

    }

    public function get_items_favorite(){

        $data =  ClientFavorite::productsFavorites(Auth::guard('client')->user()->id);
        if(count($data) > 0){
            return response()->json([ 'data' => $data ]);
        }
        return response()->json([ 'data' => null ]);
    }

    public function remove_item_favorite($id){

        $data = ClientFavorite::where('product_id',$id)->where('client_id', Auth::guard('client')->user()->id)->firstOrFail();
        ClientFavorite::destroy($data->id);
        return response()->json([ 'status' => true ]);

    }

    public function changepassword(Request $request){

        $data = $this->client->find(Auth::guard('client')->user()->id);

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $data->password =  bcrypt(Input::get('password'));
        if($data->save()){

            Mail::send('mails.changepassword', $request->all() , function ($msj) use ($data) {
                $msj->from('cakestore@sptstudio.com', 'CakeStore');
                $msj->subject('Actualización de contraseña');
                $msj->to($data->email);
            });

            return redirect()->route('client.account')
                ->with('status', 'Su contraseña ha sido cambiada correctamente.');
        }else{
            return redirect()->back()->withInput($request->only('password'));
        }
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        Session::forget('cart');
        Session::forget('city');
        Session::has('Params');
        return redirect()->to(route('client.login'));
    }

}
