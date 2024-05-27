<?php

namespace NavegapComprame\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NavegapComprame\App;
use NavegapComprame\Http\Controllers\Controller;
use NavegapComprame\Order;
use NavegapComprame\OrderStatu;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.order.index', [ 'UserPerfil' => Auth::user()->perfil_id ]);
    }

    public function list_all()
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR)
            return response()->json(['data' => Order::with('user')->with('client')->with('client.city')->with('orderStatus')->get()]);

        return response()->json(['data' => Order::with('user')->with('client')->with('client.city')->with('orderStatus')->where('user_id', Auth::user()->id)->get() ]);
    }

    public function partialView($id)
    {
        $entity = null;

        if($id != 0)
            $entity = Order::with('orderDetail')->with('typePay')->where('id', $id)->first();

        $OrderStatus = OrderStatu::all();

        return view('auth.order._Maintenance', ['Order' => $entity, 'OrderStatus' => $OrderStatus]);
    }

    public function order($code){

        $entity = Order::with('typePay')->with('client')->with('client.city')
            ->with('orderDetail')
            ->where('code', "O".$code)->first();

        if($entity != null && (Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR  ||
                (Auth::user()->perfil_id == App::$PERFIL_EMPRESA && Auth::user()->id == $entity->user_id)))
            return view('auth.order.Maintenance', ['Order' => $entity]);

        return redirect(route('auth.order'));
    }

    public function store(Request $request)
    {
        $status = false;

        $entity =  Order::find($request->get('id'));
        $entity->order_status_id = $request->get('order_status_id');

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status, 'Status' => $entity->order_status_id ]);
    }


}
