<?php

namespace Incidencias\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Incidencias\App;
use Incidencias\Arma;
use Incidencias\Categoria;
use Incidencias\ClasificacionIncidencia;
use Incidencias\Image;
use Incidencias\IncidenciaRelevante;
use Incidencias\LugarIncidencia;
use Incidencias\Macro;
use Incidencias\ModalidadIncidencia;
use Incidencias\Organizacion;
use Incidencias\OrganizacionPersonalServicio;
use Incidencias\Sector;
use Incidencias\SubSector;
use Incidencias\Trabajador;
use Incidencias\Turno;
use Incidencias\TurnoOrganizacion;
use Incidencias\Vehiculo;
use function GuzzleHttp\Promise\all;

class IncidenciaRelevanteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $turnoOrganizacion = TurnoOrganizacion::where('fecha_cierre', null)->first();

        $turnos = Turno::whereNotIn('id', [App::$TURNO_NINGUNO])->get();

        return view('auth.incidenciaRelevante.index', ['turnos' => $turnos, 'turnoOrganizacion' => $turnoOrganizacion]);
    }

    public function list_all(Request $request)
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR){
            $IncidenciaRelevantes = IncidenciaRelevante::with('turnos')->with('users')->with('categories')->with('trabajadors')->with('lugares')
                ->with('clasificacionIncidencias')->with('modalidadIncidencias')->with('vehiculos')->with('armas')->with('macros')->with('sectors')->with('subsectors')
                ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                ->where('turno_id', $request->get('turno'))
                ->get();
        }else{

            if($request->get('turno') == App::$TURNO_TARDE){

                //$incidenciaRelevante = null;

                /**/

                $incidenciaRelevante = Organizacion::whereDate('created_at',
                    date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                    ->where('turno_id', App::$TURNO_MANIANA)->first();

                /*if($incidenciaRelevante == null){

                    $incidenciaRelevante = IncidenciaRelevante::whereDate('fecha_registro',
                        date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                        ->where('turno_id', App::$TURNO_MANIANA)->first();

                }*/

                if($incidenciaRelevante != null && $incidenciaRelevante->user_id != Auth::user()->id){

                    $IncidenciaRelevantes = IncidenciaRelevante::with('turnos')->with('users')->with('categories')->with('trabajadors')->with('lugares')
                        ->with('clasificacionIncidencias')->with('modalidadIncidencias')->with('vehiculos')->with('armas')->with('macros')->with('sectors')->with('subsectors')
                        ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                        ->where('turno_id', $request->get('turno'))
                        ->get();

                }else{

                    $IncidenciaRelevantes = IncidenciaRelevante::with('turnos')->with('users')->with('categories')->with('trabajadors')->with('lugares')
                        ->with('clasificacionIncidencias')->with('modalidadIncidencias')->with('vehiculos')->with('armas')->with('macros')->with('sectors')->with('subsectors')
                        ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                        ->whereIn('user_id', [1, Auth::user()->id])
                        ->where('turno_id', $request->get('turno'))
                        ->get();
                }

            }else{

                $IncidenciaRelevantes = IncidenciaRelevante::with('turnos')->with('users')->with('categories')->with('trabajadors')->with('lugares')
                    ->with('clasificacionIncidencias')->with('modalidadIncidencias')->with('vehiculos')->with('armas')->with('macros')->with('sectors')->with('subsectors')
                    ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                    ->whereIn('user_id', [1, Auth::user()->id])
                    ->where('turno_id', $request->get('turno'))
                    ->get();
            }
        }

        return response()->json(['data' => $IncidenciaRelevantes]);
    }

    public function partialView($id)
    {
        $entity = null;
        $turnoOrganizacion = TurnoOrganizacion::with('turnos')->where('fecha_cierre', null)->first();
        $categorias = Categoria::all();
        $vehiculos = Vehiculo::all();
        $armas = Arma::all();
        $macroSectors = Macro::whereNotIn('id', [App::$MACRO_TODOS, App::$MACRO_NINGUNO])->get();
        $sectors = Sector::all();
        $subsectors = SubSector::all();
        $clasificacionIncidencias = ClasificacionIncidencia::all();
        $modalidadIncidencias = ModalidadIncidencia::all();
        $turnos = Turno::whereNotIn('id', [App::$TURNO_NINGUNO])->get();

        $trabajadoresServicio = DB::table('organizacion_personal_servicios')
            ->join('trabajadors', 'trabajadors.id', '=', 'organizacion_personal_servicios.trabajador_id')
            ->where('organizacion_personal_servicios.deleted_at',  null)
            ->where('organizacion_personal_servicios.organizacion_id', $turnoOrganizacion->organizacion_id)
            ->select('trabajadors.id')
            ->groupBy('trabajadors.id')
            ->get();

        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR){
            $trabajadores = Trabajador::all();
        }else{
            $trabajadores = Trabajador::whereIn("id", $trabajadoresServicio->pluck('id'))->get();
        }

        $lugarIncidencias = LugarIncidencia::all();

        //Trabajador::where('personalCargo_id', App::$PERSONALCARGO_OPERADOR_CAMARA)->get();

        if($id != 0) $entity = IncidenciaRelevante::find($id);

        return view('auth.incidenciaRelevante._Maintenance', ['IncidenciaRelevante' => $entity, 'SubSectors' => $subsectors,
            'TurnoOrganizacion' => $turnoOrganizacion, 'Categorias' => $categorias, 'Vehiculos' => $vehiculos,
            'ClasificacionIncidencias' => $clasificacionIncidencias, 'ModalidadIncidencias' => $modalidadIncidencias,
            'Armas' => $armas, 'MacroSectors' => $macroSectors, 'Sectors' => $sectors, 'Trabajadors' => $trabajadores,
            'LugarIncidencias' => $lugarIncidencias, 'Turnos' => $turnos]);
    }

    public function store(Request $request)
    {
        $status = false; $image_one_id = null; $image_two_id = null; $image_three_id = null;
        $exist = IncidenciaRelevante::where('nro_incidencia', $request->get('nro_incidencia'))->where('deleted_at', NULL)->first();

        if($request->file('imageOne') != null){
            $random = Str::upper(str_random(4));
            $file_name = uniqid($random . "_") . '.' . $request->file('imageOne')->getClientOriginalExtension();
            $image = new Image();$image->name = $file_name;
            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen Referencial 1!!!']);

            $request->file('imageOne')->move(public_path('uploads/cecoms/'), $file_name);
            $image_one_id = $image->id;
        }else{
            $image_one_id = 2;
        }

        if($request->file('imageTwo') != null){
            $random = Str::upper(str_random(4));
            $file_name = uniqid($random . "_") . '.' . $request->file('imageTwo')->getClientOriginalExtension();
            $image = new Image();$image->name = $file_name;
            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen Referencial 2!!!']);

            $request->file('imageTwo')->move(public_path('uploads/cecoms/'), $file_name);
            $image_two_id = $image->id;
        }else{
            $image_two_id = 2;
        }

        if($request->file('imageThree') != null){
            $random = Str::upper(str_random(4));
            $file_name = uniqid($random . "_") . '.' . $request->file('imageThree')->getClientOriginalExtension();
            $image = new Image();$image->name = $file_name;
            if(!$image->save())
                return response()->json(['Success' => $status, 'Message' => 'No se pudo registrar la Imagen Referencial 3!!!']);

            $request->file('imageThree')->move(public_path('uploads/cecoms/'), $file_name);
            $image_three_id = $image->id;
        }else{
            $image_three_id = 2;
        }

        if($request->get('id') != 0){

            if($exist != null && $exist->id != $request->get('id'))
                return response()->json(['Success' => $status, 'Message' => 'El N° de Incidencia '.$request->get('nro_incidencia'). ' ya está registrada.']);

            $entity =  IncidenciaRelevante::find($request->get('id'));

            if($image_one_id == null || $image_one_id == 2)
                $image_one_id = $entity->image_one_id;

            if($image_two_id == null || $image_two_id == 2)
                $image_two_id = $entity->image_two_id;

            if($image_three_id == null || $image_three_id == 2)
                $image_three_id = $entity->image_three_id;

        }else{

            if($exist != null)
                return response()->json(['Success' => $status, 'Message' => 'El N° de Incidencia '.$request->get('nro_incidencia'). ' ya está registrada.']);

            $entity = new IncidenciaRelevante();
        }

        $entity->image_one_id = $image_one_id;
        $entity->image_two_id = $image_two_id;
        $entity->image_three_id = $image_three_id;
        $entity->nro_incidencia = $request->get('nro_incidencia');
        $entity->turno_id = $request->get('turno_id');
        $entity->user_id = $request->get('user_id');
        $entity->fecha = date('Y-m-d', strtotime(str_replace('/', '-', $request->get('fecha'))));
        $entity->hora = $request->get('hora');
        $entity->lugar_incidencia_id =  $request->get('lugar_incidencia_id');
        $entity->nro_calle =  $request->get('nro_calle');
        $entity->longitud =  $request->get('longitud');
        $entity->latitud =  $request->get('latitud');
        $entity->categoria_id = $request->get('categoria_id');
        $entity->clasificacionIncidencia_id = $request->get('clasificacionIncidencia_id');
        $entity->modalidadIncidencia_id = $request->get('modalidadIncidencia_id');
        $entity->vehiculo_id = $request->get('vehiculo_id');
        $entity->placa = strtoupper("NINGUNO");
        $entity->arma_id = $request->get('arma_id');
        $entity->objeto =  strtoupper($request->get('objeto'));
        $entity->arma_id = $request->get('arma_id');
        $entity->macro_id = $request->get('macro_id');
        $entity->sector_id = $request->get('sector_id');
        $entity->subsector_id = $request->get('subsector_id');
        $entity->descripcion_incidencia = strtoupper($request->get('descripcion_incidencia'));
        $entity->trabajador_id = $request->get('trabajador_id');
        if(App::$PERFIL_ADMINISTRADOR == Auth::user()->perfil_id)
            $entity->fecha_registro = date('Y-m-d', strtotime(str_replace('/', '-', $request->get('fecha_registro'))));
        else
            $entity->fecha_registro =  Carbon::now()->toDateString();

        if($entity->save()) $status = true;

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = IncidenciaRelevante::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }

}
