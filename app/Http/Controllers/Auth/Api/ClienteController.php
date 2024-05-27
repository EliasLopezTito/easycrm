<?php

namespace easyCRM\Http\Controllers\Auth\Api;

use Carbon\Carbon;
use easyCRM\App;
use easyCRM\Cliente;
use easyCRM\HistorialReasignar;
use easyCRM\Turno;
use easyCRM\User;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use easyCRM\Traits\Consultas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    use Consultas;

    public function create(Request $request)
    {
        $status = false;
        $user = null;
        $register = false;
        $update = false;
        $duplicado = false;
        $reintento = false;

        try {

            DB::beginTransaction();

            $assessor = $this->getAssessorWithMinimumAssignedLeads();

            /*if($request->provincia != null && $request->provincia == 1)
            $user = DB::table('users')->select('id')->where('id', App::$USUARIO_PROVINCIA)->first();
        else{*/

            /* $user = DB::table('users')->select('id')->where('profile_id', App::$PERFIL_VENDEDOR)
                ->where('id', '!=', App::$USUARIO_PROVINCIA)->where('turno', true)
                ->where('recibe_lead', true)->first(); */

            //}
            /* ANTIGUOS ASESORES CONVOCADOS PARA Q LE LLEGUEN LEADS */
            /* $id_users = collect([9, 78, 10020, 10, 191, 7, 132, 36, 178, 221, 10037, 10040, 10041]); */
            /* $id_users = collect([9, 78, 10020, 10, 191, 7, 132, 36, 221, 10037, 10040, 10041, 10051]); */

            //comentado el 08/03/2024
            /* if($request->carrera_id == 40 || $request->carrera_id == 37 || $request->carrera_id == 35 || $request->carrera_id == 29){
                $id_users = collect([10041, 10030, 10037, 10040, 221, 10028, 9, 10, 7, 132, 78, 191, 10053]);
                $user = $id_users->random();
                $userTurnId = $id_users->random();
            }else{
                $user = $assessor;
                $userTurnId = $user != null ? $user->id : DB::table('users')->select('id')->where('recibe_lead', 1)->where('activo',1)->whereNull('deleted_at')->first()->id;
            } */
            $user = $assessor;
            $userTurnId = $user != null ? $user->id : DB::table('users')->select('id')->where('recibe_lead', 1)->where('activo',1)->whereNull('deleted_at')->first()->id;

            $clienteExist = Cliente::where('dni', $request->dni)
                ->orWhere('email', $request->email)
                ->orWhere('celular', $request->celular)
                ->orderby('created_at', 'desc')->first();

            if ($clienteExist != null) {
                $fecha_inicio_act_camp = Carbon::now()->format('d') >= 16 ? Carbon::now()->firstOfMonth()->addDay(15) : Carbon::now()->subMonth('1')->firstOfMonth()->addDay(15);
                $fecha_final_act_camp = Carbon::now()->format('d') >= 16 ? Carbon::now()->endOfMonth()->addDay(15) : Carbon::now()->subMonth('1')->endOfMonth()->addDay(15);

                /*if(($clienteExist->created_at >= $fecha_inicio_act_camp  && $clienteExist->created_at <= $fecha_final_act_camp))
            {
                if( ($request->modalidad_id == App::$MODALIDAD_CURSO) ||
                    ($request->modalidad_id == App::$MODALIDAD_CARRERA && $clienteExist->modalidad_id == App::$MODALIDAD_CURSO))
                {
                    $Cliente_Cursos = Cliente::where('dni', $request->dni)->orWhere('email', $request->email)->orWhere('celular', $request->celular)
                        ->pluck('carrera_id')->toArray();

                    $Cliente_Carreras = Cliente::where('dni', $request->dni)->orWhere('email', $request->email)->orWhere('celular', $request->celular)
                        ->pluck('modalidad_id')->toArray();

                    if (
                        ($request->modalidad_id == App::$MODALIDAD_CURSO && !in_array($request->carrera_id, $Cliente_Cursos)) ||
                        ($request->modalidad_id == App::$MODALIDAD_CARRERA &&!in_array(App::$MODALIDAD_CARRERA, $Cliente_Carreras))
                    ) {
                        $register = true;
                    }else {
                        $duplicado = true;
                    }

                }else{
                    $duplicado = true;
                }

            }else {*/

                $Cliente_Cursos = Cliente::where('dni', $request->dni)
                    ->orWhere('email', $request->email)
                    ->orWhere('celular', $request->celular)
                    ->pluck('carrera_id')->toArray();

                $Cliente_Carreras = Cliente::where('dni', $request->dni)->orWhere('email', $request->email)->orWhere('celular', $request->celular)
                    ->pluck('modalidad_id')->toArray();

                if (
                    ($request->modalidad_id == App::$MODALIDAD_CURSO && !in_array($request->carrera_id, $Cliente_Cursos)) ||
                    ($request->modalidad_id == App::$MODALIDAD_CARRERA && !in_array(App::$MODALIDAD_CARRERA, $Cliente_Carreras))
                ) {
                    $register = true;
                } else if (!in_array($clienteExist->estado_id, [App::$ESTADO_CIERRE])) {
                    $reintento = true;
                    $update = true;
                } else {
                    $duplicado = true;
                }
                //}
            } else {
                $register = true;
            }

            $request->merge([
                'user_id' =>  $userTurnId,
                'estado_id' => App::$ESTADO_NUEVO,
                'estado_detalle_id' => App::$ESTADO_DETALLE_NUEVO,
                'proviene_id' => App::$LLAMADA,
                'ultimo_contacto' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'reasignado' => false,
                'fuente_id' => $reintento ? App::$FUENTE_REINTENTO : $request->fuente_id,
                'origen_lead' => 'api'
            ]);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'dni' => ['required', 'min:8', 'max:10'],
                'celular' => ['required', 'min:9', 'max:15'],
                'email' => ['required', 'email'],
                'provincia' => 'required',
                'provincia_id' => 'required',
                'distrito_id' => 'required',
                'modalidad_id' => 'required',
                'carrera_id' => 'required',
                'fuente_id' => 'required',
                'enterado_id' => 'required',
                'estado_id' => 'required',
                'estado_detalle_id' => 'required'
            ]);

            if (!$validator->fails()) {
                if ($duplicado) {
                    return response()->json(['Success' => $status, 'Message' => "El cliente ya se encuentra registrado en esta campaña"]);
                }

                if ($clienteExist != null && $update) {
                    $Cliente = Cliente::find($clienteExist->id);
                    $Cliente->estado_id = App::$ESTADO_REINTENTO;
                    $Cliente->estado_detalle_id = App::$ESTADO_DETALLE_REINTENTO;
                    $Cliente->fuente_id = App::$FUENTE_REINTENTO;
                    if ($Cliente->save()) {
                        $status = true;
                    }
                } else if ($register) {

                    $Cliente = Cliente::create($request->all());

                    if ($Cliente && $assessor) {
                        $assessor->assigned_leads += 1;
                        $assessor->save();
                    }

                    //if ($request->provincia != null && $request->provincia == 0) {

                    $usersAllIds = DB::table('users')->whereNull('deleted_at')->where('profile_id', App::$PERFIL_VENDEDOR)
                        ->where('activo', 1)->where('recibe_lead', 1)
                        ->orderBy('id', 'asc')->pluck('id')->toArray();

                    $minId = array_search(min($usersAllIds), $usersAllIds);
                    $maxId = array_search(max($usersAllIds), $usersAllIds);

                    $userTurnId = array_search($userTurnId, $usersAllIds) + 1;

                    if ($userTurnId > $maxId)
                        $userTurnId = $usersAllIds[$minId];
                    else
                        $userTurnId = $usersAllIds[$userTurnId];

                    $user = User::find($userTurnId);
                    $user->turno = true;
                    if ($user->save()) {
                        $status = true;
                        User::where('id', '!=', $user->id)->where('profile_id', App::$PERFIL_VENDEDOR)
                            ->update(['turno' => false]);
                    }

                    /* } else {
                    $status = true;
                }*/

                    if ($status) {
                        $HistorialReasignar = new HistorialReasignar();
                        $HistorialReasignar->cliente_id = $Cliente->id;
                        $HistorialReasignar->user_id = 1;
                        $HistorialReasignar->vendedor_id = $Cliente->user_id;
                        $HistorialReasignar->observacion = "Registro";

                        if ($HistorialReasignar->save()) $status = true;
                    }
                }
            }

            if ($status) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }


    public function create_reingreso(Request $request)
    {
        $status = false;
        $user = null;
        $register = false;
        $update = false;
        $duplicado = false;
        $reintento = false;

        try {

            DB::beginTransaction();

            $user = DB::table('users')->select('id')->where('profile_id', App::$PERFIL_RESTRINGIDO)
                ->where('id', '!=', App::$USUARIO_PROVINCIA)->where('turno', true)
                ->where('recibe_lead', true)->first();

            $userTurnId = $user != null ? $user->id : DB::table('users')->select('id')->first()->id;

            $clienteExist = Cliente::where('dni', $request->dni)
                ->orWhere('email', $request->email)
                ->orWhere('celular', $request->celular)
                ->orderby('created_at', 'desc')->first();

            if ($clienteExist != null) {
                $fecha_inicio_act_camp = Carbon::now()->format('d') >= 16 ? Carbon::now()->firstOfMonth()->addDay(15) : Carbon::now()->subMonth('1')->firstOfMonth()->addDay(15);
                $fecha_final_act_camp = Carbon::now()->format('d') >= 16 ? Carbon::now()->endOfMonth()->addDay(15) : Carbon::now()->subMonth('1')->endOfMonth()->addDay(15);

                if (($clienteExist->created_at >= $fecha_inicio_act_camp  && $clienteExist->created_at <= $fecha_final_act_camp)) {
                    if (($request->modalidad_id == App::$MODALIDAD_CURSO) ||
                        ($request->modalidad_id == App::$MODALIDAD_CARRERA && $clienteExist->modalidad_id == App::$MODALIDAD_CURSO)
                    ) {
                        $Cliente_Cursos = Cliente::where('dni', $request->dni)->orWhere('email', $request->email)->orWhere('celular', $request->celular)
                            ->pluck('carrera_id')->toArray();

                        $Cliente_Carreras = Cliente::where('dni', $request->dni)->orWhere('email', $request->email)->orWhere('celular', $request->celular)
                            ->pluck('modalidad_id')->toArray();

                        if (
                            ($request->modalidad_id == App::$MODALIDAD_CURSO && !in_array($request->carrera_id, $Cliente_Cursos)) ||
                            ($request->modalidad_id == App::$MODALIDAD_CARRERA && !in_array(App::$MODALIDAD_CARRERA, $Cliente_Carreras))
                        ) {
                            $register = true;
                        } else {
                            $duplicado = true;
                        }
                    } else {
                        $duplicado = true;
                    }
                } else {

                    $Cliente_Cursos = Cliente::where('dni', $request->dni)
                        ->orWhere('email', $request->email)
                        ->orWhere('celular', $request->celular)
                        ->pluck('carrera_id')->toArray();

                    $Cliente_Carreras = Cliente::where('dni', $request->dni)->orWhere('email', $request->email)->orWhere('celular', $request->celular)
                        ->pluck('modalidad_id')->toArray();

                    if (
                        ($request->modalidad_id == App::$MODALIDAD_CURSO && !in_array($request->carrera_id, $Cliente_Cursos)) ||
                        ($request->modalidad_id == App::$MODALIDAD_CARRERA && !in_array(App::$MODALIDAD_CARRERA, $Cliente_Carreras))
                    ) {
                        $register = true;
                    } else if (!in_array($clienteExist->estado_id, [App::$ESTADO_CIERRE])) {
                        $reintento = true;
                        $update = true;
                    } else {
                        $duplicado = true;
                    }
                }
            } else {
                $register = true;
            }

            $request->merge([
                'user_id' =>  $userTurnId,
                'estado_id' => App::$ESTADO_NUEVO,
                'estado_detalle_id' => App::$ESTADO_DETALLE_NUEVO,
                'proviene_id' => App::$LLAMADA,
                'ultimo_contacto' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'reasignado' => false,
                'fuente_id' => $reintento ? App::$FUENTE_REINTENTO : $request->fuente_id,
                'origen_lead' => 'reingreso_api'
            ]);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'dni' => ['required', 'min:8', 'max:10'],
                'celular' => ['required', 'min:9', 'max:15'],
                'email' => ['required', 'email'],
                'provincia' => 'required',
                'provincia_id' => 'required',
                'distrito_id' => 'required',
                'modalidad_id' => 'required',
                'carrera_id' => 'required',
                'fuente_id' => 'required',
                'enterado_id' => 'required',
                'estado_id' => 'required',
                'estado_detalle_id' => 'required'
            ]);

            if (!$validator->fails()) {

                if ($duplicado) {
                    return response()->json(['Success' => $status, 'Message' => "El cliente ya se encuentra registrado en esta campaña"]);
                }

                if ($clienteExist != null && $update) {
                    $Cliente = Cliente::find($clienteExist->id);
                    $Cliente->estado_id = App::$ESTADO_REINTENTO;
                    $Cliente->estado_detalle_id = App::$ESTADO_DETALLE_REINTENTO;
                    $Cliente->fuente_id = App::$FUENTE_REINTENTO;
                    if ($Cliente->save()) {
                        $status = true;
                    }
                } else if ($register) {
                    $Cliente = Cliente::create($request->all());

                    //if ($request->provincia != null && $request->provincia == 0) {

                    $usersAllIds = DB::table('users')->whereNull('deleted_at')->where('profile_id', App::$PERFIL_RESTRINGIDO)
                        ->where('activo', true)->where('recibe_lead', true)->pluck('id')->toArray();

                    $minId = array_search(min($usersAllIds), $usersAllIds);
                    $maxId = array_search(max($usersAllIds), $usersAllIds);

                    $userTurnId = array_search($userTurnId, $usersAllIds) + 1;

                    if ($userTurnId > $maxId)
                        $userTurnId = $usersAllIds[$minId];
                    else
                        $userTurnId = $usersAllIds[$userTurnId];

                    $user = User::find($userTurnId);
                    $user->turno = true;
                    if ($user->save()) {
                        $status = true;
                        User::where('id', '!=', $user->id)->where('profile_id', App::$PERFIL_RESTRINGIDO)
                            ->update(['turno' => false]);
                    }

                    if ($status) {
                        $HistorialReasignar = new HistorialReasignar();
                        $HistorialReasignar->cliente_id = $Cliente->id;
                        $HistorialReasignar->user_id = 1;
                        $HistorialReasignar->vendedor_id = $Cliente->user_id;
                        $HistorialReasignar->observacion = "Registro";

                        if ($HistorialReasignar->save()) $status = true;
                    }
                }
            }

            if ($status) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }
}
