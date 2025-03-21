<?php

namespace easyCRM\Traits;

use Carbon\Carbon;
use easyCRM\App;
use easyCRM\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Consultas
{
    use Procesos;

    public function obtenerTotalClientesCreadosPorFecha($userProfile, $fecha_inicio, $fecha_final, $filter_lead_report)
    {
        $query = DB::table('clientes')
            ->select('clientes.*')
            ->join('users', 'users.id', '=', 'clientes.user_id')
            ->whereNull('clientes.deleted_at')
            ->whereIn('users.profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA]);

        if (in_array($userProfile, [App::$PERFIL_VENDEDOR, App::$PERFIL_RESTRINGIDO, App::$PERFIL_PROVINCIA])) {
            $query->where('clientes.user_id', Auth::guard('web')->user()->id);
        }

        if ($fecha_inicio && $fecha_final) {
            $query = $this->obtenerFiltroLeadPorCreatedAtAndLastContact($query, $fecha_inicio, $fecha_final, $filter_lead_report);
        }

        return $query->get();
    }

    public function obtenerTotalClienteMatriculasCreadosPorFecha($fecha_inicio, $fecha_final, $filter_lead_report)
    {
        $query = DB::table('cliente_matriculas')
            ->select(
                'cliente_matriculas.id',
                'cliente_matriculas.carrera_adicional_id',
                'cliente_matriculas.modalidad_adicional_id',
                'cliente_matriculas.turno_adicional_id',
                'clientes.provincia_id',
                'clientes.user_id',
                'clientes.fuente_id',
                'clientes.enterado_id',
                'clientes.turno_id'
            )
            ->join('clientes', 'clientes.id', '=', 'cliente_matriculas.cliente_id')
            ->whereNull('cliente_matriculas.deleted_at');
        $userLogin = Auth::user();
        if ($userLogin->id == 1) {
            dd($fecha_inicio, $fecha_final);
        }
        if ($fecha_inicio && $fecha_final) {
            $query = $this->obtenerFiltroLeadPorCreatedAtAndLastContact($query, $fecha_inicio, $fecha_final, $filter_lead_report);
        }

        return $query->get();
    }

    public function obtenerTotalClienteSeguimientosCreadosPorFecha($fecha_inicio, $fecha_final, $filter_lead_report)
    {
        $query = DB::table('cliente_seguimientos')
            ->select('cliente_seguimientos.id', 'cliente_seguimientos.accion_id')
            ->join('clientes', 'clientes.id', '=', 'cliente_seguimientos.cliente_id')
            ->whereNull('cliente_seguimientos.deleted_at');

        if ($fecha_inicio && $fecha_final) {
            $query = $this->obtenerFiltroLeadPorCreatedAtAndLastContact($query, $fecha_inicio, $fecha_final, $filter_lead_report);
        }

        return $query->get();
    }

    public function obtenerEstados()
    {
        return DB::table('estados')->whereNotIn('id', [App::$ESTADO_REINGRESO, App::$ESTADO_OTROS, App::$ESTADO_REMARKETING])->get();
    }

    public function obtenerAcciones()
    {
        return DB::table('accions')->get();
    }

    public function obtenerProvincias()
    {
        return DB::table('provincias')->get();
    }

    public function obtenerCarreras()
    {
        return DB::table('carreras')->where('modalidad_id', App::$MODALIDAD_CARRERA)->get();
    }

    public function obtenerCursos()
    {
        return DB::table('carreras')->where('modalidad_id', App::$MODALIDAD_CURSO)->get();
    }

    public function obtenerModalidades()
    {
        return DB::table('modalidads')->get();
    }

    public function obtenerEnterados()
    {
        return DB::table('enterados')->get();
    }

    public function obtenerTurnos()
    {
        return DB::table('turnos')->whereNotIn('id', [App::$TURNO_GLOABAL])->get();
    }

    public function obtenerAsesores()
    {
        return DB::table('users')->whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA])->where('activo', 1)->where('recibe_lead', 1)->get();
    }

    public function obtenerAsesoresRecibeLeads()
    {
        return User::where('profile_id', App::$PERFIL_VENDEDOR)
            ->where('id', '!=', App::$USUARIO_PROVINCIA)
            ->where('recibe_lead', 1)
            ->where('activo', 1)
            ->whereNull('deleted_at')
            ->orderBy('assigned_leads', 'ASC')
            ->get();
    }

    public function obtenerFuentes()
    {
        return DB::table('fuentes')->get();
    }

    protected function getAssessorWithMinimumAssignedLeads()
    {
        return  User::where('profile_id', App::$PERFIL_VENDEDOR)
            ->where('id', '!=', App::$USUARIO_PROVINCIA)
            ->where('recibe_lead', 1)
            ->where('activo', 1)
            ->whereNull('deleted_at')
            ->orderBy('assigned_leads', 'ASC')
            ->first();
    }
}
