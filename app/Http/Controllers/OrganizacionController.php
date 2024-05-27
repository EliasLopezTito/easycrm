<?php

namespace Incidencias\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Incidencias\App;
use Incidencias\Cargo;
use Incidencias\ControlGar;
use Incidencias\ControlGarPersonal;
use Incidencias\Grupo;
use Incidencias\GrupoTurnoIncidencia;
use Incidencias\Incidencia;
use Incidencias\IncidenciaOrganizacion;
use Incidencias\IncidenciaParqueAutomotor;
use Incidencias\IncidenciaPersonal;
use Incidencias\IncidenciaPersonalFijo;
use Incidencias\IncidenciaSector;
use Incidencias\Macro;
use Incidencias\Organizacion;
use Incidencias\OrganizacionPersonal;
use Incidencias\OrganizacionPersonalServicio;
use Incidencias\OrganizacionSector;
use Incidencias\OrganizacionServicio;
use Incidencias\ParqueAutomotor;
use Incidencias\PersonalFijo;
use Incidencias\Sector;
use Incidencias\Servicio;
use Incidencias\Turno;
use Incidencias\TurnoOrganizacion;
use Incidencias\User;

class OrganizacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $turnoOrganizacion = TurnoOrganizacion::where('fecha_cierre', null)->first();

        if(!in_array(Auth::user()->perfil_id, [App::$PERFIL_ADMINISTRADOR])){
            if ($turnoOrganizacion->perfil_id != Auth::user()->perfil_id){
                Auth::logout();
                return redirect('/login');
            }
        }

        $sectores = Sector::orderby('name', 'asc')->get();
        $turnos = Turno::whereNotIn('id', [App::$TURNO_NINGUNO])->get();

        $servicios = Servicio::all();
        $organizacion = Organizacion::where('id', $turnoOrganizacion->organizacion_id)->with('turnos')->first();
        $organizacionServicios = OrganizacionServicio::where('organizacion_id', $organizacion->id)->get();
        $organizacionSectors = OrganizacionSector::where('organizacion_id', $organizacion->id)->get();
        $organizacionPersonals = OrganizacionPersonal::where('organizacion_id', $organizacion->id)->get();
        $organizacionPersonalsServicio = OrganizacionPersonalServicio::where('organizacion_id', $organizacion->id)->get();

        return view('auth.organizacion.index', ['sectores' => $sectores, 'servicios' => $servicios, 'organizacion' => $organizacion, 'turnos' => $turnos,
            'organizacionSectors' => $organizacionSectors, 'organizacionServicios' => $organizacionServicios, 'organizacionPersonals' => $organizacionPersonals,
            'organizacionPersonalsServicio' => $organizacionPersonalsServicio]);
    }

    public function list_all()
    {
        return response()->json(['data' => Organizacion::all()]);
    }

    public function store(Request $request)
    {
        $status = false; $return = false;
        $OrganizacionServices = json_decode($request->get('OrganizacionServices'));
        $OrganizacionSectors = json_decode($request->get('OrganizacionSectors'));
        $OrganizacionPersonalsServicio = json_decode($request->get('OrganizacionPersonalsServicio'));
        $OrganizacionPersonalsMotivo = json_decode($request->get('OrganizacionPersonalsMotivo'));
        $Organizacion = Organizacion::where('id', $request->get('id'))->first();

        $Incidencia = GrupoTurnoIncidencia::where('fecha_cierre', null)->first();

        if ($request->get('estado_id') == App::$ESTADO_FINALIZADO) {
            $turnoOrganizacion = TurnoOrganizacion::where('organizacion_id', $Organizacion->id)->where('fecha_cierre', null)->first();

            if($turnoOrganizacion == null) {
                return response()->json(['Success' => $status, 'Message' => 'Esta Organización de Servicio ya fue cerrada']);
            }else{
                $Organizacion->estado_id = $request->get('estado_id');
                if(Auth::user()->perfil_id != App::$PERFIL_ADMINISTRADOR){
                    $Organizacion->user_id = Auth::user()->id;
                    $Organizacion->user_name = Auth::user()->user_alternative ? Auth::user()->user_name_alternative . " (Suplente)" : Auth::user()->name;
                    $Organizacion->efectivo = $request->get('efectivo');
                    $Organizacion->descuento = $request->get('descuento');
                    $Organizacion->total = $request->get('total');
                }

                if($Organizacion->save()){

                    $status = false;

                    $turnoOrganizacionTarde = TurnoOrganizacion::whereDate('fecha_abierta',
                        Carbon::now()->toDateString())->where('turno_id', App::$TURNO_MANIANA)->where('perfil_id', Auth::user()->perfil_id)->first();

                    $turnoOrganizacion->estado_id = App::$ESTADO_FINALIZADO;
                    $turnoOrganizacion->fecha_cierre = Carbon::now();
                    $turnoOrganizacion->save();

                    $incidenciaOrganizacion = new IncidenciaOrganizacion();
                    $incidenciaOrganizacion->incidencia_id = $Incidencia->id;
                    $incidenciaOrganizacion->organizacion_id = $Organizacion->id;
                    $incidenciaOrganizacion->estado_id = App::$ESTADO_FINALIZADO;
                    $incidenciaOrganizacion->save();

                    $perfil_id = $turnoOrganizacion->perfil_id;
                    $turno_id = $turnoOrganizacion->turno_id;

                    if($perfil_id == App::$PERFIL_SUPERVISORCECOM3) $perfil_id = App::$PERFIL_SUPERVISORCECOM1;
                    else $perfil_id++;

                    if($turno_id == App::$TURNO_MANIANA){ $turno_id = App::$TURNO_TARDE; $perfil_id = $turnoOrganizacion->perfil_id; }
                    else if($turno_id == App::$TURNO_TARDE){

                        if($turnoOrganizacionTarde != null){
                            $status = true; $turno_id = App::$TURNO_TARDE;
                        }else{
                            $turno_id = App::$TURNO_NOCHE;
                            $perfil_id = $turnoOrganizacion->perfil_id;
                        }

                    }
                    else if($turno_id == App::$TURNO_NOCHE){ $turno_id = App::$TURNO_MANIANA; }

                    $nuevaOrganizacion = new Organizacion();
                    $nuevaOrganizacion->turno_id = $turno_id;
                    $nuevaOrganizacion->estado_id = App::$ESTADO_ACTIVO;

                    if($nuevaOrganizacion->save()) {

                        $nuevoTunoOrganizacion = new TurnoOrganizacion();
                        $nuevoTunoOrganizacion->turno_id = $turno_id;
                        $nuevoTunoOrganizacion->organizacion_id = $nuevaOrganizacion->id;
                        $nuevoTunoOrganizacion->perfil_id = $perfil_id;
                        $nuevoTunoOrganizacion->estado_id = App::$ESTADO_ACTIVO;
                        $nuevoTunoOrganizacion->fecha_abierta = date("Y-m-d H:i:s");
                        $nuevoTunoOrganizacion->save();

                        if($status){
                            DB::beginTransaction();
                            try {
                                foreach ($OrganizacionServices as $os){
                                    DB::table('organizacion_servicios')->insert(['organizacion_id' => $nuevaOrganizacion->id,
                                        'servicio_id' => $os->service_id, 'cantidad' => $os->cantidad]);
                                }

                                foreach ($OrganizacionSectors as $os) {
                                    DB::table('organizacion_sectors')->insert(['organizacion_id' => $nuevaOrganizacion->id,
                                        'sector_id' => $os->sector_id, 'cantidad' => $os->cantidad]);
                                }

                                if($OrganizacionPersonalsServicio != null && count($OrganizacionPersonalsServicio) > 0){
                                    foreach ($OrganizacionPersonalsServicio as $ip){
                                        DB::table('organizacion_personal_servicios')->insert(['organizacion_id' => $nuevaOrganizacion->id,
                                            'trabajador_id' => $ip->trabajador_id, 'servicio_id' => $ip->servicio_id,
                                            'sector_id' => App::$SECTOR_1, 'regimen' => $ip->regimen]);
                                    }
                                }

                                if($OrganizacionPersonalsMotivo != null && count($OrganizacionPersonalsMotivo) > 0){
                                    foreach ($OrganizacionPersonalsMotivo as $ip){
                                        DB::table('organizacion_personals')->insert(['organizacion_id' => $nuevaOrganizacion->id,
                                            'trabajador_id' => $ip->trabajador_id, 'motivo_personal_id' => $ip->motivo_personal_id,
                                            'servicio_id' => $ip->servicio_id, 'regimen' => $ip->regimen]);
                                    }
                                }

                                DB::commit();

                            }catch (\Exception $e){
                                DB::rollBack();
                            }
                        }
                    }
                }
            }

            $return = true;
        }

        DB::beginTransaction();

        try {

            foreach ($OrganizacionServices as $os) {
                $OrganizacionServicio = OrganizacionServicio::where('organizacion_id', $os->organizacion_id)->where('servicio_id', $os->service_id)->first();
                if($OrganizacionServicio == null){
                    DB::table('organizacion_servicios')->insert(['organizacion_id' => $os->organizacion_id, 'servicio_id' => $os->service_id, 'cantidad' => $os->cantidad]);
                }else{
                    DB::table('organizacion_servicios')->where('id', $OrganizacionServicio->id)->update(['servicio_id' =>  $os->service_id, 'cantidad' => $os->cantidad]);
                }
            }

            foreach ($OrganizacionSectors as $os) {
                $OrganizacionSector = OrganizacionSector::where('organizacion_id', $os->organizacion_id)->where('sector_id', $os->sector_id)->first();
                if($OrganizacionSector == null){
                    DB::table('organizacion_sectors')->insert(['organizacion_id' => $os->organizacion_id, 'sector_id' => $os->sector_id, 'cantidad' => $os->cantidad]);
                }else{
                    DB::table('organizacion_sectors')->where('id', $OrganizacionSector->id)->update(['sector_id' =>  $os->sector_id, 'cantidad' => $os->cantidad]);
                }
            }

            $OldOrganizacionPersonalsServicio = OrganizacionPersonalServicio::where('organizacion_id', $Organizacion->id)->pluck('id')->toArray();
            $arrayPersonalServicio_ids = [];

            if($OrganizacionPersonalsServicio != null && count($OrganizacionPersonalsServicio) > 0){
                foreach ($OrganizacionPersonalsServicio as $ip){
                    $OrganizacionPersonalServicio = null;
                    if($ip->id != null && $ip->id != 0) {
                        $OrganizacionPersonalServicio = OrganizacionPersonalServicio::find($ip->id);
                        array_push($arrayPersonalServicio_ids, $ip->id);
                    }
                    if($OrganizacionPersonalServicio == null){
                        DB::table('organizacion_personal_servicios')
                            ->insert(['organizacion_id' => $ip->organizacion_id, 'trabajador_id' => $ip->trabajador_id, 'servicio_id' => $ip->servicio_id,
                                'sector_id' => App::$SECTOR_1, 'regimen' => $ip->regimen]);
                    }else{
                        DB::table('organizacion_personal_servicios')
                            ->where('id', $OrganizacionPersonalServicio->id)
                            ->update(['organizacion_id' => $ip->organizacion_id, 'trabajador_id' => $ip->trabajador_id, 'servicio_id' => $ip->servicio_id,
                                'sector_id' => $ip->sector_id, 'regimen' => $ip->regimen]);
                    }
                }
            }

            if(count($arrayPersonalServicio_ids) <= 0){
                foreach ($OldOrganizacionPersonalsServicio as $r){
                    DB::table('organizacion_personal_servicios')->where('id', $r)->delete();
                }
            }else{
                $result = array_diff($OldOrganizacionPersonalsServicio, $arrayPersonalServicio_ids);
                if(count($result) > 0){
                    foreach ($result as $r){
                        DB::table('organizacion_personal_servicios')->where('id', $r)->delete();
                    }
                }
            }

            $OldOrganizacionPersonalsMotivo = OrganizacionPersonal::where('organizacion_id', $Organizacion->id)->pluck('id')->toArray();
            $arrayPersonalMotivo_ids = [];

            if($OrganizacionPersonalsMotivo != null && count($OrganizacionPersonalsMotivo) > 0){
                foreach ($OrganizacionPersonalsMotivo as $ip){
                    $OrganizacionMotivo = null;
                    if($ip->id != null && $ip->id != 0) {
                        $OrganizacionMotivo = OrganizacionPersonal::find($ip->id);
                        array_push($arrayPersonalMotivo_ids, $ip->id);
                    }
                    if($OrganizacionMotivo == null){
                        DB::table('organizacion_personals')
                            ->insert(['organizacion_id' => $ip->organizacion_id, 'trabajador_id' => $ip->trabajador_id, 'motivo_personal_id' => $ip->motivo_personal_id,
                                'servicio_id' => $ip->servicio_id, 'regimen' => $ip->regimen]);
                    }else{
                        DB::table('organizacion_personals')
                            ->where('id', $OrganizacionMotivo->id)
                            ->update(['organizacion_id' => $ip->organizacion_id, 'trabajador_id' => $ip->trabajador_id, 'motivo_personal_id' => $ip->motivo_personal_id,
                                'servicio_id' => $ip->servicio_id, 'regimen' => $ip->regimen]);
                    }
                }
            }

            if(count($arrayPersonalMotivo_ids) <= 0){
                foreach ($OldOrganizacionPersonalsMotivo as $r){
                    DB::table('organizacion_personals')->where('id', $r)->delete();
                }
            }else{
                $result = array_diff($OldOrganizacionPersonalsMotivo, $arrayPersonalMotivo_ids);
                if(count($result) > 0){
                    foreach ($result as $r){
                        DB::table('organizacion_personals')->where('id', $r)->delete();
                    }
                }
            }

            $status = true;
            $message = "Registro/Guardado Correctamente";

            DB::commit();

        }catch (\Exception $e){
            $message = $e->getMessage();
            DB::rollBack();
        }

        return response()->json(['Success' => $status, 'Message' => $message, 'Return' => $return]);
    }

    public function filtro_fecha(Request $request){

        $turno = $request->get('turno');

        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR){
            $organizacion = Organizacion::whereDate('created_at',
                date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                ->where('turno_id', $turno)->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->latest()->first();

        }else{

            $organizacion = Organizacion::whereDate('created_at',
                date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                ->where('user_id', Auth::user()->id)->where('turno_id', $turno)->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->first();

            if($organizacion == null){

                $turnoOrganizacion = TurnoOrganizacion::where('fecha_cierre', null)->first();

                $organizacion = Organizacion::whereDate('created_at',
                    date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                    ->where('id', $turnoOrganizacion->id)->where('turno_id', $turno)->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                    ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                    ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->first();

            }
        }

        $sectores = Sector::all()->pluck('id')->toArray();
        $servicios = Servicio::all()->pluck('id')->toArray();

        return response()->json([ 'organizacion' => $organizacion, 'sectores' => $sectores, 'servicios' => $servicios ]);

    }

    public function partialView($id){

        $entity = IncidenciaOrganizacion::where('incidencia_id', $id)
            ->with('incidencias')->with('organizaciones')->with('estados')
            ->get();

        return view('auth.organizacion._Visualizar', ['IncidenciaOrganizacion' => $entity]);

    }

    public function print_filter_pdf($fecha, $turno, $status)
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR){

            $organizacion = Organizacion::whereDate('created_at', $fecha)
                ->where('turno_id', $turno)->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->latest()->first();

            if($organizacion == null && $status == 0){
                $turnoOrganizacion = TurnoOrganizacion::where('fecha_cierre', null)->first();

                $organizacion = Organizacion::where('id', $turnoOrganizacion->organizacion_id)
                    ->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                    ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                    ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->latest()->first();
            }

        }else{

            $organizacion = Organizacion::whereDate('created_at',$fecha)
                ->where('user_id', Auth::user()->id)->where('turno_id', $turno)->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->first();

            if($organizacion == null){

                $turnoOrganizacion = TurnoOrganizacion::where('fecha_cierre', null)->first();

                $organizacion = Organizacion::whereDate('created_at',$fecha)
                    ->where('id', $turnoOrganizacion->id)->where('turno_id', $turno)->with('personalesServicio')->with('personalesServicio.trabajadores')->with('personalesServicio.sectores')
                    ->with('personalesServicio.servicios')->with('personalesMotivo')->with('personalesMotivo.trabajadores')->with('personalesMotivo.motivosPersonal')
                    ->with('personalesMotivo.servicios')->with('turnos')->with('servicios')->with('sectores')->first();

            }
        }

        $sectores = Sector::all()->pluck('id')->toArray();
        $servicios = Servicio::all()->pluck('id')->toArray();

        if($organizacion == null){
            return view('auth.error.index');
        }

        $data = array(
            'fecha' => $fecha,'turno' => $turno,
            'organizacion' => $organizacion,
            'sectores' => $sectores, 'servicios' => $servicios
        );

        $pdf = PDF::loadView('auth.exports.organizacionServicio.fichaPDF', $data);
        return $pdf->download('OrganizacionServicio.pdf');
    }
}
