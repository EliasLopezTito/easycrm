<?php

namespace Incidencias\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Incidencias\App;
use Incidencias\Camioneta;
use Incidencias\IncidenciaRelevante;
use Incidencias\Organizacion;
use Incidencias\PatrullajeIntegrado;
use Incidencias\PatrullajeIntegradoSector;
use Incidencias\Sector;
use Incidencias\Trabajador;
use Incidencias\Turno;
use Incidencias\TurnoOrganizacion;

class PatrullajeIntegradoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $turnoOrganizacion = TurnoOrganizacion::with('turnos')->where('fecha_cierre', null)->first();
        $turnos = Turno::whereNotIn('id', [App::$TURNO_NINGUNO])->get();

        return view('auth.patrullajeIntegrado.index',['turnos' => $turnos, 'turnoOrganizacion' => $turnoOrganizacion]);
    }

    public function list_all(Request $request)
    {
        if(Auth::user()->perfil_id == App::$PERFIL_ADMINISTRADOR){
            $PatrullajeIntegrado = PatrullajeIntegrado::with('turnos')->with('users')->with('camionetas')
                ->with('trabajadors')->with('estados')->with('zonas')->with('zonas.sectors')
                ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                ->where('turno_id', $request->get('turno'))
                ->get();
        }else{

            if($request->get('turno') == App::$TURNO_TARDE) {

                /*$PatrullajeIntegrado = PatrullajeIntegrado::whereDate('fecha_registro',
                    date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                    ->where('turno_id', App::$TURNO_MANIANA)->first();*/

                $PatrullajeIntegrado = Organizacion::whereDate('created_at',
                    date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                    ->where('turno_id', App::$TURNO_MANIANA)->first();

                if ($PatrullajeIntegrado != null && $PatrullajeIntegrado->user_id != Auth::user()->id) {

                    $PatrullajeIntegrado = PatrullajeIntegrado::with('turnos')->with('users')->with('camionetas')
                        ->with('trabajadors')->with('estados')->with('zonas')->with('zonas.sectors')
                        ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                        ->where('turno_id', $request->get('turno'))
                        ->get();

                } else {

                    $PatrullajeIntegrado = PatrullajeIntegrado::with('turnos')->with('users')->with('camionetas')
                        ->with('trabajadors')->with('estados')->with('zonas')->with('zonas.sectors')
                        ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                        ->whereIn('user_id', [1, Auth::user()->id])
                        ->where('turno_id', $request->get('turno'))
                        ->get();

                }

            } else{
                $PatrullajeIntegrado = PatrullajeIntegrado::with('turnos')->with('users')->with('camionetas')
                    ->with('trabajadors')->with('estados')->with('zonas')->with('zonas.sectors')
                    ->whereDate('fecha_registro', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                    ->whereIn('user_id', [1, Auth::user()->id])
                    ->where('turno_id', $request->get('turno'))
                    ->get();
            }

        }

        return response()->json(['data' => $PatrullajeIntegrado]);
    }

    public function partialView($id)
    {
        $entity = null;
        $turnoOrganizacion = TurnoOrganizacion::with('turnos')->where('fecha_cierre', null)->first();
        $camionetas = Camioneta::all();
        $sectors = Sector::all();
        $trabajadors = Trabajador::all();
        $patrullajeIntegradoSector = PatrullajeIntegradoSector::where('patrullajeIntegrado_id', $id)->pluck('sector_id')->toArray();

        if($id != 0) $entity = PatrullajeIntegrado::find($id);

        return view('auth.patrullajeIntegrado._Maintenance', ['PatrullajeIntegrado' => $entity, 'Camionetas' => $camionetas,
        'Sectors' => $sectors, 'Trabajadors' => $trabajadors, 'TurnoOrganizacion' => $turnoOrganizacion, 'PatrullajeIntegradoSector' => $patrullajeIntegradoSector ]);
    }

    public function store(Request $request)
    {

       $status = false;

        if($request->get('id') != 0){
            $entity =  PatrullajeIntegrado::find($request->get('id'));
        }else{
            $entity = new PatrullajeIntegrado();
        }

        $entity->turno_id = $request->get('turno_id');
        $entity->user_id = $request->get('user_id');
        $entity->dia = $request->get('dia');
        $entity->hora_inicio = $request->get('hora_inicio');
        $entity->hora_final = $request->get('hora_final');
        $entity->camioneta_id = $request->get('camioneta_id');
        $entity->placa = $request->get('placa');
        $entity->trabajador_id = $request->get('trabajador_id');
        $entity->efectivo_policial = strtoupper($request->get('efectivo_policial'));
        $entity->estado_id = 1;

        if(App::$PERFIL_ADMINISTRADOR == Auth::user()->perfil_id)
            $entity->fecha_registro = date('Y-m-d', strtotime(str_replace('/', '-', $request->get('fecha_registro'))));
        else
            $entity->fecha_registro =  Carbon::now()->toDateString();



        if($entity->save()){

            $oldPatruallajeSectors = PatrullajeIntegradoSector::where('patrullajeIntegrado_id', $entity->id)->pluck('id')->toArray();

            if ($oldPatruallajeSectors != null && count($oldPatruallajeSectors) > 0){
                foreach ($oldPatruallajeSectors as $q){
                    $patrullajeSector = PatrullajeIntegradoSector::find($q);
                    $patrullajeSector->delete();
                }
            }

            foreach (explode(',', $request->get('sector_id')) as $s){
                $patrullajeSector = new PatrullajeIntegradoSector();
                $patrullajeSector->patrullajeIntegrado_id = $entity->id;
                $patrullajeSector->sector_id =  $s;
                $patrullajeSector->save();
            }

            $status = true;
        }

        return response()->json(['Success' => $status]);
    }

    public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = PatrullajeIntegrado::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
