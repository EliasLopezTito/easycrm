<?php

namespace Incidencias\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Incidencias\Exports\RoserenazgosExport;
use Incidencias\Roserenazgo;
use Maatwebsite\Excel\Facades\Excel;

class RoserenazgoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $anios = Roserenazgo::groupby('year')->pluck('year')->toArray();

        if (!in_array(Carbon::now()->year, $anios)) {
            array_push($anios, Carbon::now()->year);
        }

        $roserenazgos = Roserenazgo::with('clasificacionIncidencias')->where('year', Carbon::now()->year)->get();

        return view('auth.roserenazgo.index', ['Anios' => $anios, 'Roserenazgos' => $roserenazgos]);
    }

    public function store(Request $request)
    {
        $year = $request->get('year');
        $Roserenazgos = json_decode($request->get('Roserenazgos'));

        $OldRoserenazgos = Roserenazgo::where('year', $request->get('year'))->pluck('id')->toArray();

        $arrayRoserenazgos_ids = [];

        if($Roserenazgos != null && count($Roserenazgos) > 0){
            foreach ($Roserenazgos as $r){

                $Roserenazgo = null;

                if($r->id != null && $r->id != 0) {
                    $Roserenazgo = Roserenazgo::find($r->id);
                    array_push($arrayRoserenazgos_ids, $r->id);
                }

                if($Roserenazgo == null) {
                    $Roserenazgo = new Roserenazgo();
                }

                $Roserenazgo->year = $year;
                $Roserenazgo->clasificacionIncidencia_id = $r->clasificacionIncidencia_id;
                $Roserenazgo->modalidadIncidencia_id = $r->modalidadIncidencia_id;
                $Roserenazgo->enero = $r->enero;
                $Roserenazgo->febrero = $r->febrero;
                $Roserenazgo->marzo = $r->marzo;
                $Roserenazgo->abril = $r->abril;
                $Roserenazgo->mayo = $r->mayo;
                $Roserenazgo->junio = $r->junio;
                $Roserenazgo->julio = $r->julio;
                $Roserenazgo->agosto = $r->agosto;
                $Roserenazgo->septiembre = $r->septiembre;
                $Roserenazgo->octubre = $r->octubre;
                $Roserenazgo->noviembre = $r->noviembre;
                $Roserenazgo->diciembre = $r->diciembre;
                $Roserenazgo->save();

                if(count($arrayRoserenazgos_ids) <= 0){
                    foreach ($OldRoserenazgos as $o){
                        DB::table('roserenazgos')->where('id', $o)->delete();
                    }
                }else{
                    $result = array_diff($OldRoserenazgos, $arrayRoserenazgos_ids);
                    if(count($result) > 0){
                        foreach ($result as $w){
                            DB::table('roserenazgos')->where('id', $w)->delete();
                        }
                    }
                }
            }
        }else{
            DB::table('roserenazgos')->delete();
        }

        $status = true;

        return response()->json(['Success' => $status]);

    }

    public function filtro_fecha($anio)
    {
        $roserenazgos = Roserenazgo::with('clasificacionIncidencias')->with('modalidadIncidencias')
        ->where('year', $anio)->get();

        return response()->json([ 'Roserenazgos' => $roserenazgos]);
    }

    public function print_filter_excel($anio){
        return Excel::download(new RoserenazgosExport($anio),
            'Roserenazgos.xlsx');
    }

    public function print_filter_pdf($anio)
    {
        $roserenazgos = Roserenazgo::with('clasificacionIncidencias')->where('year', $anio)->get();

        $data = array(
            'anio' => $anio, 'roserenazgos' => $roserenazgos
        );

        $pdf = PDF::loadView('auth.exports.roserenazgo.listadoPDF', $data)->setPaper('a4', 'landscape');
        return $pdf->download('Roserenazgos.pdf');
    }

}
