<?php

namespace easyCRM\Http\Controllers\Auth;

use Carbon\Carbon;
use easyCRM\Traits\Consultas;
use easyCRM\Traits\Procesos;
use easyCRM\Accion;
use easyCRM\App;
use easyCRM\Carrera;
use easyCRM\Cliente;
use easyCRM\ClienteMatricula;
use easyCRM\ClienteSeguimiento;
use easyCRM\Enterado;
use easyCRM\Estado;
use easyCRM\EstadoDetalle;
use easyCRM\Fuente;
use easyCRM\Modalidad;
use easyCRM\Provincia;
use easyCRM\Turno;
use easyCRM\User;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    use Consultas, Procesos;

    public function index()
    {
        $Vendedores = User::whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA])->where('activo', true)
            ->orderby('name', 'asc')->get();

        return view('auth.reporte.index', ['Vendedores' => $Vendedores]);
    }

    public function filtro(Request $request)
    {
        $userProfile = Auth::guard('web')->user()->profile_id;
        $userLogin = Auth::user();
        //consultas globales
        $Acciones = $this->obtenerAcciones();
        $Provincias = $this->obtenerProvincias();
        $Carreras = $this->obtenerCarreras();
        $Cursos = $this->obtenerCursos();
        $Modalidades = $this->obtenerModalidades();
        $Enterados = $this->obtenerEnterados();
        $Assessors = $this->obtenerEnterados();
        $Turnos = $this->obtenerTurnos();
        $Usuarios = $this->obtenerAsesores();
        $Fuentes = $this->obtenerFuentes();
        $totalClientes = $this->obtenerTotalClientesCreadosPorFecha($userProfile, $request->fecha_inicio, $request->fecha_final, $request->filter_lead_report);
        if ($userLogin->id == 1) {
            $totalClientes = $this->obtenerTotalClientesCreadosPorFecha($userProfile, "2025-03-19", "2025-03-19", "created_at_last_contact");
        }
        $totalClientesMatriculas = $this->obtenerTotalClienteMatriculasCreadosPorFecha($request->fecha_inicio, $request->fecha_final, $request->filter_lead_report);
        $totalClientesSeguimientos = $this->obtenerTotalClienteSeguimientosCreadosPorFecha($request->fecha_inicio, $request->fecha_final, $request->filter_lead_report);

        $count_clientes = COUNT($totalClientes);

        if ($request->action_full == "true") {
            $Estados = $this->obtenerEstados();

            $arregloFilterEstadosGlobal = [];
            $arregloFilterEstados = [];

            foreach ($Estados as $q) {
                $Cantidad = $this->obtenerDatosPorFiltro($totalClientes, array(['columna' => 'estado_id', 'valor' => $q->id]), 'cantidad');
                if (in_array($q->id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                    if (in_array($userProfile, [App::$PERFIL_VENDEDOR, App::$PERFIL_RESTRINGIDO, App::$PERFIL_PROVINCIA])) {
                        $Cantidad += (int) $this->obtenerDatosPorFiltro($totalClientesMatriculas, array(['columna' => 'user_id', 'valor' => Auth::guard('web')->user()->id]), 'cantidad');
                    } else {
                        $Cantidad += (int) $this->obtenerDatosPorFiltro($totalClientesMatriculas, array(), 'cantidad');
                    }
                }

                array_push($arregloFilterEstadosGlobal, [$q->name, $Cantidad, $q->background, $q->id]);
                array_push($arregloFilterEstados, [
                    'color' => $q->background, 'name' => $q->name, 'y' => ($Cantidad > 0 && $count_clientes > 0 ? ($Cantidad / $count_clientes) * 100 : 0), 'count' => $Cantidad, 'drilldown' => null
                ]);
            }

            usort($arregloFilterEstados, $this->OrdernarArreglo('count', 'DESC'));

            $arregloFilterAcciones = [];
            foreach ($Acciones as $q) {
                $Cantidad = $this->obtenerDatosPorFiltro($totalClientesSeguimientos, array(['columna' => 'accion_id', 'valor' => $q->id]), 'cantidad');
                array_push($arregloFilterAcciones, [$q->name, $Cantidad, false]);
            }
        }

        /* CONSULTA DE MARCO */
        $recibeLeadAsesor = DB::select(
            "SELECT  J1.nombre_asesor as 'name', count(*) as 'y'
                From (
                    select 
                    CL.nombres as 'nombre_cliente',
                    CL.apellidos as 'apellido_cliente' ,
                    US.name as 'nombre_asesor'
                    from clientes CL 
                    inner join users US on US.id=CL.user_id
                    where CL.deleted_at is null and US.deleted_at is null and CL.created_at BETWEEN '" . $request->fecha_inicio . " 00:00:00' AND '" . $request->fecha_final . " 23:59:59'
                ) as J1
                Group by J1.nombre_asesor 
                order by y desc"
        );

        /* $recibeLeadsNuevosAsesor = DB::select("SELECT
            J1.nombre_asesor as 'name',
            count(*) as 'y'
        From (
        select 
        CL.nombres as 'nombre_cliente',
        CL.apellidos as 'apellido_cliente' ,
        US.name as 'nombre_asesor'
        from clientes CL 
        inner join users US on US.id=CL.user_id
        where CL.estado_id = 1 and CL.deleted_at is null and US.deleted_at is null and date(CL.created_at) BETWEEN '" . $request->fecha_inicio . "' AND '" . $request->fecha_final . "'
        ) as J1
        Group by J1.nombre_asesor 
        order by y desc"); */

        $recibeLeadsNuevosAsesor = DB::select("CALL seguimiento_asesores_por_fecha_de_registro('" . $request->fecha_inicio . "', '" . $request->fecha_final . "');");

        $arregloFilterProvincias = [];
        foreach ($Provincias as $q) {
            if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'provincia_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                    'cantidad'
                );
                $Cantidad += (int) $this->obtenerDatosPorFiltro(
                    $totalClientesMatriculas,
                    array(['columna' => 'provincia_id', 'valor' => $q->id]),
                    'cantidad'
                );
            } else {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'provincia_id', 'valor' => $q->id]),
                    'cantidad'
                );
            }

            if ($Cantidad > 0) {
                array_push($arregloFilterProvincias, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && $count_clientes > 0 ? ($Cantidad / $count_clientes) * 100 : 0), 'count' => $Cantidad, 'drilldown' => null
                ]);
            }
        }

        usort($arregloFilterProvincias, $this->OrdernarArreglo('count', 'DESC'));

        $arregloFilterCarreras = [];
        foreach ($Carreras as $q) {
            if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'carrera_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                    'cantidad'
                );
                $Cantidad += (int) $this->obtenerDatosPorFiltro(
                    $totalClientesMatriculas,
                    array(['columna' => 'carrera_adicional_id', 'valor' => $q->id]),
                    'cantidad'
                );
            } else {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'carrera_id', 'valor' => $q->id]),
                    'cantidad'
                );
            }

            if ($Cantidad > 0) {
                array_push($arregloFilterCarreras, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && $count_clientes > 0 ? ($Cantidad / $count_clientes) * 100 : 0), 'count' => $Cantidad, 'drilldown' => null
                ]);
            }
        }

        usort($arregloFilterCarreras, $this->OrdernarArreglo('count', 'DESC'));

        $Cursos = Carrera::Where('modalidad_id', App::$MODALIDAD_CURSO)->get();
        $arregloFilterCursos = [];

        foreach ($Cursos as $q) {
            if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'carrera_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                    'cantidad'
                );
                $Cantidad += (int) $this->obtenerDatosPorFiltro(
                    $totalClientesMatriculas,
                    array(['columna' => 'carrera_adicional_id', 'valor' => $q->id]),
                    'cantidad'
                );
            } else {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'carrera_id', 'valor' => $q->id]),
                    'cantidad'
                );
            }

            if ($Cantidad > 0) {
                array_push($arregloFilterCursos, [
                    'name' => $q->name, 'y' => $Cantidad > 0 ? ($Cantidad / $count_clientes) * 100 : $Cantidad, 'count' => $Cantidad, 'drilldown' => null
                ]);
            }
        }

        usort($arregloFilterCursos, $this->OrdernarArreglo('count', 'DESC'));

        $arregloFilterModalidades = [];
        foreach ($Modalidades as $q) {
            if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'modalidad_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                    'cantidad'
                );
                $Cantidad += (int) $this->obtenerDatosPorFiltro(
                    $totalClientesMatriculas,
                    array(['columna' => 'modalidad_adicional_id', 'valor' => $q->id]),
                    'cantidad'
                );
            } else {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'modalidad_id', 'valor' => $q->id]),
                    'cantidad'
                );
            }

            if ($Cantidad > 0) {
                array_push($arregloFilterModalidades, [$q->name, $Cantidad, false]);
            }
        }

        $arregloFilterFuentes = [];
        foreach ($Fuentes as $q) {
            if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'fuente_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                    'cantidad'
                );
                $Cantidad += (int) $this->obtenerDatosPorFiltro(
                    $totalClientesMatriculas,
                    array(['columna' => 'fuente_id', 'valor' => $q->id]),
                    'cantidad'
                );
            } else {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'fuente_id', 'valor' => $q->id]),
                    'cantidad'
                );
            }

            if ($Cantidad > 0) {
                array_push($arregloFilterFuentes, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && $count_clientes > 0 ? ($Cantidad / $count_clientes) * 100 : 0), 'count' => $Cantidad, 'drilldown' => null
                ]);
            }
        }

        usort($arregloFilterFuentes, $this->OrdernarArreglo('count', 'DESC'));

        $arregloFilterEnterados = [];
        foreach ($Enterados as $q) {
            if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'enterado_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                    'cantidad'
                );
                $Cantidad += (int) $this->obtenerDatosPorFiltro(
                    $totalClientesMatriculas,
                    array(['columna' => 'enterado_id', 'valor' => $q->id]),
                    'cantidad'
                );
            } else {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'enterado_id', 'valor' => $q->id]),
                    'cantidad'
                );
            }

            if ($Cantidad > 0) {
                array_push($arregloFilterEnterados, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && $count_clientes > 0 ? ($Cantidad / $count_clientes) * 100 : 0), 'count' => $Cantidad, 'drilldown' => null
                ]);
            }
        }

        usort($arregloFilterEnterados, $this->OrdernarArreglo('count', 'DESC'));

        $arregloFilterTurnos = [];
        foreach ($Turnos as $q) {
            if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'turno_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                    'cantidad'
                );
                $Cantidad += (int) $this->obtenerDatosPorFiltro(
                    $totalClientesMatriculas,
                    array(['columna' => 'turno_adicional_id', 'valor' => $q->id]),
                    'cantidad'
                );
            } else {
                $Cantidad = $this->obtenerDatosPorFiltro(
                    $totalClientes,
                    array(['columna' => 'turno_id', 'valor' => $q->id]),
                    'cantidad'
                );
            }
            array_push($arregloFilterTurnos, [$q->name, $Cantidad, false]);
        }
        if ($request->action_full == "false") {
            $arregloFilterTurnos = [];
            foreach ($Turnos as $q) {
                if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                    $Cantidad = $this->obtenerDatosPorFiltro(
                        $totalClientes,
                        array(['columna' => 'turno_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                        'cantidad'
                    );
                    $Cantidad += (int) $this->obtenerDatosPorFiltro(
                        $totalClientesMatriculas,
                        array(['columna' => 'turno_adicional_id', 'valor' => $q->id]),
                        'cantidad'
                    );
                } else {
                    $Cantidad = $this->obtenerDatosPorFiltro(
                        $totalClientes,
                        array(['columna' => 'turno_id', 'valor' => $q->id]),
                        'cantidad'
                    );
                }
                array_push($arregloFilterTurnos, [$q->name, $Cantidad, false]);
            }

            $arregloFilterUsuarios = [];
            foreach ($Usuarios as $q) {
                if (in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                    $Cantidad = $this->obtenerDatosPorFiltro(
                        $totalClientes,
                        array(['columna' => 'user_id', 'valor' => $q->id], ['columna' => 'estado_id', 'valor' => $request->estado_id]),
                        'cantidad'
                    );
                    $Cantidad += (int) $this->obtenerDatosPorFiltro(
                        $totalClientesMatriculas,
                        array(['columna' => 'user_id', 'valor' => $q->id]),
                        'cantidad'
                    );
                } else {
                    $Cantidad = $this->obtenerDatosPorFiltro(
                        $totalClientes,
                        array(['columna' => 'user_id', 'valor' => $q->id]),
                        'cantidad'
                    );
                }
                array_push($arregloFilterUsuarios, [
                    'color' => "#2ECC71", 'name' => $q->name, 'y' => $Cantidad > 0 ? ($Cantidad / $count_clientes) * 100 : $Cantidad, 'count' => $Cantidad, 'drilldown' => null
                ]);
            }

            usort($arregloFilterUsuarios, $this->OrdernarArreglo('count', 'DESC'));
        }

        if ($request->action_full == "true") {

            return response()->json([
                'recibeLeadAsesor' => $recibeLeadAsesor,
                'recibeLeadsNuevosAsesor' => $recibeLeadsNuevosAsesor,
                'Estados' => $arregloFilterEstados,
                'EstadosGlobal' => $arregloFilterEstadosGlobal,
                'Acciones' => $arregloFilterAcciones,
                //'RegistroDias' => $arregloFilterEstadosPorDias,
                'Provincias' => $arregloFilterProvincias,
                'Carreras' => $arregloFilterCarreras,
                'Cursos' => $arregloFilterCursos,
                'Modalidades' => $arregloFilterModalidades,
                'Fuentes' => $arregloFilterFuentes,
                'Enterados' => $arregloFilterEnterados,
                'Clientes' => $count_clientes
            ]);
        }

        return response()->json([
            'recibeLeadAsesor' => $recibeLeadAsesor,
            'recibeLeadsNuevosAsesor' => $recibeLeadsNuevosAsesor,
            'Provincias' => $arregloFilterProvincias,
            'Carreras' => $arregloFilterCarreras,
            'Cursos' => $arregloFilterCursos,
            'Modalidades' => $arregloFilterModalidades,
            'Fuentes' => $arregloFilterFuentes,
            'Enterados' => $arregloFilterEnterados,
            'Turnos' =>  $arregloFilterTurnos,
            'Usuarios' => $arregloFilterUsuarios,
            'Clientes' => $count_clientes
        ]);
    }

    public function vendedores()
    {
        $Vendedores = User::where('profile_id', App::$PERFIL_VENDEDOR)->get();
        return view('auth.reporte.vendedores', ['Vendedores' => $Vendedores]);
    }

    public function filtro_vendedores(Request $request)
    {
        $Clientes = Cliente::whereNull('deleted_at')
            ->whereHas('users', function ($query) {
                $query->where('profile_id',  App::$PERFIL_VENDEDOR);
            })
            ->where(function ($q) use ($request) {
                if ($request->fecha_inicio) {
                    $q->whereDate('ultimo_contacto', '>=', $request->fecha_inicio);
                }
            })
            ->where(function ($q) use ($request) {
                if ($request->fecha_final) {
                    $q->whereDate('ultimo_contacto', '<=', $request->fecha_final);
                }
            })
            ->get();

        $ClientesCreated = Cliente::whereNull('deleted_at')
            ->whereHas('users', function ($query) {
                $query->where('profile_id',  App::$PERFIL_VENDEDOR);
            })
            ->where(function ($q) use ($request) {
                if ($request->fecha_inicio) {
                    $q->whereDate('created_at', '>=', $request->fecha_inicio);
                }
            })
            ->where(function ($q) use ($request) {
                if ($request->fecha_final) {
                    $q->whereDate('created_at', '<=', $request->fecha_final);
                }
            })
            ->get();

        $Estados = Estado::all();

        $arregloFilterEstadosGlobal = [];
        $CantidadtTotal = 0;

        /*foreach ($Estados as $q){

            if($q->id == App::$ESTADO_NUEVO) {
                $Cantidad = count($ClientesCreated->where('estado_id', $q->id)->pluck('estado_id')->toArray());
            }else if($q->id == App::$ESTADO_CIERRE) {
                $Cantidad = count($Clientes->where('estado_id', $q->id)->pluck('estado_id')->toArray());
                $Cantidad = $Cantidad + count(ClienteMatricula::whereNull('deleted_at')
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->pluck('id')->toArray());
            }else {
                $Cantidad = count($Clientes->where('estado_id', $q->id)->pluck('estado_id')->toArray());
            }

            $CantidadtTotal += $Cantidad;
        }*/

        if (Auth::guard('web')->user()->profile_id == App::$PERFIL_VENDEDOR) {
            $Clientes = $Clientes->where('user_id', Auth::guard('web')->user()->id);
            $ClientesCreated = $ClientesCreated->where('user_id', Auth::guard('web')->user()->id);
        } else {
            $Clientes = $request->vendedor_id ? $Clientes->where('user_id', $request->vendedor_id) : $Clientes;
            $ClientesCreated = $request->vendedor_id ? $ClientesCreated->where('user_id', $request->vendedor_id) : $ClientesCreated;
        }

        array_push($arregloFilterEstadosGlobal, ["TOTAL", count($ClientesCreated)]);

        foreach ($Estados as $q) {

            if (!in_array($q->id, [App::$ESTADO_OTROS, App::$ESTADO_NOCONTACTADO, App::$ESTADO_PERDIDO])) {

                if ($q->id == App::$ESTADO_NUEVO) {
                    $Cantidad = count($ClientesCreated->where('estado_id', $q->id)->pluck('estado_id')->toArray());
                    array_push($arregloFilterEstadosGlobal, [$q->name, $Cantidad]);
                } else if (in_array($q->id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                    $Cantidad = count($ClientesCreated->where('estado_id', $q->id)->pluck('estado_id')->toArray());
                    $Cantidad = $Cantidad + count(ClienteMatricula::whereNull('deleted_at')
                        ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                        ->pluck('id')->toArray());
                    array_push($arregloFilterEstadosGlobal, [$q->name, $Cantidad]);
                } else {
                    $Cantidad = count($ClientesCreated->where('estado_id', $q->id)->pluck('estado_id')->toArray());
                    array_push($arregloFilterEstadosGlobal, [$q->name, $Cantidad]);
                }
            }
        }

        $arregloFilterVendedorEstados = [];

        if (Auth::guard('web')->user()->profile_id == App::$PERFIL_VENDEDOR) {
            $matriculados = count(ClienteMatricula::whereHas('clientes', function ($query) use ($request) {
                $query->where('user_id', Auth::guard('web')->user()->id);
            })
                ->whereNull('deleted_at')->whereDate('created_at', '>=', $request->fecha_inicio)
                ->whereDate('created_at', '<=', $request->fecha_final)->pluck('id')->toArray());;
        } else {
            $matriculados = count(ClienteMatricula::whereHas('clientes', function ($query) use ($request) {
                if ($request->vendedor_id) {
                    $query->where('user_id', $request->vendedor_id);
                }
            })
                ->whereNull('deleted_at')->whereDate('created_at', '>=', $request->fecha_inicio)
                ->whereDate('created_at', '<=', $request->fecha_final)->pluck('id')->toArray());
        }

        array_push($arregloFilterVendedorEstados, [
            'estado_id' => 0, 'name' => "TODOS", 'y' => (count($Clientes) + $matriculados) > 0 ? ((count($Clientes) + $matriculados) / (count($Clientes) + $matriculados)) * 100 : (count($Clientes) + $matriculados),
            'count' => (count($Clientes) + $matriculados), 'drilldown' => "TODOS", 'color' => "#7C4DFF"
        ]);

        foreach ($Estados as $q) {
            $Cantidad = count($Clientes->where('estado_id', $q->id)->pluck('estado_id')->toArray());
            if (in_array($q->id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $Cantidad + count(ClienteMatricula::whereHas('clientes', function ($query) use ($request) {
                    if ($request->vendedor_id) {
                        $query->where('user_id', $request->vendedor_id);
                    }
                })->whereNull('deleted_at')
                    ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                    ->pluck('id')->toArray());
            }
            array_push($arregloFilterVendedorEstados, [
                'estado_id' => $q->id, 'name' => $q->name, 'y' => $Cantidad > 0 ? ($Cantidad / count($Clientes)) * 100 : $Cantidad, 'count' => $Cantidad,
                'drilldown' => $q->name, 'color' => $q->background
            ]);
        }

        usort($arregloFilterVendedorEstados, $this->OrdernarArreglo('count', 'DESC'));

        $EstadosDetalles = EstadoDetalle::all();

        $arregloFilterVendedorEstadosDetalle = [];

        foreach ($arregloFilterVendedorEstados as $q) {

            $data = [];

            foreach ($EstadosDetalles as $e) {
                if ($q['estado_id'] == $e->estado_id) {

                    $cantidad = 0;
                    foreach ($Clientes as $c) {
                        if ($c->estado_detalle_id == $e->id) {
                            $cantidad++;
                        }
                    }

                    if ($cantidad > 0) {
                        array_push($data, [
                            'name' => $e->name,
                            'y' => $q['count'] > 0 ? (($cantidad / $q['count']) * 100) : 0,
                            'count' => $cantidad
                        ]);
                    }
                }
            }

            array_push($arregloFilterVendedorEstadosDetalle, [
                'id' => $q['name'],
                'name' => $q['name'],
                'data' => $data
            ]);
        }

        $Acciones = Accion::all();

        $arregloFilterAcciones = [];

        $profile_id = Auth::guard('web')->user()->profile_id;

        foreach ($Acciones as $q) {
            $Cantidad = count(ClienteSeguimiento::with('clientes')->where('accion_id', $q->id)
                ->whereHas('clientes', function ($q) use ($request) {
                    if ($request->vendedor_id && $request->vendedor_id != "undefined") {
                        $q->where('user_id', $request->vendedor_id);
                    }
                })
                ->whereHas('clientes', function ($q) use ($profile_id) {
                    if ($profile_id == App::$PERFIL_VENDEDOR) {
                        $q->where('user_id', Auth::guard('web')->user()->id);
                    }
                })
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->pluck('accion_id')
                ->toArray());

            array_push($arregloFilterAcciones, [$q->name, $Cantidad, false]);
        }

        return response()->json([
            'EstadosGlobal' => $arregloFilterEstadosGlobal,
            'Vendedores' => $arregloFilterVendedorEstados,
            'VendedoresDetalles' => $arregloFilterVendedorEstadosDetalle,
            'Acciones' => $arregloFilterAcciones,
        ]);
    }

    public function OrdernarArreglo($elemento, $orden = null)
    {
        return function ($a, $b) use ($elemento, $orden) {
            $result =  ($orden == "DESC") ? strnatcmp($b[$elemento], $a[$elemento]) :  strnatcmp($a[$elemento], $b[$elemento]);
            return $result;
        };
    }

    public function reportAdmin()
    {
        $test = 0;
        //Testeo
        if ($test == 1) {
            $today = '20241231';
            $firstDayMonth = '20241201';
        } else {
            $today = Carbon::now()->format('Ymd');
            $firstDayMonth = Carbon::now()->startOfMonth()->format('Ymd');
        }
        //Datos requeridos
        /*$clientData = DB::select("CALL JCELeadsIngresados(?, ?)", [
            $firstDayMonth,
            $today,
        ]);*/
        $clientData = Cliente::where('id', 0)->get();
        $formattedToday = Carbon::createFromFormat('Ymd', $today)->format('Y-m-d');
        $formattedFirstDayMonth = Carbon::createFromFormat('Ymd', $firstDayMonth)->format('Y-m-d');
        return view('auth.reporte.report-admin')->with('clientData', $clientData)->with('formattedToday', $formattedToday)->with('formattedFirstDayMonth', $formattedFirstDayMonth);
    }
    public function storeReportAdmin(Request $request)
    {
        $fechaInicio = $request->input('fechaInicio');
        $fechaFinal = $request->input('fechaFinal');
        $clientData = DB::select("CALL JCELeadsIngresados(?, ?)", [
            $fechaInicio,
            $fechaFinal,
        ]);
        $data = array_map(function ($client) {
            return [
                $client->Cliente,
                $client->DNI,
                $client->Celular,
                $client->Whatsapp,
                $client->Email,
                $client->fecha_nacimiento,
                $client->Plataforma,
                $client->Fuente,
                $client->Provincia,
                $client->Distrito,
                $client->Especialidad,
                $client->Modalidad,
                $client->Estado,
                $client->Detalle_Estado,
                $client->Turno,
                $client->Horario,
                $client->Sede,
                $client->Observacion,
                $client->Fecha_ultimo_contacto,
                $client->Año,
                $client->Mes
            ];
        }, $clientData);
        return response()->json([
            'data' => $data
        ]);
    }
    public function editClient()
    {
        return view('auth.cliente.edit-client');
    }
    public function storeSearchClient(Request $request)
    {
        $clientData = Cliente::where('dni', $request->numberSearch)->get();
        if (!$clientData) {
            $clientData = Cliente::where('celular', $request->numberSearch)->get();
        }
        return response()->json([
            'data' => $clientData
        ]);
    }
    public function editClientUnit($id)
    {
        $clientData = Cliente::where('id', $id)->first();
        return view('auth.cliente.edit-client-unit')->with('clientData', $clientData);
    }
    public function storeEditClientUnit(Request $request)
    {
        $userLogin = Auth::user();
        $lastName = $request->paternalSurname . " " . $request->maternalSurname;
        $clientData = Cliente::where('id', $request->idClient)->update([
            'nombres' => $request->name,
            'apellidos' => $lastName,
            'apellido_paterno' => $request->paternalSurname,
            'apellido_materno' => $request->maternalSurname,
            'email' => $request->email,
            'dni' => $request->dni,
            'celular' => $request->celular,
            'whatsapp' => $request->whatsapp,
            'fecha_nacimiento' => $request->date,
            'direccion' => $request->direction,
            'updated_at' => Carbon::now(),
            'updated_modified_by' => $userLogin->id,
        ]);
        return redirect()->back()->with('success', 'Cliente actualizado correctamente.');
    }
}
