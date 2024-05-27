<?php

namespace NavegapComprame\Http\Controllers\Auth;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use NavegapComprame\Cart;
use NavegapComprame\Categorie;
use NavegapComprame\City;
use NavegapComprame\Client;
use NavegapComprame\Departament;
use NavegapComprame\Http\Controllers\Controller;
use NavegapComprame\Product;
use NavegapComprame\SocialProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use NavegapComprame\TypePay;
use Socialite;

class ClientAuthController extends Controller
{
   private $client, $categorie, $product;
   public function __construct(Client $client, Categorie $categorie, Product $product)
   {
       $this->client = $client;
       $this->categorie = $categorie;
       $this->product = $product;
       $this->middleware('guest:client');
   }

    public function login()
    {
        $data = array(
            'categories' => $this->categorie->orderBy('name', 'asc')->get(),
            'products' => $this->product->orderBy('created_at', 'desc')->get(),
        );

        return view('login')->with($data);
    }

    public function login_post(Request $request)
    {
        $errors = ['email' => trans('auth.failed')];

        $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required|min:6'
        ]);


       if(Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], true)){

           if(Session::has('city') && Auth::guard('client')->user()->city_id != intval(Session::get('city'))){
               Session::forget('cart');
           }

           if(Session::get('History')){

               if(Session::has('Params')){

                   if(Session::has('cart')) {

                       $cartExist = Session::get('cart');
                       $cart = new Cart($cartExist);

                       $data = array(
                           'typePay' => TypePay::find(Session::get('Params')->get('type_pay_id')),
                           'totalPrice' => number_format($cart->totalPrice, 2),
                           'totalQuantity' => $cart->totalQuantity
                       );

                       Session::forget('Params');
                       return view('checkoutStore', $data);
                   }

                   return redirect()->route('home');

               }else{
                   return redirect()->to(Session::get('History'));
               }
           }

           return redirect()->intended(route('index'));
       }

       return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors($errors);

    }

    public function register()
    {
        return view('register');
    }

    public function register_post(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:clients',
            'name' => 'required|min:2',
            'lastname' => 'required|min:2',
            'phone' => 'required|min:5',
            'password' => 'required|min:6'
        ]);

        $city = City::where('id', Session::get('city'))->first();

        $c = $this->client->create([
            'departament_id' => $city->departament_id,
            'city_id' => $city->id,
            'name' => $request->name,
            'last_name' => $request->lastname,
            'dni' => $request->dni,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password)
        ]);

       Mail::send('mails.register', $request->all() , function ($msj) use ($c) {
            $msj->from('soporte@comprando.pe', 'Comprando');
            $msj->subject('Comprando le da la bienvenida');
            $msj->to($c->email);
        });

        if($c){

            Auth::guard('client')->login($c);

            if(Session::get('History')){

                if(Session::has('Params')){

                    if(Session::has('cart')) {

                        $cartExist = Session::get('cart');
                        $cart = new Cart($cartExist);

                        $data = array(
                            'typePay' => TypePay::find(Session::get('Params')->get('type_pay_id')),
                            'totalPrice' => number_format($cart->totalPrice, 2),
                            'totalQuantity' => $cart->totalQuantity
                        );

                        Session::forget('Params');
                        return view('checkoutStore', $data);
                    }

                    return redirect()->route('home');

                }else{
                    return redirect()->to(Session::get('History'));
                }
            }

            return redirect()->intended(route('index'));
        }

        return redirect()->back()->withInput($request->only('name', 'lastname', 'dni', 'email', 'phone'));

    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try{

         if($provider == 'facebook'){
             $socialClient = Socialite::driver($provider)->fields([
                 'first_name', 'last_name', 'email'
             ])->user();

         }else if($provider == 'google'){
             $socialClient = Socialite::driver($provider)->scopes([
                 'name'
             ])->user();
         }
        }catch(\Exception $e){
            return $e->getMessage();
        }

       $socialProvider = SocialProvider::where('provider_id',
                         $socialClient->getId())->first();

        if(!$socialProvider)
        {

        $emailSocial = null;
        if($provider == 'facebook'){
            $emailSocial = $socialClient['email'];
        }else if($provider == 'google'){
            $emailSocial = $socialClient->getEmail();
        }

         $c = $this->client->where('email', $emailSocial)->first();

          if($c){

          $c->socialProviders()->create([
              'provider_id' => $socialClient->getId(),
              'provider' => $provider
          ]);

          }else{

              $city = City::find(Session::get('city'));

          if($provider == 'facebook'){
           $c = $this->client->firstOrCreate([
                'departament_id' => $city->departament_id,
                 'city_id' => $city->id,
                'name' =>  $socialClient['first_name'],
                'last_name' =>  $socialClient['last_name'],
                'email' => $socialClient['email'],
            ]);
          }else if($provider == 'google'){
              $c = $this->client->firstOrCreate([
                  'departament_id' => $city->departament_id,
                  'city_id' => $city->id,
                  'name' =>  $socialClient['name']['givenName'],
                  'last_name' =>  $socialClient['name']['familyName'],
                  'email' => $socialClient->getEmail(),
              ]);
          }

           $data = array('name' => $c->name, 'lastname' => $c->last_name, 'email' => $c->email);

            $c->socialProviders()->create([
                'provider_id' => $socialClient->getId(),
                'provider' => $provider
            ]);

           Mail::send('mails.register', $data , function ($msj) use ($c) {
                $msj->from('soporte@comprando.com', 'ComprandoPe');
                $msj->subject('¡Te damos la bienvenida a ComprandoPe!');
                $msj->to($c->email);
            });
          }
        }else {
            $c = $socialProvider->client;
         }

        Auth::guard('client')->login($c);

        if(Session::get('History')){

            if(Session::has('Params')){

                if(Session::has('cart')) {

                    $cartExist = Session::get('cart');
                    $cart = new Cart($cartExist);

                    $data = array(
                        'typePay' => TypePay::find(Session::get('Params')->get('type_pay_id')),
                        'totalPrice' => number_format($cart->totalPrice, 2),
                        'totalQuantity' => $cart->totalQuantity
                    );

                    Session::forget('Params');
                    return view('checkoutStore', $data);
                }

                return redirect()->route('home');

            }else{
                return redirect()->to(Session::get('History'));
            }
        }

        return redirect()->intended(route('index'));

    }

}
