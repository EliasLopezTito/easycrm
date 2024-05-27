<?php

namespace NavegapComprame\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use NavegapComprame\City;
use NavegapComprame\Departament;
use NavegapComprame\Http\Controllers\Controller;

class CityController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $departaments = Departament::with('cities')->get();

        if(!Auth::guard('client')->check() || (Auth::guard('client')->check() && Auth::guard('client')->user()->city_id == 0))
            return view('city.index', ['Departaments' => $departaments]);

        return redirect(route('index'));
    }

    public function selectCity(Request $request)
    {
        if(Session::has('city') && intval(Session::get('city')) != intval($request->get('city')))
            Session::forget('cart');

        $request->session()->put('city', $request->get('city'));

        return redirect(route('index'));
    }
}
