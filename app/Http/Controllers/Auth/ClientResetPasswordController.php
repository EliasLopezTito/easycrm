<?php

namespace NavegapComprame\Http\Controllers\Auth;

use NavegapComprame\Categorie;
use NavegapComprame\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Password;
use Auth;

class ClientResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $categorie;
    public function __construct(Categorie $categorie)
    {
        $this->categorie = $categorie;
        $this->middleware('guest:client');
    }

    protected function guard()
    {
        return Auth::guard('client');
    }

    protected function broker()
    {
     return Password::broker('clients');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('reset')->with(
            ['categories' => $this->categorie->orderBy('name', 'asc')->get(),
                'token' => $token, 'email' => $request->email]
        );
    }
}
