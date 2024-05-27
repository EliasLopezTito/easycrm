<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $registerView = 'auth.login.register';

    public function __construct()
    {
        $this->middleware('guest:web', ['except' => ['logout'] ]);
    }

    protected function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->merge([
            'password' => Hash::make($request->password)
        ]);

        $user = User::create($request->all());

        Auth::guard('web')->login($user);

        return redirect(route('user.micuenta_confirmacion'));

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'tipo_documento_id' => 'required',
            'nro_documento' => 'required|min:6|max:30',
            'nombres' => 'required|max:200',
            'apellidoMaterno' => 'required|max:200',
            'apellidoPaterno' => 'required|max:200',
            'email' => 'required|email|max:200|unique:users',
            'password' => 'required|confirmed|min:6',
            'telefono' => 'required|max:15',
            'genero' => 'required',
            'promociones' => 'boolean',
            'terminos_condiciones' => 'required|boolean',
        ]);
    }
}
