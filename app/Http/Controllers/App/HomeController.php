<?php

namespace easyCRM\Http\Controllers\App;

use easyCRM\Carrera;
use easyCRM\Cliente;
use easyCRM\ClienteMatricula;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Exception;

class HomeController extends Controller
{
    public function webhooks_auth()
    {
        return View('app.webhooks.auth');
    }

    public function webhooks_auth_platform()
    {
        return View('app.webhooks.auth_platform');
    }

    public function webhooks_auth_generateToken(Request $request)
    {
        Storage::disk('public_html')->put('token_facebook.txt', $request->token);

        $path = Storage::disk('public_html')->url('token_facebook.txt');

        return response()->json(['Success' => true, 'token' => $request->token, 'path' => $path]);
    }

    public function webhooks(Request $request)
    {
        try {

            $challenge = $request['hub_challenge'];
            $verify_token = $request['hub_verify_token'];

            if ($verify_token === 'qwerty') {
                echo $challenge;
            }

            $input = json_decode(file_get_contents('php://input'), true);

            $client = new Client();

            $form_id = $input["entry"][0]["changes"][0]["value"]["form_id"];
            $leadgen_id = $input["entry"][0]["changes"][0]["value"]["leadgen_id"];
            $token = Storage::disk('public_html')->get('token_facebook.txt');

            $response = $client->request(
                'GET',
                'https://graph.facebook.com/' . $leadgen_id,
                [
                    RequestOptions::HEADERS => [
                        'Accept' => "application/json",
                        'Cache-Control' => "no-cache",
                    ],
                    RequestOptions::QUERY => [
                        'access_token' => $token
                    ]
                ]
            );

            $response_carrera = $client->request(
                'GET',
                'https://graph.facebook.com/' . $form_id,
                [
                    RequestOptions::HEADERS => [
                        'Accept' => "application/json",
                        'Cache-Control' => "no-cache",
                    ],
                    RequestOptions::QUERY => [
                        'access_token' => $token
                    ]
                ]
            );

            $result = json_decode($response->getBody());
            $result_carrera = json_decode($response_carrera->getBody());

            if ($result) {
                $data = $result->field_data;
                $nombres = null;
                $apellidos = null;
                $email = null;
                $telefono = null;
                $dni = null;

                if ($data[0] != null && ($data[0]->name == "nombre" || $data[0]->name == "first_name")) {
                    $nombres = $data[0]->values[0];
                } else if ($data[1] != null && ($data[1]->name == "nombre" || $data[1]->name == "first_name")) {
                    $nombres = $data[1]->values[0];
                } else if ($data[2] != null && ($data[2]->name == "nombre" || $data[2]->name == "first_name")) {
                    $nombres = $data[2]->values[0];
                } else if ($data[3] != null && ($data[3]->name == "nombre" || $data[3]->name == "first_name")) {
                    $nombres = $data[3]->values[0];
                }

                if ($data[0] != null && ($data[0]->name == "apellido" || $data[0]->name == "last_name")) {
                    $apellidos = $data[0]->values[0];
                } else if ($data[1] != null && ($data[1]->name == "apellido" || $data[1]->name == "last_name")) {
                    $apellidos = $data[1]->values[0];
                } else if ($data[2] != null && ($data[2]->name == "apellido" || $data[2]->name == "last_name")) {
                    $apellidos = $data[2]->values[0];
                } else if ($data[3] != null && ($data[3]->name == "apellido" || $data[3]->name == "last_name")) {
                    $apellidos = $data[3]->values[0];
                }

                if ($data[0] != null && $data[0]->name == "email") {
                    $email = $data[0]->values[0];
                } else if ($data[1] != null && $data[1]->name == "email") {
                    $email = $data[1]->values[0];
                } else if ($data[2] != null && $data[2]->name == "email") {
                    $email = $data[2]->values[0];
                } else if ($data[3] != null && $data[3]->name == "email") {
                    $email = $data[3]->values[0];
                }

                if ($data[0] != null && $data[0]->name == "phone_number") {
                    $telefono = $data[0]->values[0];
                } else if ($data[1] != null && $data[1]->name == "phone_number") {
                    $telefono = $data[1]->values[0];
                } else if ($data[2] != null && $data[2]->name == "phone_number") {
                    $telefono = $data[2]->values[0];
                } else if ($data[3] != null && $data[3]->name == "phone_number") {
                    $telefono = $data[3]->values[0];
                }

                if ($telefono) {
                    $telefono = str_replace("+51", "", $telefono);
                    $dni = substr($telefono, -8);
                }

                $Carrera = Carrera::where('alias', $result_carrera->name)->first();

                $client->request(
                    'POST',
                    'https://easycrm.ial.edu.pe/api/cliente/create',
                    [
                        RequestOptions::HEADERS => [
                            'Accept' => "application/json",
                            'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                            'Cache-Control' => "no-cache",
                        ],
                        RequestOptions::JSON => [
                            "nombres" => $nombres,
                            "apellidos" => $apellidos,
                            "dni" => $dni,
                            "celular" => $telefono,
                            "email" => $email,
                            "fecha_nacimiento" => date("Y-m-d H:i:s"),
                            "provincia" => 0,
                            "provincia_id" => 1,
                            "distrito_id" => 1,
                            "modalidad_id" => $Carrera->modalidad_id,
                            "carrera_id" => $Carrera->id,
                            "fuente_id" => 5,
                            "enterado_id" => 1
                        ]
                    ]
                );

                //Log::info('User access.', ['result' => $nombres." "$apellidos]);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            Log::info('User failed.', ['id' => $message]);
        }
    }

    public function webhooks_post(Request $request)
    {
        try {

            $challenge = $request['hub_challenge'];
            $verify_token = $request['hub_verify_token'];

            if ($verify_token === 'qwerty') {
                echo $challenge;
            }

            $input = json_decode(file_get_contents('php://input'), true);

            $client = new Client();

            $form_id = $input["entry"][0]["changes"][0]["value"]["form_id"];
            $leadgen_id = $input["entry"][0]["changes"][0]["value"]["leadgen_id"];
            $token = Storage::disk('public_html')->get('token_facebook.txt');

            $response = $client->request(
                'GET',
                'https://graph.facebook.com/' . $leadgen_id,
                [
                    RequestOptions::HEADERS => [
                        'Accept' => "application/json",
                        'Cache-Control' => "no-cache",
                    ],
                    RequestOptions::QUERY => [
                        'access_token' => $token
                    ]
                ]
            );

            $response_carrera = $client->request(
                'GET',
                'https://graph.facebook.com/' . $form_id,
                [
                    RequestOptions::HEADERS => [
                        'Accept' => "application/json",
                        'Cache-Control' => "no-cache",
                    ],
                    RequestOptions::QUERY => [
                        'access_token' => $token
                    ]
                ]
            );

            $result = json_decode($response->getBody());
            $result_carrera = json_decode($response_carrera->getBody());

            if ($result) {
                $data = $result->field_data;
                $nombres = null;
                $apellidos = null;
                $email = null;
                $telefono = null;
                $dni = null;

                if ($data[0] != null && ($data[0]->name == "nombre" || $data[0]->name == "first_name")) {
                    $nombres = $data[0]->values[0];
                } else if ($data[1] != null && ($data[1]->name == "nombre" || $data[1]->name == "first_name")) {
                    $nombres = $data[1]->values[0];
                } else if ($data[2] != null && ($data[2]->name == "nombre" || $data[2]->name == "first_name")) {
                    $nombres = $data[2]->values[0];
                } else if ($data[3] != null && ($data[3]->name == "nombre" || $data[3]->name == "first_name")) {
                    $nombres = $data[3]->values[0];
                }

                if ($data[0] != null && ($data[0]->name == "apellido" || $data[0]->name == "last_name")) {
                    $apellidos = $data[0]->values[0];
                } else if ($data[1] != null && ($data[1]->name == "apellido" || $data[1]->name == "last_name")) {
                    $apellidos = $data[1]->values[0];
                } else if ($data[2] != null && ($data[2]->name == "apellido" || $data[2]->name == "last_name")) {
                    $apellidos = $data[2]->values[0];
                } else if ($data[3] != null && ($data[3]->name == "apellido" || $data[3]->name == "last_name")) {
                    $apellidos = $data[3]->values[0];
                }

                if ($data[0] != null && $data[0]->name == "email") {
                    $email = $data[0]->values[0];
                } else if ($data[1] != null && $data[1]->name == "email") {
                    $email = $data[1]->values[0];
                } else if ($data[2] != null && $data[2]->name == "email") {
                    $email = $data[2]->values[0];
                } else if ($data[3] != null && $data[3]->name == "email") {
                    $email = $data[3]->values[0];
                }

                if ($data[0] != null && $data[0]->name == "phone_number") {
                    $telefono = $data[0]->values[0];
                } else if ($data[1] != null && $data[1]->name == "phone_number") {
                    $telefono = $data[1]->values[0];
                } else if ($data[2] != null && $data[2]->name == "phone_number") {
                    $telefono = $data[2]->values[0];
                } else if ($data[3] != null && $data[3]->name == "phone_number") {
                    $telefono = $data[3]->values[0];
                }

                if ($telefono) {
                    $telefono = str_replace("+51", "", $telefono);
                    $dni = substr($telefono, -8);
                }

                $Carrera = Carrera::where('alias', $result_carrera->name)->first();

                $client->request(
                    'POST',
                    'https://easycrm.ial.edu.pe/api/cliente/create',
                    [
                        RequestOptions::HEADERS => [
                            'Accept' => "application/json",
                            'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                            'Cache-Control' => "no-cache",
                        ],
                        RequestOptions::JSON => [
                            "nombres" => $nombres,
                            "apellidos" => $apellidos,
                            "dni" => $dni,
                            "celular" => $telefono,
                            "email" => $email,
                            "fecha_nacimiento" => date("Y-m-d H:i:s"),
                            "provincia" => 0,
                            "provincia_id" => 1,
                            "distrito_id" => 1,
                            "modalidad_id" => $Carrera->modalidad_id,
                            "carrera_id" => $Carrera->id,
                            "fuente_id" => 5,
                            "enterado_id" => 1
                        ]
                    ]
                );

                //Log::info('User access.', ['result' => $nombres." "$apellidos]);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            Log::info('User failed.', ['id' => $message]);
        }
    }

    public function tiktok(Request $request)
    {
        try {

            $client = new Client();

            Log::info('Zapier Result 3.', ['res' => $request->all()]);

            $nombres = $request->get('first_name');
            $apellidos = $request->get('last_name');
            $email = $request->get('email');
            $telefono = $request->get('phone_number');
            $dni = null;

            if ($telefono) {
                $dni = substr($telefono, -8);
            }

            $Carrera = Carrera::where('alias', 'Form ENFE')->first();

            $client->request(
                'POST',
                'https://easycrm.ial.edu.pe/api/cliente/create',
                [
                    RequestOptions::HEADERS => [
                        'Accept' => "application/json",
                        'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                        'Cache-Control' => "no-cache",
                    ],
                    RequestOptions::JSON => [
                        "nombres" => $nombres,
                        "apellidos" => $apellidos,
                        "dni" => $dni,
                        "celular" => $telefono,
                        "email" => $email,
                        "fecha_nacimiento" => date("Y-m-d H:i:s"),
                        "provincia" => 0,
                        "provincia_id" => 1,
                        "distrito_id" => 1,
                        "modalidad_id" => $Carrera->modalidad_id,
                        "carrera_id" => $Carrera->id,
                        "fuente_id" => 34,
                        "enterado_id" => 7
                    ]
                ]
            );

            Log::info('Zapier .', [
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'email' => $email,
                'telefono' => $telefono,
                'dni' => $dni
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            Log::info('User failed.', ['id' => $message]);
        }

        Log::info('User .', ['id' => 'Success']);

        return response()->json($request->all());
    }

    public function tiktokads(Request $request)
    {
        $client = new Client();
        $form_id = $request->get('FormId');
        $nombres = $request->get('FirstName');
        $apellidos = $request->get('LastName');
        $email = $request->get('Email');
        $telefono = $request->get('Phone');
        $dni = null;
        if ($telefono) {
            $dni = substr($telefono, -8);
        }
        $Carrera = Carrera::where('id', 5)->first();
        $client->request(
            'POST',
            'https://easycrm.ial.edu.pe/api/cliente/create',
            [
                RequestOptions::HEADERS => [
                    'Accept' => "application/json",
                    'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                    'Cache-Control' => "no-cache",
                ],
                RequestOptions::JSON => [
                    "nombres" => $nombres,
                    "apellidos" => $apellidos,
                    "dni" => $dni,
                    "celular" => $telefono,
                    "email" => $email,
                    "fecha_nacimiento" => date("Y-m-d H:i:s"),
                    "provincia" => 0,
                    "provincia_id" => 1,
                    "distrito_id" => 1,
                    "modalidad_id" => $Carrera->modalidad_id,
                    "carrera_id" => $Carrera->id,
                    "fuente_id" => 34,
                    "enterado_id" => 7
                ]
            ]
        );
        return response()->json($request->all());
    }

    public function maketiktokads(Request $request)
    {
        try {

            $client = new Client();

            Log::info('Make.', ['res' => $request->all()]);

            $nombres = $request->get('FirstName');
            $apellidos = $request->get('LastName');
            $email = $request->get('Email');
            $telefono = $request->get('Phone');
            $fuente = 5;
            $dni = null;
            $Carrera = null;

            if ($telefono) {
                $dni = substr($telefono, -8);
            }

            if ($request->get('FormId') == "7354427944133050640") { // API FUENTE TIKTOK ADS
                $Carrera = Carrera::where('id', 5)->first();
                $fuente = 34;
            } else {
                $fuente = 34;
                $Carrera = Carrera::where('id', 5)->first();
            }

            if ($Carrera) {
                $client->request(
                    'POST',
                    'https://easycrm.ial.edu.pe/api/cliente/create',
                    [
                        RequestOptions::HEADERS => [
                            'Accept' => "application/json",
                            'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                            'Cache-Control' => "no-cache",
                        ],
                        RequestOptions::JSON => [
                            "nombres" => $nombres,
                            "apellidos" => $apellidos,
                            "dni" => $dni,
                            "celular" => $telefono,
                            "email" => $email,
                            "fecha_nacimiento" => date("Y-m-d H:i:s"),
                            "provincia" => 0,
                            "provincia_id" => 1,
                            "distrito_id" => 1,
                            "modalidad_id" => $Carrera->modalidad_id,
                            "carrera_id" => $Carrera->id,
                            "fuente_id" => $fuente,
                            "enterado_id" => 1
                        ]
                    ]
                );

                Log::info('Make .', [
                    'Status' => "Success make tiktokads",
                    'fuente' => $fuente,
                    'carrera' => $Carrera->name,
                    'select' => $Carrera,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'telefono' => $telefono,
                    'dni' => $dni
                ]);
            } else {
                Log::info('Make .', [
                    'Status' => "Error make tiktokads",
                    'fuente' => $fuente,
                    'carrera' => $Carrera->name,
                    'select' => $Carrera,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'telefono' => $telefono,
                    'dni' => $dni
                ]);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            Log::info('User failed Make.', ['id' => $message]);
        }

        Log::info('User Make.', ['id' => 'Success']);

        return response()->json($request->all());
    }

    /*  public function make(Request $request)
    {
        try {

            $client = new Client();

            Log::info('Make.', ['res' => $request->all()]);

            $nombres = $request->get('FirstName');
            $apellidos = $request->get('LastName');
            $email = $request->get('Email');
            $telefono = $request->get('Phone');
            $fuente = 5;
            $dni = null;
            $Carrera = null;

            if($telefono) {
                $dni = substr($telefono, -8);
            }

            if($request->get('FormId') == "2180585935620286"){ //ENFERMERIA
                $Carrera = Carrera::where('id', 1)->first();
            }else if($request->get('FormId') == "1169485510710930"){ //FARMACIA           
                $Carrera = Carrera::where('id', 2)->first();
            }else if($request->get('FormId') == "1133693821113148"){  //FISIOTERAPIA             
                $Carrera = Carrera::where('id', 3)->first();
            }else if($request->get('FormId') == "1690095204729761"){ //LABORATORIO
                $Carrera = Carrera::where('id', 4)->first();
            }else if($request->get('FormId') == "961214466014274"){ //PROTESIS DENTAL
                $Carrera = Carrera::where('id', 5)->first();
            }else if($request->get('FormId') == "215243078092677"){ //ENFERMERIA
                $Carrera = Carrera::where('id', 5)->first();
                $fuente = 43;
            }else if($request->get('FormId') == "3792492817740864"){ //OTRA FUENTE CARRERA A ELEGIR
                $Carrera = Carrera::where('id', 5)->first();
                if($request->get('carrera') == 'fisioterapia'){
                    $Carrera = Carrera::where('id', 3)->first();
                }else if($request->get('carrera') == 'enfermeria'){
                    $Carrera = Carrera::where('id', 1)->first();
                }else if($request->get('carrera') == 'farmacia'){
                    $Carrera = Carrera::where('id', 2)->first();
                }else if($request->get('carrera') == 'laboratorio_clinico'){
                    $Carrera = Carrera::where('id', 4)->first();
                }else if($request->get('carrera') == 'protesis_dental'){
                    $Carrera = Carrera::where('id', 5)->first();
                }
                $fuente = 55;
            }else if($request->get('FormId') == "7091620347624516"){ //curso NUTRICION DEPORTIVA
                $Carrera = Carrera::where('id', 40)->first();
            }else if($request->get('FormId') == "318102581061039"){ //curso INYECTABLES
                $Carrera = Carrera::where('id', 37)->first();
            }else if($request->get('FormId') == "613607640851297"){ //curso CONTROL Y MONITOREO
                $Carrera = Carrera::where('id', 35)->first();
            }else if($request->get('FormId') == "78070008407420"){ //curso MKT FARMACEUTICO
                $Carrera = Carrera::where('id', 29)->first();
            }else if($request->get('FormId') == "7354427944133050640"){ // API FUENTE TIKTOK ADS
                $Carrera = Carrera::where('id', 5)->first();
                $fuente = 34;
                $client->request('POST', 'https://easycrm.ial.edu.pe/api/cliente/create',
                [
                        RequestOptions::HEADERS => [
                            'Accept' => "application/json",
                            'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                            'Cache-Control' => "no-cache",
                        ],
                        RequestOptions::JSON => [
                            "nombres" => $nombres,
                            "apellidos" => $apellidos,
                            "dni" => $dni,
                            "celular" => $telefono,
                            "email" => $email,
                            "fecha_nacimiento" => date("Y-m-d H:i:s"),
                            "provincia" => 0,
                            "provincia_id" => 1,
                            "distrito_id" => 1,
                            "modalidad_id" => $Carrera->modalidad_id,
                            "carrera_id" => $Carrera->id,
                            "fuente_id" => $fuente,
                            "enterado_id" => 1 
                        ]
                    ]
                );
            }else if($request->get('FormId') == "1155320879163579"){ // LABORATORIO SIMILAR
                $fuente = 53;
                $Carrera = Carrera::where('id', 4)->first();
            }else if($request->get('FormId') == "1135286467919261"){ // PROTESIS SIMILAR
                $fuente = 53;
                $Carrera = Carrera::where('id', 5)->first();
            }else if($request->get('FormId') == "1228096511507124"){ // FISIOTERAPIA SIMILAR
                $fuente = 53;
                $Carrera = Carrera::where('id', 3)->first();
            }else if($request->get('FormId') == "1152894632404531"){ // FARMACIA SIMILAR
                $fuente = 53;
                $Carrera = Carrera::where('id', 2)->first();
            }else if($request->get('FormId') == "392657383583258"){ // ENFERMERIA SIMILAR
                $fuente = 53;
                $Carrera = Carrera::where('id', 1)->first();
            }
            
            if($Carrera){
                $client->request('POST', 'https://easycrm.ial.edu.pe/api/cliente/create',
                [
                        RequestOptions::HEADERS => [
                            'Accept' => "application/json",
                            'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                            'Cache-Control' => "no-cache",
                        ],
                        RequestOptions::JSON => [
                            "nombres" => $nombres,
                            "apellidos" => $apellidos,
                            "dni" => $dni,
                            "celular" => $telefono,
                            "email" => $email,
                            "fecha_nacimiento" => date("Y-m-d H:i:s"),
                            "provincia" => 0,
                            "provincia_id" => 1,
                            "distrito_id" => 1,
                            "modalidad_id" => $Carrera->modalidad_id,
                            "carrera_id" => $Carrera->id,
                            "fuente_id" => $fuente,
                            "enterado_id" => 1 
                        ]
                    ]
                );

                Log::info('Make .', [
                    'Status' => "Success",
                    'fuente' => $fuente,
                    'carrera' => $Carrera->name,
                    'select' => $Carrera,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'telefono' => $telefono,
                    'dni' => $dni]);
            }else{
                Log::info('Make .', [
                    'Status' => "Error",
                    'fuente' => $fuente,
                    'carrera' => $Carrera->name,
                    'select' => $Carrera,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'telefono' => $telefono,
                    'dni' => $dni]);                
            }
                

        }catch (\Exception $e)
        {
            $message = $e->getMessage();
            Log::info('User failed Make.', ['id' => $message]);
        }

        Log::info('User Make.', ['id' => 'Success']);

        return response()->json($request->all());
    } */

    public function make(Request $request)
    {
        try {
            $client = new Client();

            Log::info('Make.', ['res' => $request->all()]);

            $nombres = $request->get('FirstName');
            $apellidos = $request->get('LastName');
            $email = $request->get('Email');
            $telefono = $request->get('Phone');
            /*$fuente = 60; // Fuente asignada como 60*/
            $fuente = 5; // Fuente Facebook Ads
            $dni = $telefono ? substr($telefono, -8) : null;
            $Carrera = null;

            // Condiciones de los nuevos FormId
            if ($request->get('FormId') == "120213979311500623") { // 2025
                $Carrera = Carrera::where('id', 1)->first();
            } else if ($request->get('FormId') == "120213979254620623") { // 2025
                $Carrera = Carrera::where('id', 1)->first();
            } else if ($request->get('FormId') == "120213978723190623") { // 2025
                $Carrera = Carrera::where('id', 1)->first();
            } else if ($request->get('FormId') == "1138904161269308") { // 2025 Fisioterapia
                $Carrera = Carrera::where('id', 3)->first();
            } else if ($request->get('FormId') == "572167608758840") { // 2025 Laboratorio
                $Carrera = Carrera::where('id', 4)->first();
            } else if ($request->get('FormId') == "465117063360591") { // 2025 Enfermeria
                $Carrera = Carrera::where('id', 1)->first();
            } else if ($request->get('FormId') == "570038382409604") { // 2025 Protesis Dental
                $Carrera = Carrera::where('id', 5)->first();
            } else if ($request->get('FormId') == "959142959418089") { // 2025 Farmasia
                $Carrera = Carrera::where('id', 2)->first();
            } else if ($request->get('FormId') == "1465473047742411") { // 2025 Curso TRATAMIENTO FACIAL
                $Carrera = Carrera::where('id', 42)->first();
            } else if ($request->get('FormId') == "8878121772298886") { // 2025 Curso PRIMEROS AUXILIOS
                $Carrera = Carrera::where('id', 8)->first();
            } else if ($request->get('FormId') == "918426343833149") { // 2025 Curso NUTRICIÓN DEPORTIVA
                $Carrera = Carrera::where('id', 40)->first();
            } else if ($request->get('FormId') == "560066173700027") { // 2025 Curso MASOTERAPIA
                $Carrera = Carrera::where('id', 51)->first();
            } else if ($request->get('FormId') == "3730661810580159") { // 2025 Curso MARKETING FARMACÉUTICO
                $Carrera = Carrera::where('id', 29)->first();
            } else if ($request->get('FormId') == "1200554285017951") { // 2025 Curso Inyectables
                $Carrera = Carrera::where('id', 37)->first();
            } else if ($request->get('FormId') == "2079222755851344") { // 2025 Curso SEMI PRESENCIAL CIRUGIA MENOR Y SUTURAS
                $Carrera = Carrera::where('id', 26)->first();
            }

            if ($Carrera) {
                $client->request('POST', 'https://easycrm.ial.edu.pe/api/cliente/create', [
                    RequestOptions::HEADERS => [
                        'Accept' => "application/json",
                        'Authorization' => "Bearer ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO",
                        'Cache-Control' => "no-cache",
                    ],
                    RequestOptions::JSON => [
                        "nombres" => $nombres,
                        "apellidos" => $apellidos,
                        "dni" => $dni,
                        "celular" => $telefono,
                        "email" => $email,
                        "fecha_nacimiento" => date("Y-m-d H:i:s"),
                        "provincia" => 0,
                        "provincia_id" => 1,
                        "distrito_id" => 1,
                        "modalidad_id" => $Carrera->modalidad_id,
                        "carrera_id" => $Carrera->id,
                        "fuente_id" => $fuente,
                        "enterado_id" => 1
                    ]
                ]);

                Log::info('Make.', [
                    'Status' => "Success",
                    'fuente' => $fuente,
                    'carrera' => $Carrera->name,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'telefono' => $telefono,
                    'dni' => $dni
                ]);
            } else {
                Log::info('Make.', [
                    'Status' => "Error",
                    'fuente' => $fuente,
                    'carrera' => 'No asignada',
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'telefono' => $telefono,
                    'dni' => $dni
                ]);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            Log::info('User failed Make.', ['id' => $message]);
        }

        Log::info('User Make.', ['id' => 'Success']);

        return response()->json($request->all());
    }

    public function addCustomerEvents(Request $request)
    {
        try {
            DB::table('customers_events')->insert([
                'event_id' => $request->eventId,
                'carrera_id' => $request->carrera_id,
                'name' => $request->nom,
                'surnames' => $request->ape,
                'email' => $request->maix,
                'phone' => $request->tel,
                'dni' => $request->dni,
                'fuente_id' => $request->fuente_id,
                'created_at' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Registro guardado correctamente'], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el registro',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getCustomerEvents()
    {
        $customerEventData = DB::table('customers_events')
            ->join('events', 'customers_events.event_id', '=', 'events.id')
            ->join('carreras', 'customers_events.carrera_id', '=', 'carreras.id')
            ->join('fuentes', 'customers_events.fuente_id', '=', 'fuentes.id')
            ->select(
                'customers_events.name as nameCustomer',
                'customers_events.surnames as surnamesCustomer',
                'events.name as nameEvent',
                'customers_events.email as emailCustomer',
                'carreras.name as nameCarrera',
                'customers_events.phone as phoneCustomer',
                'customers_events.dni as dniCustomer',
                'fuentes.name as nameFuente'
            )
            ->get();
        return response()->json($customerEventData);
    }
    public function getClientRegistered(Request $request)
    {
        $startDate = Carbon::parse($request->start)->startOfDay();
        $endDate = Carbon::parse($request->end)->endOfDay();
        $clientData = DB::table('clientes')
            ->join('tipo_operacions', 'clientes.tipo_operacion_id', '=', 'tipo_operacions.id')
            ->join('turnos', 'clientes.turno_id', '=', 'turnos.id')
            ->join('sedes', 'clientes.sede_id', '=', 'sedes.id')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->leftJoin('modalidads', 'clientes.modalidad_id', '=', 'modalidads.id')
            ->join('carreras', 'clientes.carrera_id', '=', 'carreras.id')
            ->select(
                'clientes.id as idUnico',
                'tipo_operacions.name as nameOperation',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'clientes.nombres as nameClient',
                'clientes.apellido_paterno as paternalSurname',
                'clientes.apellido_materno as maternalSurname',
                'clientes.fecha_nacimiento as dateOfBirth',
                'clientes.celular as phoneClient',
                'clientes.whatsapp as whatsappClient',
                'clientes.email as emailClient',
                'clientes.direccion as addressClient',
                'turnos.name as shiftClient',
                'sedes.name as sedeClient',
                'clientes.nro_operacion as nrOperationClient',
                'clientes.monto as montoClient',
                'clientes.nombre_titular as nameTitularClient',
                'clientes.codigo_alumno as codeAlumnClient',
                'clientes.created_at as createdAtClient',
                'clientes.updated_at as updatedAtClient',
                DB::raw('CASE WHEN clientes.mayor = 1 THEN "Sí" ELSE "No" END as mayor'),
                DB::raw('CASE 
                WHEN clientes.modalidad_pago = 1 THEN "Presencial" 
                WHEN clientes.modalidad_pago = 2 THEN "Virtual" 
                ELSE "Desconocido" 
                END as modalidadPago'),
                DB::raw('CONCAT(users.last_name, " ", users.name) as usersAsesor'),
                'modalidads.name as nameModalidad',
                'carreras.name as nameCarrera',
                'clientes.ultimo_contacto as ultimoContacto'
            )
            ->where('clientes.estado_id', 4)
            ->where('clientes.estado_detalle_id', 8)
            ->whereNull('clientes.deleted_at')
            ->whereBetween('clientes.ultimo_contacto', [$startDate, $endDate])
            ->orderBy('clientes.ultimo_contacto', 'asc')
            ->get();
        return response()->json([
            'count' => $clientData->count(),
            'data' => $clientData
        ]);
    }
    public function getClientFollowUp(Request $request)
    {
        $clientImgData = DB::table('client_registration_images')->where('id_client', $request->id)->first();
        return response()->json([
            'data' => $clientImgData
        ]);
    }
    public function getApellidos()
    {
        try {
            $updatedIds = [];

            DB::table('clientes')
                ->whereNull('apellido_paterno')
                ->whereNull('apellido_materno')
                ->where('estado_detalle_id', 8)
                ->whereBetween('ultimo_contacto', ['2025-01-01 00:00:00', '2025-03-14 23:59:59'])
                //->whereBetween('created_at', ['2024-03-18 00:00:00', '2024-03-18 23:59:59'])
                ->whereNull('deleted_at')
                ->orderBy('id') // Agregar orden para evitar el error
                ->chunk(500, function ($clientes) use (&$updatedIds) {
                    foreach ($clientes as $cliente) {
                        if (!empty(trim($cliente->apellidos))) {
                            $nombres = explode(' ', trim($cliente->apellidos));

                            $apellido_paterno = $nombres[0] ?? null;
                            $apellido_materno = $nombres[1] ?? null;

                            DB::table('clientes')
                                ->where('id', $cliente->id)
                                ->update([
                                    'apellido_paterno' => $apellido_paterno,
                                    'apellido_materno' => $apellido_materno,
                                    'updated_at' => now()
                                ]);

                            $updatedIds[] = $cliente->id;
                        }
                    }
                });

            return response()->json(['message' => 'Proceso completado correctamente', 'updated_ids' => $updatedIds], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //System Cash
    public function getActiveAdvisors()
    {
        $advisorsData = DB::table('users')->where('profile_id', 2)->where('activo', 1)->whereNull('deleted_at')->get();
        return response()->json([
            'data' => $advisorsData
        ]);
    }
    public function sendingRegistrations(Request $request)
    {
        $startDateComplete = $request->startDate . " 00:00:00";
        $endDateComplete = $request->endDate . " 23:59:59";

        // Subquery para obtener el último seguimiento válido
        $latestSeguimiento = DB::table('cliente_seguimientos as cs1')
            ->select('cs1.cliente_id', 'cs1.comentario')
            ->where('cs1.estado_id', 4)
            ->where('cs1.estado_detalle_id', 8)
            ->whereNull('cs1.deleted_at')
            ->whereRaw('cs1.id = (
            SELECT cs2.id FROM cliente_seguimientos cs2
            WHERE cs2.cliente_id = cs1.cliente_id
            AND cs2.estado_id = 4
            AND cs2.estado_detalle_id = 8
            ORDER BY cs2.created_at DESC LIMIT 1
        )');

        // Consulta principal
        $query = DB::table('clientes')
            ->join('distritos', 'clientes.distrito_id', '=', 'distritos.id')
            ->leftJoin('modalidads', 'clientes.modalidad_id', '=', 'modalidads.id')
            ->leftJoin('carreras', 'clientes.carrera_id', '=', 'carreras.id')
            ->joinSub($latestSeguimiento, 'ultimo_seguimiento', function ($join) {
                $join->on('clientes.id', '=', 'ultimo_seguimiento.cliente_id');
            })
            ->select(
                'clientes.id as idUnico',
                'clientes.ultimo_contacto as endContact',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'distritos.name as districtClient',
                'modalidads.name as nameModalidad',
                'carreras.name as nameCarrera',
                'ultimo_seguimiento.comentario as comentario',
                DB::raw('CASE 
                    WHEN clientes.modalidad_pago = 1 THEN "Presencial" 
                    WHEN clientes.modalidad_pago = 2 THEN "Virtual" 
                    ELSE "Desconocido" 
                    END as modalidadPago'),
            )
            ->where('clientes.estado_id', 4)
            ->where('clientes.estado_detalle_id', 8)
            ->whereNull('clientes.deleted_at')
            ->whereBetween('clientes.ultimo_contacto', [$startDateComplete, $endDateComplete]);
        // Filtro por cliente
        if (!empty($request->dataClient)) {
            $query->where(function ($q) use ($request) {
                $q->where('clientes.nombres', $request->dataClient)
                    ->orWhere('clientes.apellidos', 'LIKE', '%' . $request->dataClient . '%')
                    ->orWhere('clientes.dni', $request->dataClient);
            });
        }

        // Filtro por asesor
        if ($request->advisor !== "all") {
            $query->where('clientes.user_id', $request->advisor);
        }

        $clientData = $query->orderBy('clientes.ultimo_contacto', 'asc')->get();

        return response()->json([
            'data' => $clientData
        ]);
    }
    public function registeredCustomerData(Request $request)
    {
        $clientData = DB::table('clientes')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->join('provincias', 'clientes.provincia_id', '=', 'provincias.id')
            ->join('distritos', 'clientes.distrito_id', '=', 'distritos.id')
            ->leftJoin('client_registration_images', 'clientes.id', '=', 'client_registration_images.id_client')
            ->join('tipo_operacions', 'clientes.tipo_operacion_id', '=', 'tipo_operacions.id')
            ->join('carreras', 'clientes.carrera_id', '=', 'carreras.id')
            ->join('modalidads', 'clientes.modalidad_id', '=', 'modalidads.id')
            ->join('turnos', 'clientes.turno_id', '=', 'turnos.id')
            ->join('locals', 'clientes.local_id', '=', 'locals.id')
            ->select(
                'clientes.id as idUnico',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'clientes.fecha_nacimiento as dateOfBirth',
                'clientes.email as emailClient',
                'clientes.direccion as addressClient',
                'provincias.name as nameProvincia',
                'distritos.name as nameDistrito',
                'clientes.celular as phoneClient',
                'carreras.name as nameCarreras',
                'clientes.code_waiver as renunciaCodigo',
                'clientes.codigo_alumno as codigoAlumno',
                'modalidads.name as nameModalidad',
                'turnos.name as nameTurno',
                'locals.name as nameLocal',
                'clientes.nombre_titular as nameTitular',
                'clientes.completo as pagoCompleto',
                'client_registration_images.dni_front as dniFront',
                'client_registration_images.dni_rear as dniRear',
                'client_registration_images.code_waiver as codeWaiver',
                'client_registration_images.izy_pay as izyPay',
                'client_registration_images.vaucher as vaucher',
                'client_registration_images.additional_voucher as additionalVaucher',
                'client_registration_images.school_name as schoolName',
                'client_registration_images.completion_date as completionDate',
                DB::raw('CONCAT(users.last_name, " ", users.name) as usersAsesor'),
                'users.id as idAdvisor',
                'tipo_operacions.name as nameOperation',
                DB::raw('CASE WHEN clientes.mayor = 1 THEN "Sí" ELSE "No" END as mayor'),
            )
            ->where('clientes.estado_id', 4)
            ->where('clientes.estado_detalle_id', 8)
            ->whereNull('clientes.deleted_at')
            ->where('clientes.id', $request->idClient)
            ->first();
        return response()->json([
            'data' => $clientData
        ]);
    }
    public function sendCashierNotification(Request $request)
    {
        try {
            DB::beginTransaction();
            $seguimientoData = DB::table('cliente_seguimientos')
                ->select('cliente_seguimientos.id as idSeguimiento')
                ->where('cliente_id', $request->id_lead)
                ->where('estado_id', 4)
                ->where('estado_detalle_id', 8)
                ->whereNull('deleted_at')
                ->first();
            if ($seguimientoData) {
                DB::table('notifications')
                    ->where('cliente_id', $request->id_lead)
                    ->where('estado', 0)
                    ->whereNull('deleted_at')
                    ->update([
                        'estado' => 1,
                        'updated_at' => Carbon::now()
                    ]);
                DB::table('notifications')->insert([
                    'cliente_id' => $request->id_lead,
                    'cliente_seguimiento_id' => $seguimientoData->idSeguimiento,
                    'estado' => 0,
                    'created_at' => Carbon::now(),
                    'user_id' => $request->idAdvisor,
                    'box_tracking' => $request->typeNotification,
                ]);
            }
            DB::commit();
            return response()->json([
                'state' => 200,
                'message' => 'Notificación registrada correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'state' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }
    //Adicional
    public function sendingRegistrationsAdditional(Request $request)
    {
        $startDateComplete = $request->startDate . " 00:00:00";
        $endDateComplete = $request->endDate . " 23:59:59";
        // Consulta principal
        $query = DB::table('cliente_matriculas')
            ->join('clientes', 'cliente_matriculas.cliente_id', '=', 'clientes.id')
            ->join('distritos', 'clientes.distrito_id', '=', 'distritos.id')
            ->leftJoin('modalidads', 'cliente_matriculas.modalidad_adicional_id', '=', 'modalidads.id')
            ->leftJoin('carreras', 'cliente_matriculas.carrera_adicional_id', '=', 'carreras.id')
            ->select(
                'cliente_matriculas.id as idUnico',
                'cliente_matriculas.created_at as endContact',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'distritos.name as districtClient',
                'modalidads.name as nameModalidad',
                'carreras.name as nameCarrera',
                'cliente_matriculas.observacion_adicional as observacionAdicional',
                DB::raw('CASE 
                    WHEN cliente_matriculas.modalidad_pago_adicional = 1 THEN "Presencial" 
                    WHEN cliente_matriculas.modalidad_pago_adicional = 2 THEN "Virtual" 
                    ELSE "Desconocido" 
                    END as modalidadPago'),
            )
            ->whereNull('cliente_matriculas.deleted_at')
            ->whereNull('clientes.deleted_at')
            ->whereBetween('cliente_matriculas.created_at', [$startDateComplete, $endDateComplete]);
        // Filtro por cliente
        if (!empty($request->dataClient)) {
            $query->where(function ($q) use ($request) {
                $q->where('clientes.nombres', $request->dataClient)
                    ->orWhere('clientes.apellidos', 'LIKE', '%' . $request->dataClient . '%')
                    ->orWhere('clientes.dni', $request->dataClient);
            });
        }
        // Filtro por asesor
        if ($request->advisor !== "all") {
            $query->where('clientes.user_id', $request->advisor);
        }
        $clientData = $query->orderBy('cliente_matriculas.created_at', 'asc')->get();
        return response()->json([
            'data' => $clientData
        ]);
    }
    public function registeredCustomerDataAdditional(Request $request)
    {
        $clientData = DB::table('cliente_matriculas')
            ->join('clientes', 'cliente_matriculas.cliente_id', '=', 'clientes.id')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->join('provincias', 'clientes.provincia_id', '=', 'provincias.id')
            ->join('distritos', 'clientes.distrito_id', '=', 'distritos.id')
            ->leftJoin('client_registration_images_additional', 'cliente_matriculas.id', '=', 'client_registration_images_additional.id_client_additional')
            ->join('tipo_operacions', 'cliente_matriculas.tipo_operacion_adicional_id', '=', 'tipo_operacions.id')
            ->join('carreras', 'cliente_matriculas.carrera_adicional_id', '=', 'carreras.id')
            ->join('modalidads', 'cliente_matriculas.modalidad_adicional_id', '=', 'modalidads.id')
            ->join('turnos', 'cliente_matriculas.turno_adicional_id', '=', 'turnos.id')
            ->join('locals', 'cliente_matriculas.local_adicional_id', '=', 'locals.id')
            ->select(
                'cliente_matriculas.id as idUnico',
                'clientes.id as idClient',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'clientes.fecha_nacimiento as dateOfBirth',
                'clientes.email as emailClient',
                'clientes.direccion as addressClient',
                'provincias.name as nameProvincia',
                'distritos.name as nameDistrito',
                'clientes.celular as phoneClient',
                'carreras.name as nameCarreras',
                'cliente_matriculas.codigo_alumno_adicional as codigoAlumno',
                'modalidads.name as nameModalidad',
                'turnos.name as nameTurno',
                'locals.name as nameLocal',
                'cliente_matriculas.nombre_titular_adicional as nameTitular',
                'client_registration_images_additional.dni_front_additional as dniFront',
                'client_registration_images_additional.dni_rear_additional as dniRear',
                'client_registration_images_additional.izy_pay_additional as izyPay',
                'client_registration_images_additional.vaucher_additional as vaucher',
                'client_registration_images_additional.school_name_additional as schoolName',
                'client_registration_images_additional.completion_date_additional as completionDate',
                DB::raw('CONCAT(users.last_name, " ", users.name) as usersAsesor'),
                'users.id as idAdvisor',
                'tipo_operacions.name as nameOperation',
                DB::raw('CASE WHEN cliente_matriculas.mayor_adicional = 1 THEN "Sí" ELSE "No" END as mayor'),
            )
            ->whereNull('cliente_matriculas.deleted_at')
            ->where('cliente_matriculas.id', $request->idAdditionalRegistration)
            ->first();
        return response()->json([
            'data' => $clientData
        ]);
    }
    //
    //Prueba Alisson
    public function enrolledCurrentYear()
    {
        $year = Carbon::now()->year;
        $startDate = Carbon::parse($year . "01-01")->startOfDay();
        $endDate = Carbon::parse($year . "12-31")->endOfDay();
        $clientData = DB::table('clientes')
            ->join('tipo_operacions', 'clientes.tipo_operacion_id', '=', 'tipo_operacions.id')
            ->join('turnos', 'clientes.turno_id', '=', 'turnos.id')
            ->join('sedes', 'clientes.sede_id', '=', 'sedes.id')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->leftJoin('modalidads', 'clientes.modalidad_id', '=', 'modalidads.id')
            ->join('carreras', 'clientes.carrera_id', '=', 'carreras.id')
            ->select(
                'clientes.id as idUnico',
                'tipo_operacions.name as nameOperation',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'clientes.nombres as nameClient',
                'clientes.apellido_paterno as paternalSurname',
                'clientes.apellido_materno as maternalSurname',
                'clientes.fecha_nacimiento as dateOfBirth',
                'clientes.celular as phoneClient',
                'clientes.whatsapp as whatsappClient',
                'clientes.email as emailClient',
                'clientes.direccion as addressClient',
                'turnos.name as shiftClient',
                'sedes.name as sedeClient',
                'clientes.nro_operacion as nrOperationClient',
                'clientes.monto as montoClient',
                'clientes.nombre_titular as nameTitularClient',
                'clientes.codigo_alumno as codeAlumnClient',
                'clientes.created_at as createdAtClient',
                'clientes.updated_at as updatedAtClient',
                DB::raw('CASE WHEN clientes.mayor = 1 THEN "Sí" ELSE "No" END as mayor'),
                DB::raw('CASE 
                WHEN clientes.modalidad_pago = 1 THEN "Presencial" 
                WHEN clientes.modalidad_pago = 2 THEN "Virtual" 
                ELSE "Desconocido" 
                END as modalidadPago'),
                DB::raw('CONCAT(users.last_name, " ", users.name) as usersAsesor'),
                'modalidads.name as nameModalidad',
                'carreras.name as nameCarrera',
                'clientes.ultimo_contacto as ultimoContacto'
            )
            ->where('clientes.estado_id', 4)
            ->where('clientes.estado_detalle_id', 8)
            ->whereNull('clientes.deleted_at')
            ->whereBetween('clientes.ultimo_contacto', [$startDate, $endDate])
            ->orderBy('clientes.ultimo_contacto', 'asc')
            ->get();
        $clientTuitionData = DB::table('cliente_matriculas')
            ->join('clientes', 'cliente_matriculas.cliente_id', '=', 'clientes.id')
            ->join('tipo_operacions', 'clientes.tipo_operacion_id', '=', 'tipo_operacions.id')
            ->join('turnos', 'clientes.turno_id', '=', 'turnos.id')
            ->join('sedes', 'clientes.sede_id', '=', 'sedes.id')
            ->join('users', 'clientes.user_id', '=', 'users.id')
            ->leftJoin('modalidads', 'clientes.modalidad_id', '=', 'modalidads.id')
            ->join('carreras', 'clientes.carrera_id', '=', 'carreras.id')
            ->select(
                'clientes.id as idUnico',
                'tipo_operacions.name as nameOperation',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'clientes.nombres as nameClient',
                'clientes.apellido_paterno as paternalSurname',
                'clientes.apellido_materno as maternalSurname',
                'clientes.fecha_nacimiento as dateOfBirth',
                'clientes.celular as phoneClient',
                'clientes.whatsapp as whatsappClient',
                'clientes.email as emailClient',
                'clientes.direccion as addressClient',
                'turnos.name as shiftClient',
                'sedes.name as sedeClient',
                'clientes.nro_operacion as nrOperationClient',
                'clientes.monto as montoClient',
                'clientes.nombre_titular as nameTitularClient',
                'clientes.codigo_alumno as codeAlumnClient',
                'clientes.created_at as createdAtClient',
                'clientes.updated_at as updatedAtClient',
                DB::raw('CASE WHEN clientes.mayor = 1 THEN "Sí" ELSE "No" END as mayor'),
                DB::raw('CASE 
            WHEN clientes.modalidad_pago = 1 THEN "Presencial" 
            WHEN clientes.modalidad_pago = 2 THEN "Virtual" 
            ELSE "Desconocido" 
            END as modalidadPago'),
                DB::raw('CONCAT(users.last_name, " ", users.name) as usersAsesor'),
                'modalidads.name as nameModalidad',
                'carreras.name as nameCarrera',
                'clientes.ultimo_contacto as ultimoContacto'
            )
            ->whereNull('cliente_matriculas.deleted_at')
            ->whereBetween('cliente_matriculas.created_at', [$startDate, $endDate])
            ->orderBy('cliente_matriculas.created_at', 'asc')
            ->get();
        $clientData = $clientData->merge($clientTuitionData);
        return response()->json([
            'count' => $clientData->count(),
            'data' => $clientData
        ]);
    }
    public function listOfErrorsInSurnames(Request $request)
    {
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);

        Cliente::where('estado_id', 4)
            ->where('estado_detalle_id', 8)
            ->where('mayor', 1)
            ->whereNull('deleted_at')
            ->whereBetween('ultimo_contacto', [$startDate, $endDate])
            ->chunk(50, function ($clientes) use ($startDate, $endDate) {
                foreach ($clientes as $cliente) {
                    $url = "https://my.apidev.pro/api/dni/" . $cliente->dni . "?api_token=3fcaa8c48f59ff6ee58afff70a360af5fdcc214f512128165cdc050da28ee770";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    if ($response) {
                        $responseData = json_decode($response, true);
                        if (isset($responseData['data']['apellido_paterno']) && isset($responseData['data']['apellido_materno'])) {
                            $apellidosApi = trim($responseData['data']['apellido_paterno'] . ' ' . $responseData['data']['apellido_materno']);
                            $apellidosLocal = trim($cliente->apellidos);
                            if (strtolower($apellidosApi) !== strtolower($apellidosLocal)) {
                                DB::table('apellidos_errores')->insert([
                                    'dni'            => $cliente->dni,
                                    'apellidos_bd'   => $apellidosLocal,
                                    'apellidos_api'  => $apellidosApi,
                                    'fecha_inicio'   => $startDate,
                                    'fecha_final'    => $endDate
                                ]);
                            }
                        }
                    }
                }

                // Pausa de 1 segundo después de cada bloque
                sleep(1);
            });

        return response()->json(['message' => 'Proceso finalizado.']);
    }
    //Matriculas aprobadas
    public function getLeadsApproved(Request $request)
    {
        $clientData = DB::table('clientes')
            ->join('distritos', 'clientes.distrito_id', '=', 'distritos.id')
            ->join('carreras', 'clientes.carrera_id', '=', 'carreras.id')
            ->join('modalidads', 'clientes.modalidad_id', '=', 'modalidads.id')
            ->select(
                'clientes.id as idUnico',
                'clientes.ultimo_contacto as endContact',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'distritos.name as districtClient',
                'modalidads.name as nameModalidad',
                'carreras.name as nameCarrera',
                DB::raw('CASE 
                    WHEN clientes.modalidad_pago = 1 THEN "Presencial" 
                    WHEN clientes.modalidad_pago = 2 THEN "Virtual" 
                    ELSE "Desconocido" 
                    END as modalidadPago'),
            )
            ->where('clientes.estado_id', 4)
            ->where('clientes.estado_detalle_id', 8)
            ->whereNull('clientes.deleted_at')
            ->whereIn('clientes.id', $request->idsLeads)
            ->get();
        return response()->json([
            'data' => $clientData
        ]);
    }
    public function getLeadsAdditionalsApproved(Request $request)
    {
        $clientData = DB::table('cliente_matriculas')
            ->join('clientes', 'cliente_matriculas.cliente_id', '=', 'clientes.id')
            ->join('carreras', 'cliente_matriculas.carrera_adicional_id', '=', 'carreras.id')
            ->join('modalidads', 'cliente_matriculas.modalidad_adicional_id', '=', 'modalidads.id')
            ->select(
                'cliente_matriculas.id as idUnico',
                'clientes.ultimo_contacto as endContact',
                'clientes.dni as dniClient',
                DB::raw('CONCAT(clientes.apellidos, " ", clientes.nombres) as nameComplete'),
                'modalidads.name as nameModalidad',
                'carreras.name as nameCarrera',
                DB::raw('CASE 
                    WHEN clientes.modalidad_pago = 1 THEN "Presencial" 
                    WHEN clientes.modalidad_pago = 2 THEN "Virtual" 
                    ELSE "Desconocido" 
                    END as modalidadPago'),
            )
            ->whereNull('clientes.deleted_at')
            ->whereIn('cliente_matriculas.id', $request->idsLeadsAdditionals)
            ->get();
        return response()->json([
            'data' => $clientData
        ]);
    }
    public function updateRegistration(Request $request)
    {
        try {
            $updateLead = Cliente::where('id', $request->id_lead)->first();
            dd($updateLead);
            $updateLead = Cliente::where('id', $request->id_lead)->update([
                'lead_approved' => 1,
            ]);
            if ($updateLead) {
                return response()->json([
                    'state' => 200,
                    'message' => 'Actualización exitosa.',
                ]);
            } else {
                return response()->json([
                    'state' => 404,
                    'message' => 'Cliente no encontrado o no se realizó ninguna actualización.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'state' => 500,
                'message' => 'Error al actualizar: ' . $e->getMessage(),
            ]);
        }
    }
    public function updateRegistrationAdditional(Request $request)
    {
        try {
            $updateLead = ClienteMatricula::where('id', $request->id_lead_additional)->update([
                'lead_additional_approved' => 1,
            ]);
            if ($updateLead) {
                return response()->json([
                    'state' => 200,
                    'message' => 'Actualización exitosa.',
                ]);
            } else {
                return response()->json([
                    'state' => 404,
                    'message' => 'Cliente no encontrado o no se realizó ninguna actualización.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'state' => 500,
                'message' => 'Error al actualizar: ' . $e->getMessage(),
            ]);
        }
    }
}
