<?php

namespace easyCRM\Http\Controllers\App;

use easyCRM\Carrera;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

            $response = $client->request('GET', 'https://graph.facebook.com/'.$leadgen_id,
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

            $response_carrera = $client->request('GET', 'https://graph.facebook.com/'.$form_id,
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

            if($result)
            {
                $data = $result->field_data;
                $nombres = null; $apellidos = null; $email =null; $telefono = null; $dni = null;

                if($data[0] != null && ($data[0]->name == "nombre" || $data[0]->name == "first_name")){
                    $nombres = $data[0]->values[0];
                }else if($data[1] != null && ($data[1]->name == "nombre" || $data[1]->name == "first_name")){
                    $nombres = $data[1]->values[0];
                }else if($data[2] != null && ($data[2]->name == "nombre" || $data[2]->name == "first_name")) {
                    $nombres = $data[2]->values[0];
                }else if($data[3] != null && ($data[3]->name == "nombre" || $data[3]->name == "first_name")){
                    $nombres = $data[3]->values[0];
                }

                if($data[0] != null && ($data[0]->name == "apellido" || $data[0]->name == "last_name")){
                    $apellidos = $data[0]->values[0];
                }else if($data[1] != null && ($data[1]->name == "apellido" || $data[1]->name == "last_name")){
                    $apellidos = $data[1]->values[0];
                }else if($data[2] != null && ($data[2]->name == "apellido" || $data[2]->name == "last_name")) {
                    $apellidos = $data[2]->values[0];
                }else if($data[3] != null && ($data[3]->name == "apellido" || $data[3]->name == "last_name")){
                    $apellidos = $data[3]->values[0];
                }

                if($data[0] != null && $data[0]->name == "email"){
                    $email = $data[0]->values[0];
                }else if($data[1] != null && $data[1]->name == "email"){
                    $email = $data[1]->values[0];
                }else if($data[2] != null && $data[2]->name == "email") {
                    $email = $data[2]->values[0];
                }else if($data[3] != null && $data[3]->name == "email"){
                    $email = $data[3]->values[0];
                }

                if($data[0] != null && $data[0]->name == "phone_number"){
                    $telefono = $data[0]->values[0];
                }else if($data[1] != null && $data[1]->name == "phone_number"){
                    $telefono = $data[1]->values[0];
                }else if($data[2] != null && $data[2]->name == "phone_number") {
                    $telefono = $data[2]->values[0];
                }else if($data[3] != null && $data[3]->name == "phone_number"){
                    $telefono = $data[3]->values[0];
                }

                if($telefono) {
                    $telefono = str_replace("+51", "",$telefono);
                    $dni = substr($telefono, -8);
                }

                $Carrera = Carrera::where('alias', $result_carrera->name)->first();

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
                            "fuente_id" => 5,
                            "enterado_id" => 1
                        ]
                    ]
                );

                //Log::info('User access.', ['result' => $nombres." "$apellidos]);
            }

        }catch (\Exception $e)
        {
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

            $response = $client->request('GET', 'https://graph.facebook.com/'.$leadgen_id,
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

            $response_carrera = $client->request('GET', 'https://graph.facebook.com/'.$form_id,
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

            if($result)
            {
                $data = $result->field_data;
                $nombres = null; $apellidos = null; $email =null; $telefono = null; $dni = null;

                if($data[0] != null && ($data[0]->name == "nombre" || $data[0]->name == "first_name")){
                    $nombres = $data[0]->values[0];
                }else if($data[1] != null && ($data[1]->name == "nombre" || $data[1]->name == "first_name")){
                    $nombres = $data[1]->values[0];
                }else if($data[2] != null && ($data[2]->name == "nombre" || $data[2]->name == "first_name")) {
                    $nombres = $data[2]->values[0];
                }else if($data[3] != null && ($data[3]->name == "nombre" || $data[3]->name == "first_name")){
                    $nombres = $data[3]->values[0];
                }

                if($data[0] != null && ($data[0]->name == "apellido" || $data[0]->name == "last_name")){
                    $apellidos = $data[0]->values[0];
                }else if($data[1] != null && ($data[1]->name == "apellido" || $data[1]->name == "last_name")){
                    $apellidos = $data[1]->values[0];
                }else if($data[2] != null && ($data[2]->name == "apellido" || $data[2]->name == "last_name")) {
                    $apellidos = $data[2]->values[0];
                }else if($data[3] != null && ($data[3]->name == "apellido" || $data[3]->name == "last_name")){
                    $apellidos = $data[3]->values[0];
                }

                if($data[0] != null && $data[0]->name == "email"){
                    $email = $data[0]->values[0];
                }else if($data[1] != null && $data[1]->name == "email"){
                    $email = $data[1]->values[0];
                }else if($data[2] != null && $data[2]->name == "email") {
                    $email = $data[2]->values[0];
                }else if($data[3] != null && $data[3]->name == "email"){
                    $email = $data[3]->values[0];
                }

                if($data[0] != null && $data[0]->name == "phone_number"){
                    $telefono = $data[0]->values[0];
                }else if($data[1] != null && $data[1]->name == "phone_number"){
                    $telefono = $data[1]->values[0];
                }else if($data[2] != null && $data[2]->name == "phone_number") {
                    $telefono = $data[2]->values[0];
                }else if($data[3] != null && $data[3]->name == "phone_number"){
                    $telefono = $data[3]->values[0];
                }

                if($telefono) {
                    $telefono = str_replace("+51", "",$telefono);
                    $dni = substr($telefono, -8);
                }

                $Carrera = Carrera::where('alias', $result_carrera->name)->first();

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
                            "fuente_id" => 5,
                            "enterado_id" => 1
                        ]
                    ]
                );

                //Log::info('User access.', ['result' => $nombres." "$apellidos]);
            }

        }catch (\Exception $e)
        {
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

            if($telefono) {
                $dni = substr($telefono, -8);
            }

            $Carrera = Carrera::where('alias', 'Form ENFE')->first();

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
                    'dni' => $dni]);

        }catch (\Exception $e)
        {
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
        if($telefono) {
            $dni = substr($telefono, -8);
        }
        $Carrera = Carrera::where('id', 5)->first();
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

            if($telefono) {
                $dni = substr($telefono, -8);
            }

            if($request->get('FormId') == "7354427944133050640"){ // API FUENTE TIKTOK ADS
                $Carrera = Carrera::where('id', 5)->first();
                $fuente = 34;
            }else{
                $fuente = 34;
                $Carrera = Carrera::where('id', 5)->first(); 
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
                    'Status' => "Success make tiktokads",
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
                    'Status' => "Error make tiktokads",
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
    }

    public function make(Request $request)
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
                /* $Carrera = Carrera::where('id', 5)->first(); */
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
            else if($request->get('FormId') == "120213979311500623"){ // ENFERMERIA SIMILAR
                $fuente = 60;
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
    }

}
