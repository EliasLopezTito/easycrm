<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\App;
use easyCRM\Cliente;
use easyCRM\HistorialReasignar;
use easyCRM\Profile;
use easyCRM\Turno;
use easyCRM\User;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('auth.usuario.index');
    }

    public function list_all(Request $request)
    {
        if ($request->estado_usuario != null && $request->estado_usuario != "") {
            $list = User::whereNotIn('id', [1])
                ->where('activo', $request->estado_usuario)
                ->with('profiles')
                ->with('turnos')
                ->orderBy('created_at', 'desc')->get();
        } else {
            $list = User::whereNotIn('id', [1])
                ->with('profiles')
                ->with('turnos')
                ->orderBy('created_at', 'desc')->get();
        }
        return response()->json(['data' => $list]);
    }

    public function partialView($id)
    {
        $entity = null;

        if ($id != 0) $entity = User::find($id);

        $Profiles = Profile::orderBy('name', 'asc')->get();
        $Turnos = Turno::whereNotIn('id', [App::$TURNO_GLOABAL, App::$TURNO_NOCHE])->get();

        return view('auth.usuario._Mantenimiento', ['Usuario' => $entity, 'Turnos' => $Turnos, 'Profiles' => $Profiles]);
    }

    public function store(Request $request)
    {
        $status = false;

        if ($request->id != 0) {

            $Usuario = User::find($request->id);

            $Usuario->password = ($request->password != null && trim($request->password) != "") ? bcrypt($request->password) : $Usuario->password;

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'last_name' => 'required',
                'turno_id' => 'required',
                'profile_id' => 'required',
            ]);
        } else {

            $Usuario = new User();
            $Usuario->email = $request->email;
            $Usuario->password = bcrypt($request->password);
            $Usuario->turno = App::$INACTIVO;
            $Usuario->created_modified_by = auth()->user()->id;

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'profile_id' => 'required',
                'turno_id' => 'required',
                'password' => 'required|min:6|max:20',
                'recibe_lead' => 'required',
                'activo' => 'required'
            ]);
        }

        if (!$validator->fails()) {
            $Usuario->name = $request->name;
            $Usuario->last_name = $request->last_name;
            $Usuario->turno_id = $request->turno_id;
            $Usuario->profile_id = $request->profile_id;
            $Usuario->activo = $request->activo;
            $Usuario->recibe_lead = $request->recibe_lead;
            $Usuario->updated_modified_by = auth()->user()->id;

            if ($Usuario->save()) $status = true;
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = User::find($request->get('id'));
        $entity->deleted_modified_by = auth()->user()->id;

        if ($entity->delete() and $entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function reasignar(Request $request)
    {
        $status = false;
        $Message = null;

        $Leads = explode(",", $request->array_leads);

        //$clientes = Cliente::whereIn('id', explode(",", $Leads));
        //$clientesReasignacion = $clientes;

        try {

            DB::beginTransaction();

            //$clientes = $clientes->select(DB::raw("CONCAT(nombres,' ',apellidos) AS NombresCompleto"))->pluck('NombresCompleto')->toArray();

            for ($i = 0; $i < count($Leads); $i++) {
                $HistorialReasignar = new HistorialReasignar();
                $HistorialReasignar->user_id = Auth::guard('web')->user()->id;
                $HistorialReasignar->cliente_id =  $Leads[$i];
                $HistorialReasignar->vendedor_id =  $request->reasignar_id;
                $HistorialReasignar->observacion = "Reasignó este registro a una Asesora";
                $HistorialReasignar->save();
            }

            $Cliente = Cliente::whereIn('id', $Leads)->update(['user_id' => $request->reasignar_id]);

            //assigned_leads 
            if ($Cliente) {
                $assessor = User::where('id', $request->reasignar_id)->first();
                if ($assessor) {
                    $assessor->assigned_leads += count($Leads);
                    $assessor->save();
                }
            }

            $status = true;

            DB::commit();
        } catch (\Exception $e) {
            $Message = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['Success' => $status, 'Message' => $Message]);
    }


    /* CODIGO AÑADIO POR SEBASTIAN  PARA GESTIONAR LAS ASIGNACIONES*/
   /*  public function reasignar(Request $request)
    {
        $status = false;
        $Message = null;

        $Leads = explode(",", $request->array_leads);

        try {

            DB::beginTransaction();

            // Obtener el ID del vendedor anterior (antes de la reasignación)
            $oldVendedorId = Cliente::whereIn('id', $Leads)->pluck('user_id')->unique();

            // Descontar los leads al vendedor anterior si existe
            if ($oldVendedorId->isNotEmpty()) {
                $oldVendedor = User::where('id', $oldVendedorId->first())->first();
                if ($oldVendedor) {
                    $oldVendedor->assigned_leads -= count($Leads); // Descontamos los leads
                    $oldVendedor->save();
                }
            }

            // Guardar el historial de reasignación para cada lead
            for ($i = 0; $i < count($Leads); $i++) {
                $HistorialReasignar = new HistorialReasignar();
                $HistorialReasignar->user_id = Auth::guard('web')->user()->id;
                $HistorialReasignar->cliente_id =  $Leads[$i];
                $HistorialReasignar->vendedor_id =  $request->reasignar_id;
                $HistorialReasignar->observacion = "Reasignó este registro a una Asesora";
                $HistorialReasignar->save();
            }

            // Actualizar el cliente con el nuevo vendedor
            $Cliente = Cliente::whereIn('id', $Leads)->update(['user_id' => $request->reasignar_id]);

            // Sumar los leads al nuevo vendedor
            $assessor = User::where('id', $request->reasignar_id)->first();
            if ($assessor) {
                $assessor->assigned_leads += count($Leads); // Sumamos los leads
                $assessor->save();
            }

            $status = true;

            DB::commit();
        } catch (\Exception $e) {
            $Message = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['Success' => $status, 'Message' => $Message]);
    } */

}
