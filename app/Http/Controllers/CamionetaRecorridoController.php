<?php

namespace Incidencias\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Incidencias\App;
use Incidencias\Camioneta;
use Incidencias\CamionetaRecorrido;
use Incidencias\Recorrido;

class CamionetaRecorridoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $recorrido = Recorrido::whereNull('fecha_cerrado')->first();

        return view('auth.camionetaRecorrido.index', ['Recorrido' => $recorrido]);
    }

    public function filtro_fecha(Request $request)
    {
        if($request->get("fecha") != null){

            $recorrido = Recorrido::with('camionetasRecorrido')->with('camionetasRecorrido.camionetas')
                ->whereDate('fecha_abierto', date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha")))))
                ->first();

            return response()->json(['data' => $recorrido]);
        }

        $recorrido = Recorrido::with('camionetasRecorrido')
            ->with('camionetasRecorrido.camionetas')->whereNull('fecha_cerrado')->first();

        return response()->json(['data' => $recorrido]);
    }

    public function store(Request $request)
    {
        $status = false;

        $entity = Recorrido::find($request->get('id'));
        $camionetaRecorridos = json_decode($request->get('CamionetaRecorridos'));

        if($request->get('estado_id') == App::$ESTADO_FINALIZADO) {

            if($entity->fecha_abierto == Carbon::now()->toDateString()){
                return response()->json(['Success' => $status, 'Message' => 'La fecha de cierre no puede ser la misma que la fecha de apertura']);
            }

            $entity->fecha_cerrado = Carbon::now();
            $entity->estado_id = App::$ESTADO_FINALIZADO;

            if($entity->save()){

                $entityRecorrido = new Recorrido();
                $entityRecorrido->fecha_abierto = Carbon::now();
                $entityRecorrido->estado_id = App::$ESTADO_ACTIVO;
                $entityRecorrido->user_id = Auth::user()->id;

                if($entityRecorrido->save()){
                    foreach(Camioneta::all() as $q){
                        $camionetaRecorrido = new CamionetaRecorrido();
                        $camionetaRecorrido->recorrido_id = $entityRecorrido->id;
                        $camionetaRecorrido->camioneta_id = $q->id;
                        $camionetaRecorrido->placa = $q->placa;
                        $camionetaRecorrido->vinculado = $q->vinculado;
                        $camionetaRecorrido->recorrido = 0;
                        $camionetaRecorrido->save();
                    }
                }
            }
        }else{
            if($entity->estado_id != App::$ESTADO_FINALIZADO){
                $entity->estado_id = App::$ESTADO_GUARDADA;
            }
            $entity->save();
        }

        if($camionetaRecorridos != null){
            foreach($camionetaRecorridos as $q){
                $camioneta = Camioneta::find($q->camioneta_id);
                $camionetaRecorrido = CamionetaRecorrido::find($q->id);
                if($camioneta != null){
                    $camionetaRecorrido->placa = $camioneta->placa;
                    $camionetaRecorrido->vinculado = $camioneta->vinculado;
                }
                $camionetaRecorrido->recorrido = $q->recorrido;
                $camionetaRecorrido->save();
            }
        }

        $status = true;

        return response()->json(['Success' => $status]);

    }

    /*public function delete(Request $request)
    {
        $status = false;
        $id = $request->get('id');

        $entity = CamionetaRecorrido::find($id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }*/

}
