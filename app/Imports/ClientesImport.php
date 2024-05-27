<?php

namespace easyCRM\Imports;

use Carbon\Carbon;
use easyCRM\App;
use easyCRM\Carrera;
use easyCRM\Cliente;
use easyCRM\Distrito;
use easyCRM\Enterado;
use easyCRM\Estado;
use easyCRM\EstadoDetalle;
use easyCRM\Fuente;
use easyCRM\HistorialReasignar;
use easyCRM\Modalidad;
use easyCRM\Provincia;
use easyCRM\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientesImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $errors = [], $filas = [];
    protected $assessors;

    public function __construct($profile_id, $assessors)
    {
        $this->profile_id = $profile_id;
        $this->assessors = $assessors;
    }

    public function collection(Collection $rows)
    {
        try {
            $asesssor_count = count($this->assessors);
            $assessor_index = 0;
            $first_user_id = DB::table('users')->select('id')->where('activo', 1)->where('recibe_lead', 1)->whereNull('deleted_at')->first()->id;

            foreach ($rows as $key => $row) {
                $status = false;
                $user = null;
                $register = false;
                $update = false;
                $duplicado = false;
                $message = null;
                $validator = null;
                $userTurnId = null;
                $reintento = false;

                $modalidad_id = Modalidad::where('name', trim($row['modalidad']))->first()->id;
                $carrera_id = Carrera::where('name', trim($row['carrera_curso']))->first()->id;

                if (in_array(Auth::guard('web')->user()->profile_id, [App::$PERFIL_VENDEDOR, App::$PERFIL_RESTRINGIDO, App::$PERFIL_PROVINCIA])) {
                    $userTurnId = Auth::guard('web')->user()->id;
                } else {
                    /* $user = DB::table('users')->select('id')->where('profile_id', App::$PERFIL_VENDEDOR)
                        ->where('id', '!=', App::$USUARIO_PROVINCIA)->where('turno', true)
                        ->where('recibe_lead', true)->first(); */

                    //$userTurnId = $user != null ? $user->id : DB::table('users')->select('id')->first()->id;
                    $userTurnId = $this->assessors && $asesssor_count > 0 ? $this->assessors[$assessor_index]->id : $first_user_id;
                }

                $clienteExist = Cliente::where('dni', $row['dni'])
                    ->orWhere('email', $row['email'])
                    ->orWhere('celular', $row['celular'])
                    ->orderby('created_at', 'desc')->first();

                if ($clienteExist != null) {
                    $fecha_inicio_act_camp = Carbon::now()->format('d') >= 16 ? Carbon::now()->firstOfMonth()->addDay(15) : Carbon::now()->subMonth('1')->firstOfMonth()->addDay(15);
                    $fecha_final_act_camp = Carbon::now()->format('d') >= 16 ? Carbon::now()->endOfMonth()->addDay(15) : Carbon::now()->subMonth('1')->endOfMonth()->addDay(15);

                    if (($clienteExist->created_at >= $fecha_inicio_act_camp  && $clienteExist->created_at <= $fecha_final_act_camp)) {
                        if (($modalidad_id == App::$MODALIDAD_CURSO) ||
                            ($modalidad_id == App::$MODALIDAD_CARRERA && $clienteExist->modalidad_id == App::$MODALIDAD_CURSO)
                        ) {
                            $Cliente_Cursos = Cliente::where('dni',  $row['dni'])
                                ->orWhere('email', $row['email'])
                                ->orWhere('celular',  $row['celular'])
                                ->pluck('carrera_id')->toArray();

                            $Cliente_Carreras = Cliente::where('dni',  $row['dni'])->orWhere('email',  $row['email'])
                                ->orWhere('celular',  $row['celular'])
                                ->pluck('modalidad_id')->toArray();

                            if (
                                ($modalidad_id == App::$MODALIDAD_CURSO && !in_array($carrera_id, $Cliente_Cursos)) ||
                                ($modalidad_id == App::$MODALIDAD_CARRERA && !in_array(App::$MODALIDAD_CARRERA, $Cliente_Carreras))
                            ) {
                                $register = true;
                            } else {
                                $duplicado = true;
                            }
                        } else {
                            $duplicado = true;
                        }
                    } else {

                        $Cliente_Cursos = Cliente::where('dni', $row['dni'])
                            ->orWhere('email', $row['email'])
                            ->orWhere('celular', $row['celular'])
                            ->pluck('carrera_id')->toArray();

                        $Cliente_Carreras = Cliente::where('dni', $row['dni'])->orWhere('email', $row['email'])->orWhere('celular', $row['celular'])
                            ->pluck('modalidad_id')->toArray();

                        if (
                            ($modalidad_id == App::$MODALIDAD_CURSO && !in_array($carrera_id, $Cliente_Cursos)) ||
                            ($modalidad_id == App::$MODALIDAD_CARRERA && !in_array(App::$MODALIDAD_CARRERA, $Cliente_Carreras))
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

                $Validator = Validator::make($row->toArray(), [
                    'nombres' => 'required',
                    'apellidos' => 'required',
                    'dni' => ['required', 'min:8', 'max:10'],
                    'celular' => ['required', 'min:9', 'max:15'],
                    'email' => ['required', 'email'],
                    'provincia' => 'required',
                    'distrito' => 'required',
                    'modalidad' => 'required',
                    'carrera_curso' => 'required',
                    'origen_lead' => 'required',
                    'como_te_enteraste' => 'required',
                    'estado' => 'required',
                    'estado_detalle' => 'required'
                ]);
                if (!$Validator->fails()) {

                    if ($duplicado) {
                        $this->errors[] = ['key' => ($key + 1), 'Message' => 'No se pudo registrar al usuario ' . ($row['nombres'] . ' ' . $row['apellidos']) . ' porque es duplicado ', 'error' => null];
                    } else if ($clienteExist != null && $update) {
                        $Cliente = Cliente::find($clienteExist->id);
                        $Cliente->estado_id = App::$ESTADO_REINTENTO;
                        $Cliente->estado_detalle_id = App::$ESTADO_DETALLE_REINTENTO;
                        $Cliente->fuente_id = App::$FUENTE_REINTENTO;
                        if ($Cliente->save()) {
                            $status = true;
                        }
                    } else if ($register) {
                        $Cliente = Cliente::create([
                            'user_id' => $userTurnId,
                            'nombres' => $row['nombres'],
                            'apellidos' => $row['apellidos'],
                            'dni' => $row['dni'],
                            'celular' => $row['celular'],
                            'email' => $row['email'],
                            'proviene_id' => 1,
                            'provincia_id' => Provincia::where('name', trim($row['provincia']))->first()->id,
                            'distrito_id' => Distrito::where('name', trim($row['distrito']))->first()->id,
                            'modalidad_id' => Modalidad::where('name', trim($row['modalidad']))->first()->id,
                            'carrera_id' => Carrera::where('name', trim($row['carrera_curso']))->first()->id,
                            'fuente_id' => $reintento ? App::$FUENTE_REINTENTO : Fuente::where('name', trim($row['origen_lead']))->first()->id,
                            'enterado_id' => Enterado::where('name', trim($row['como_te_enteraste']))->first()->id,
                            'estado_id' => Estado::where('name', trim($row['estado']))->first()->id,
                            'estado_detalle_id' => EstadoDetalle::where('name', trim($row['estado_detalle']))->first()->id,
                            'reasignado' => 0,
                            'ciclo_id' => $row['ciclo'],
                            'created_modified_by' => auth()->user()->id,
                            'ultimo_contacto' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'origen_lead' => 'registro_import',
                        ]);

                        //actualización de assigned_lead
                        if ($Cliente && $this->assessors && $asesssor_count > 0) {
                            $this->assessors[$assessor_index]->assigned_leads += 1;
                            $this->assessors[$assessor_index]->save();
                        }

                        //increment and validate assessor index
                        if ($asesssor_count === ($assessor_index + 1)) {
                            $assessor_index = 0;
                        } else {
                            $assessor_index = $assessor_index + 1;
                        }

                        if (in_array(Auth::guard('web')->user()->profile_id, [App::$PERFIL_VENDEDOR, App::$PERFIL_RESTRINGIDO, App::$PERFIL_PROVINCIA]))
                            $status = true;
                        else {
                            $usersAllIds = DB::table('users')->whereNull('deleted_at')->where('profile_id', App::$PERFIL_VENDEDOR)
                                ->where('activo', 1)->where('recibe_lead', 1)->pluck('id')->toArray();

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
                        }

                        if ($status) {
                            $HistorialReasignar = new HistorialReasignar();
                            $HistorialReasignar->cliente_id =  $Cliente->id;
                            $HistorialReasignar->user_id =  1;
                            $HistorialReasignar->vendedor_id =  $Cliente->user_id;
                            $HistorialReasignar->observacion = "Registro";

                            if ($HistorialReasignar->save()) $status = true;
                        }
                    } else {
                        $this->errors[] = ['key' => ($key + 1), 'Message' => 'No se pudo registrar al usuario ' . ($row['nombres'] . ' ' . $row['apellidos']) . ' por ninguna acción ', 'error' => null];
                    }
                } else {
                    $errors = [];
                    foreach ($Validator->errors()->messages() as $messages) {
                        foreach ($messages as $error) {
                            $errors[] = ['error' => $error];
                        }
                    }
                    $this->errors[] = ['key' => ($key + 1), 'Message' => 'No se pudo registrar al usuario ' . ($row['nombres'] . ' ' . $row['apellidos']) . ' porque : ', 'error' => $errors];
                }
            }
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
