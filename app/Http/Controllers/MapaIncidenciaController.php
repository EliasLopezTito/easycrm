<?php

namespace Incidencias\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Incidencias\IncidenciaRelevante;
use Incidencias\Roserenazgo;

class MapaIncidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $anios = DB::table('incidencia_relevantes')
            ->where('deleted_at',  null)
            ->select(DB::raw('YEAR(fecha_registro) as year'))
            ->groupby('year')
            ->pluck('year')->toArray();

        if (!in_array(Carbon::now()->year, $anios)) {
            array_push($anios, Carbon::now()->year);
        }

        $mes = [
            ['id' => 1, 'name' => 'ENERO'], ['id' => 2, 'name' => 'FEBRERO'], ['id' => 3, 'name' => 'MARZO'],
            ['id' => 4, 'name' => 'ABRIL'], ['id' => 5, 'name' => 'MAYO'], ['id' => 6, 'name' => 'JUNIO'],
            ['id' => 7, 'name' => 'JULIO'], ['id' => 8, 'name' => 'AGOSTO'], ['id' => 9, 'name' => 'SEPTIEMBRE'],
            ['id' => 10, 'name' => 'OCTUBRE'], ['id' => 11, 'name' => 'NOVIEMBRE'], ['id' => 12, 'name' => 'DICIEMBRE']
        ];

        return view('auth.mapaIncidencia.index', ['Anios' => $anios, 'Mes' => $mes]);
    }

    public function filtro_fecha_modalidad(Request $request)
    {
        $modalidadIncidencias = DB::table('incidencia_relevantes')
            ->join('modalidad_incidencias', 'modalidad_incidencias.id', '=', 'incidencia_relevantes.modalidadIncidencia_id')
            ->join('images', 'images.id', '=', 'modalidad_incidencias.image_id')
            ->where('incidencia_relevantes.deleted_at',  null)
            ->whereBetween('incidencia_relevantes.fecha_registro', [date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha_inicio")))),
                date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha_final"))))])
            ->select(['modalidad_incidencias.*', 'images.name as image'])
            ->groupby('modalidad_incidencias.id')
            ->get();

        /*if($mes != 0){
            $modalidadIncidencias = DB::table('incidencia_relevantes')
                ->join('modalidad_incidencias', 'modalidad_incidencias.id', '=', 'incidencia_relevantes.modalidadIncidencia_id')
                ->join('images', 'images.id', '=', 'modalidad_incidencias.image_id')
                ->where('incidencia_relevantes.deleted_at',  null)
                ->where(DB::raw('DATE_FORMAT(incidencia_relevantes.fecha_registro, "%Y")'),  $anio)
                ->where(DB::raw('DATE_FORMAT(incidencia_relevantes.fecha_registro, "%c")'),  $mes)
                ->select(['modalidad_incidencias.*', 'images.name as image'])
                ->groupby('modalidad_incidencias.id')
                ->get();
        }else{
            $modalidadIncidencias = DB::table('incidencia_relevantes')
                ->join('modalidad_incidencias', 'modalidad_incidencias.id', '=', 'incidencia_relevantes.modalidadIncidencia_id')
                ->join('images', 'images.id', '=', 'modalidad_incidencias.image_id')
                ->where('incidencia_relevantes.deleted_at',  null)
                ->where(DB::raw('DATE_FORMAT(incidencia_relevantes.fecha_registro, "%Y")'),  $anio)
                ->select(['modalidad_incidencias.*', 'images.name as image'])
                ->groupby('modalidad_incidencias.id')
                ->get();
        }*/

        return response()->json($modalidadIncidencias);
    }

    public function filtro_mapa(Request $request){

        $modalidadIncidenciasArreglo = json_decode($request->get('checks'));

       if($modalidadIncidenciasArreglo != null && count($modalidadIncidenciasArreglo) > 0){

           $modalidadIncidencias = DB::table('incidencia_relevantes')
               ->join('modalidad_incidencias', 'modalidad_incidencias.id', '=', 'incidencia_relevantes.modalidadIncidencia_id')
               ->join('lugar_incidencias', 'lugar_incidencias.id', '=', 'incidencia_relevantes.lugar_incidencia_id')
               ->join('images', 'images.id', '=', 'modalidad_incidencias.image_id')
               ->where('incidencia_relevantes.deleted_at', null)
               ->whereIn('incidencia_relevantes.modalidadIncidencia_id', $modalidadIncidenciasArreglo)
               ->whereBetween('incidencia_relevantes.fecha_registro', [date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha_inicio")))),
                   date('Y-m-d', strtotime(str_replace('/', '-', $request->get("fecha_final"))))])
               ->select(['incidencia_relevantes.*', 'modalidad_incidencias.name as modalidadIncidencia', 'lugar_incidencias.name as lugarIncidencia', 'images.name as image'])
               ->get();

        }else{
            $modalidadIncidencias = null;
        }

        return response()->json($modalidadIncidencias);
    }

}
