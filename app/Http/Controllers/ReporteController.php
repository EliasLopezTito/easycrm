<?php

namespace easyCRM\Http\Controllers\Auth;

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
    public function index()
    {
        $Vendedores = User::whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA])->where('activo', true)
            ->orderby('name', 'asc')->get();

        return view('auth.reporte.index', ['Vendedores' => $Vendedores]);
    }

    public function filtro(Request $request)
    {
        $userProfile = Auth::guard('web')->user()->profile_id;

        $ClientesAll = Cliente::whereNull('deleted_at')
        ->whereHas('users', function ($query) { $query->whereIn('profile_id',  [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA]); } )
        ->where(function ($q) use ($userProfile){ if(in_array($userProfile, [App::$PERFIL_VENDEDOR, App::$PERFIL_RESTRINGIDO, App::$PERFIL_PROVINCIA])){ $q->where('user_id', Auth::guard('web')->user()->id); }})
        ->where(function ($q) use ($request){ if($request->fecha_inicio){ $q->whereDate('ultimo_contacto', '>=', $request->fecha_inicio); }})
        ->where(function ($q) use ($request){ if($request->fecha_final){ $q->whereDate('ultimo_contacto', '<=', $request->fecha_final);}});

        $ClientesCreated = Cliente::whereNull('deleted_at')
            ->whereHas('users', function ($query) { $query->whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA]); } )
            ->where(function ($q) use ($userProfile){ if(in_array($userProfile, [App::$PERFIL_VENDEDOR, App::$PERFIL_RESTRINGIDO, App::$PERFIL_PROVINCIA])){ $q->where('user_id', Auth::guard('web')->user()->id); }})
            ->where(function ($q) use ($request){ if($request->fecha_inicio){ $q->whereDate('created_at', '>=', $request->fecha_inicio); }})
            ->where(function ($q) use ($request){ if($request->fecha_final){ $q->whereDate('created_at', '<=', $request->fecha_final);}})
            ->get();

        $Clientes = $ClientesAll->get();

        if ($request->action_full == "true")
        {
            $Estados = Estado::whereNotIn('id',[App::$ESTADO_REINGRESO, App::$ESTADO_OTROS])->get();

            $arregloFilterEstadosGlobal = []; $arregloFilterEstados = [];

            foreach ($Estados as $q){
                $Cantidad = count($Clientes->where('estado_id', $q->id)->pluck('estado_id')->toArray());
                if(in_array($q->id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                    if(in_array($userProfile, [App::$PERFIL_VENDEDOR, App::$PERFIL_RESTRINGIDO, App::$PERFIL_PROVINCIA])){
                        $Cantidad = $Cantidad + count(ClienteMatricula::whereHas('clientes', function ($query){ $query->where('user_id',  Auth::guard('web')->user()->id); })->whereNull('deleted_at')
                        ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                        ->pluck('id')->toArray());
                    }else{
                        $Cantidad = $Cantidad + count(DB::table('cliente_matriculas')->whereNull('deleted_at')
                        ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                        ->pluck('id')->toArray());
                    }
                }else if($q->id == App::$ESTADO_NUEVO)
                {
                    $Cantidad = count($ClientesAll->whereHas('users', function ($query) { $query->whereIn('profile_id',[App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA]); } )->where('estado_id', $q->id)->pluck('estado_id')->toArray());
                }
                array_push($arregloFilterEstadosGlobal, [$q->name, $Cantidad, $q->background, $q->id]);
                array_push($arregloFilterEstados, [
                    'color' => $q->background, 'name' => $q->name, 'y' => ($Cantidad > 0 && count($Clientes) > 0 ? ($Cantidad/count($Clientes))*100 : 0),'count' => $Cantidad, 'drilldown' => null]);
            }

            usort($arregloFilterEstados, $this->OrdernarArreglo('count', 'DESC'));

            $Acciones = Accion::all();

            $arregloFilterAcciones = [];

            foreach ($Acciones as $q){
                $Cantidad = count(DB::table('cliente_seguimientos')->whereNull('deleted_at')->where('accion_id', $q->id)
                    ->where(function ($q) use ($request){ if($request->fecha_inicio){ $q->whereDate('created_at', '>=', $request->fecha_inicio); }})
                    ->where(function ($q) use ($request){ if($request->fecha_final){ $q->whereDate('created_at', '<=', $request->fecha_final);}})
                    ->pluck('accion_id')->toArray());
                array_push($arregloFilterAcciones, [$q->name, $Cantidad, false]);
            }

            $fechas = App::ObtenerFechasPorRango($request->fecha_inicio, $request->fecha_final);

            $arregloFilterEstadosPorDias = []; $x = [];

            foreach ($Estados as $q) {
                $data = [];
                foreach($fechas['fechas'] as $v) {
                    $Cantidad = count(DB::table('clientes')->whereNull('deleted_at')->where('estado_id', $q->id)->whereDate('created_at', $v)->pluck('estado_id')->toArray());
                    if(in_array($q->id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                        $Cantidad = $Cantidad + count(DB::table('cliente_matriculas')->whereNull('deleted_at')->whereDate('created_at', $v)
                        ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                        ->pluck('id')->toArray());
                    }
                    array_push($data, $Cantidad);
                }
                array_push($x, ['name' => $q->name, 'color' => $q->background, 'data' => $data]);
            }
            array_push($arregloFilterEstadosPorDias, ['dias' => $fechas['dias'], 'data' => $x]);
        }

        $Provincias = Provincia::all();

        $arregloFilterProvincias = [];

        foreach ($Provincias as $q){
            if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                $Cantidad =  $Clientes->where('provincia_id', $q->id);
                $Cantidad = count($Cantidad->where('estado_id', $request->estado_id)->toArray());
                $Cantidad = $Cantidad + count(ClienteMatricula::whereHas('clientes', function ($query) use ($q){ $query->where('provincia_id',  $q->id); })
                        ->whereDate('created_at', '>=', $request->fecha_inicio)
                        ->whereDate('created_at', '<=', $request->fecha_final)->pluck('id')->toArray());
            }else{
                $Cantidad =  $ClientesCreated->where('provincia_id', $q->id);
                $Cantidad = count($Cantidad->pluck('provincia_id')->toArray());
            }

            if($Cantidad > 0) {
                array_push($arregloFilterProvincias, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && count($Clientes) > 0 ? ($Cantidad/count($Clientes))*100 : 0), 'count' => $Cantidad, 'drilldown' => null]);
            }
        }

        usort($arregloFilterProvincias, $this->OrdernarArreglo('count', 'DESC'));

        $Carreras = Carrera::Where('modalidad_id', App::$MODALIDAD_CARRERA)->get();
        $arregloFilterCarreras = [];

        foreach ($Carreras as $q){
            if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                $Cantidad =  $Clientes->where('carrera_id', $q->id);
                $Cantidad = count($Cantidad->where('estado_id', $request->estado_id)->toArray());
                $Cantidad = $Cantidad + count(DB::table('cliente_matriculas')->where('carrera_adicional_id', $q->id)->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->pluck('id')->toArray());
            }else{
                $Cantidad =  $ClientesCreated->where('carrera_id', $q->id);
                $Cantidad = count($Cantidad->pluck('carrera_id')->toArray());
            }

            if($Cantidad > 0){
                array_push($arregloFilterCarreras, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && count($Clientes) > 0 ? ($Cantidad/count($Clientes))*100 : 0),'count' => $Cantidad, 'drilldown' => null]);
            }
        }

        usort($arregloFilterCarreras, $this->OrdernarArreglo('count', 'DESC'));

        $Cursos = Carrera::Where('modalidad_id', App::$MODALIDAD_CURSO)->get();
        $arregloFilterCursos = [];

        foreach ($Cursos as $q){
            if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                $Cantidad =  $Clientes->where('carrera_id', $q->id);
                $Cantidad = count($Cantidad->where('estado_id', $request->estado_id)->toArray());
                $Cantidad = $Cantidad + count(DB::table('cliente_matriculas')->where('carrera_adicional_id', $q->id)->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->pluck('id')->toArray());
            }else{
                $Cantidad =  $ClientesCreated->where('carrera_id', $q->id);
                $Cantidad = count($Cantidad->pluck('carrera_id')->toArray());
            }

            if($Cantidad > 0) {
                array_push($arregloFilterCursos, [
                    'name' => $q->name, 'y' => $Cantidad > 0 ?($Cantidad/count($Clientes))*100 : $Cantidad,'count' => $Cantidad, 'drilldown' => null]);
            }
        }

        usort($arregloFilterCursos, $this->OrdernarArreglo('count', 'DESC'));

        $Modalidades = Modalidad::all();
        $arregloFilterModalidades = [];

        foreach ($Modalidades as $q){
            if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                $Cantidad =  $Clientes->where('modalidad_id', $q->id);
                $Cantidad = count($Cantidad->where('estado_id', $request->estado_id)->toArray());
                $Cantidad = $Cantidad + count(DB::table('cliente_matriculas')->where('modalidad_adicional_id', $q->id)->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->pluck('id')->toArray());
            }else{
                $Cantidad =  $ClientesCreated->where('modalidad_id', $q->id);
                $Cantidad = count($Cantidad->pluck('modalidad_id')->toArray());
            }

            if($Cantidad > 0){
                array_push($arregloFilterModalidades, [$q->name, $Cantidad, false]);
            }
        }

        $Fuentes = Fuente::all();
        $arregloFilterFuentes = [];

        foreach ($Fuentes as $q){
            if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                $Cantidad =  $Clientes->where('fuente_id', $q->id);
                $Cantidad =  count($Cantidad->where('estado_id', $request->estado_id)->pluck('fuente_id')->toArray());
                $Cantidad = $Cantidad + count(ClienteMatricula::whereHas('clientes', function ($query) use ($q){ $query->where('fuente_id',  $q->id); })
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->whereNull('deleted_at')->pluck('id')->toArray());
            }else{
                $Cantidad =  $ClientesCreated->where('fuente_id', $q->id);
                $Cantidad = count($Cantidad->pluck('fuente_id')->toArray());
            }

            if($Cantidad > 0){
                array_push($arregloFilterFuentes, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && count($Clientes) > 0 ? ($Cantidad/count($Clientes))*100 : 0),'count' => $Cantidad, 'drilldown' => null]);
            }
        }

        usort($arregloFilterFuentes, $this->OrdernarArreglo('count', 'DESC'));

        $Enterados = Enterado::all();
        $arregloFilterEnterados = [];

        foreach ($Enterados as $q){
            if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                $Cantidad =  $Clientes->where('enterado_id', $q->id);
                $Cantidad = count($Cantidad->where('estado_id', $request->estado_id)->pluck('enterado_id')->toArray());
                $Cantidad = $Cantidad + count(ClienteMatricula::whereHas('clientes', function ($query) use ($q){ $query->where('enterado_id',  $q->id); })
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->whereNull('deleted_at')->pluck('id')->toArray());
            }else{
                $Cantidad =  $ClientesCreated->where('enterado_id', $q->id);
                $Cantidad = count($Cantidad->pluck('enterado_id')->toArray());
            }

            if($Cantidad > 0) {
                array_push($arregloFilterEnterados, [
                    'name' => $q->name, 'y' => ($Cantidad > 0 && count($Clientes) > 0 ? ($Cantidad/count($Clientes))*100 : 0),'count' => $Cantidad, 'drilldown' => null]);
            }
        }

        usort($arregloFilterEnterados, $this->OrdernarArreglo('count', 'DESC'));

        $Turnos = Turno::whereNotIn('id', [App::$TURNO_GLOABAL])->get();
        $arregloFilterTurnos = [];

        foreach ($Turnos as $q){
            $Cantidad =  $Clientes->where('turno_id', $q->id);
            if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                $Cantidad = count($Cantidad->where('turno_id', $request->turno_id)->pluck('turno_id')->toArray()) ;
                $Cantidad = $Cantidad + count(DB::table('cliente_matriculas')->where('turno_adicional_id', $q->id)->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->pluck('id')->toArray());
            }else{
                $Cantidad = count($Cantidad->pluck('turno_id')->toArray());
            }
            array_push($arregloFilterTurnos, [$q->name, $Cantidad, false]);
        }

        if ($request->action_full == "false") {

            $Turnos = Turno::whereNotIn('id', [App::$TURNO_GLOABAL])->get();
            $arregloFilterTurnos = [];

            foreach ($Turnos as $q) {
                $Cantidad = $Clientes->where('turno_id', $q->id);
                if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                    $Cantidad = count($Cantidad->where('estado_id', $request->estado_id)->toArray());
                    $Cantidad = $Cantidad + count(DB::table('cliente_matriculas')->where('turno_adicional_id', $q->id)->whereNull('deleted_at')
                    ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                    ->pluck('id')->toArray());
                }else{
                    $Cantidad = count($Cantidad->pluck('turno_id')->toArray());
                }
                array_push($arregloFilterTurnos, [$q->name, $Cantidad, false]);
            }
            
            $Usuarios = User::whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA])->where('activo', 1)->get();
            $arregloFilterUsuarios = [];
            
            foreach ($Usuarios as $q) {
                $Cantidad = $Clientes->where('user_id', $q->id);
                if(in_array($request->estado_id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])){
                    $Cantidad = count($Cantidad->where('estado_id', $request->estado_id)->pluck('user_id')->toArray());
                    $Cantidad = $Cantidad + count(ClienteMatricula::whereHas('clientes', function ($query) use ($q) { $query->where('user_id', $q->id); })->whereNull('deleted_at')
                    ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                    ->pluck('id')->toArray());
                }else{
                    $Cantidad = count($Cantidad->pluck('user_id')->toArray());
                }
                array_push($arregloFilterUsuarios, [
                    'color' => "#2ECC71", 'name' => $q->name, 'y' => $Cantidad > 0 ? ($Cantidad / count($Clientes)) * 100 : $Cantidad, 'count' => $Cantidad, 'drilldown' => null]);
            }

            usort($arregloFilterUsuarios, $this->OrdernarArreglo('count', 'DESC'));

        }


        if ($request->action_full == "true") {

            return response()->json([
                'Estados' => $arregloFilterEstados,
                'EstadosGlobal' => $arregloFilterEstadosGlobal,
                'Acciones' => $arregloFilterAcciones,
                'RegistroDias' => $arregloFilterEstadosPorDias,
                'Provincias' => $arregloFilterProvincias,
                'Carreras' => $arregloFilterCarreras,
                'Cursos' => $arregloFilterCursos,
                'Modalidades' => $arregloFilterModalidades,
                'Fuentes' => $arregloFilterFuentes,
                'Enterados' => $arregloFilterEnterados,
                'Clientes' => count($ClientesCreated)
            ]);
        }

        return response()->json([
            'Provincias' => $arregloFilterProvincias,
            'Carreras' => $arregloFilterCarreras,
            'Cursos' => $arregloFilterCursos,
            'Modalidades' => $arregloFilterModalidades,
            'Fuentes' => $arregloFilterFuentes,
            'Enterados' => $arregloFilterEnterados,
            'Turnos' =>  $arregloFilterTurnos,
            'Usuarios' => $arregloFilterUsuarios,
            'Clientes' => count($ClientesCreated)
        ]);
    }

    public function vendedores(){
        $Vendedores = User::where('profile_id', App::$PERFIL_VENDEDOR)->get();
        return view('auth.reporte.vendedores', ['Vendedores' => $Vendedores]);
    }

    public function filtro_vendedores(Request $request)
    {
        $Clientes = Cliente::whereNull('deleted_at')
            ->whereHas('users', function ($query) { $query->where('profile_id',  App::$PERFIL_VENDEDOR); } )
            ->where(function ($q) use ($request){ if($request->fecha_inicio){ $q->whereDate('ultimo_contacto', '>=', $request->fecha_inicio); }})
            ->where(function ($q) use ($request){ if($request->fecha_final){ $q->whereDate('ultimo_contacto', '<=', $request->fecha_final);}})
            ->get();

        $ClientesCreated = Cliente::whereNull('deleted_at')
            ->whereHas('users', function ($query) { $query->where('profile_id',  App::$PERFIL_VENDEDOR); } )
            ->where(function ($q) use ($request){ if($request->fecha_inicio){ $q->whereDate('created_at', '>=', $request->fecha_inicio); }})
            ->where(function ($q) use ($request){ if($request->fecha_final){ $q->whereDate('created_at', '<=', $request->fecha_final);}})
            ->get();

        $Estados = Estado::all();

        $arregloFilterEstadosGlobal = []; $CantidadtTotal = 0;

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

        if(Auth::guard('web')->user()->profile_id == App::$PERFIL_VENDEDOR) {
            $Clientes = $Clientes->where('user_id', Auth::guard('web')->user()->id);
            $ClientesCreated = $ClientesCreated->where('user_id', Auth::guard('web')->user()->id);
        }else {
            $Clientes = $request->vendedor_id ? $Clientes->where('user_id', $request->vendedor_id) : $Clientes;
            $ClientesCreated = $request->vendedor_id ? $ClientesCreated->where('user_id', $request->vendedor_id) : $ClientesCreated;
        }

        array_push($arregloFilterEstadosGlobal, ["TOTAL", count($ClientesCreated)]);

        foreach ($Estados as $q){

            if(!in_array($q->id, [App::$ESTADO_OTROS, App::$ESTADO_NOCONTACTADO, App::$ESTADO_PERDIDO])) {

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

        if(Auth::guard('web')->user()->profile_id == App::$PERFIL_VENDEDOR) {
            $matriculados = count(ClienteMatricula::whereHas('clientes', function ($query) use ($request) { $query->where('user_id', Auth::guard('web')->user()->id);  })
                ->whereNull('deleted_at')->whereDate('created_at', '>=', $request->fecha_inicio)
                ->whereDate('created_at', '<=', $request->fecha_final)->pluck('id')->toArray());;
        }else {
            $matriculados = count(ClienteMatricula::whereHas('clientes', function ($query) use ($request) { if($request->vendedor_id){ $query->where('user_id', $request->vendedor_id); } })
            ->whereNull('deleted_at')->whereDate('created_at', '>=', $request->fecha_inicio)
            ->whereDate('created_at', '<=', $request->fecha_final)->pluck('id')->toArray());
        }

        array_push($arregloFilterVendedorEstados, [
            'estado_id' => 0, 'name' => "TODOS", 'y' => (count($Clientes) + $matriculados) > 0 ?((count($Clientes) + $matriculados)/(count($Clientes) + $matriculados))*100 : (count($Clientes) + $matriculados) ,
            'count' => (count($Clientes) + $matriculados), 'drilldown' => "TODOS", 'color' => "#7C4DFF"]);

        foreach ($Estados as $q){
            $Cantidad = count($Clientes->where('estado_id', $q->id)->pluck('estado_id')->toArray());
            if(in_array($q->id, [App::$ESTADO_CIERRE, App::$ESTADO_REINGRESO])) {
                $Cantidad = $Cantidad + count(ClienteMatricula::whereHas('clientes', function ($query) use ($request) { if($request->vendedor_id){ $query->where('user_id', $request->vendedor_id); } })->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $request->fecha_inicio)->whereDate('created_at', '<=', $request->fecha_final)
                ->pluck('id')->toArray());
            }
            array_push($arregloFilterVendedorEstados, [
                'estado_id' => $q->id, 'name' => $q->name, 'y' => $Cantidad > 0 ?($Cantidad/count($Clientes))*100 : $Cantidad, 'count' => $Cantidad,
                'drilldown' => $q->name, 'color' => $q->background
            ]);
        }

        $EstadosDetalles = EstadoDetalle::all();

        $arregloFilterVendedorEstadosDetalle = [];

        foreach ($arregloFilterVendedorEstados as $q){

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

        foreach ($Acciones as $q){
            $Cantidad = count(ClienteSeguimiento::with('clientes')->where('accion_id', $q->id)
                ->whereHas('clientes', function ($q) use ($request){ if($request->vendedor_id && $request->vendedor_id != "undefined"){ $q->where('user_id', $request->vendedor_id); }})
                ->whereHas('clientes', function ($q) use ($profile_id){ if($profile_id == App::$PERFIL_VENDEDOR){ $q->where('user_id', Auth::guard('web')->user()->id); }})
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

    public function OrdernarArreglo($elemento,$orden=null) {
        return function ($a, $b) use ($elemento,$orden) {
            $result =  ($orden=="DESC") ? strnatcmp($b[$elemento], $a[$elemento]) :  strnatcmp($a[$elemento], $b[$elemento]);
            return $result;
        };
    }

}
