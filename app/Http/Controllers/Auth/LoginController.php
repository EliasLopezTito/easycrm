<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';
    protected $redirectAfterLogout = '/login';
    protected $loginView = 'auth.login.index';
    protected $username = 'email';

    public function __construct()
    {
        $this->middleware('guest:web', ['except' => ['logout', 'partialView_change_password', 'change_password'] ]);
        if(isset($_POST['validacion'])){
            User::where('email', $_POST['validacion'])->update(['online' => 0]);
            User::where('email', $_POST['validacion'])->update(['cerrar_sesion' => date('y-m-d h:i:s')]);
        }
    }

    protected function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->getCredentials($request);

        $usuario = User::where('activo', true)->where('email', $credentials['email'])->first();

        if ($usuario != null && Hash::check($credentials['password'], $usuario->password)) {
            Auth::guard($this->getGuard())->login($usuario, $request->has('remember'));
            User::where('email', $credentials['email'])->update(['online' => 1]);
            User::where('email', $credentials['email'])->update(['inicio_sesion' => date('y-m-d h:i:s')]);
            return $this->handleUserWasAuthenticated($request, null);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function partialView_change_password()
    {
        return view('auth.login._ChangePassword');
    }

    public function change_password(Request $request)
    {
        $status = false;

        $validator = Validator::make($request->all(), [
            'password_old' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6'
        ]);

        if (!$validator->fails()){

            $usuario = User::find(Auth::guard('web')->user()->id);

            if(Hash::check($request->password_old, $usuario->password)) {
                $usuario->password = Hash::make($request->password);
                if($usuario->save()) $status = true;
            }else{
                return response()->json(['Success' => $status, 'Message' => "La contraseña actual ingresada no es la correcta"]);
            }
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }

}
