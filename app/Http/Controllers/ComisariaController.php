<?php

namespace Incidencias\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Incidencias\Comisaria;
use Incidencias\Exports\ComisariasExport;
use Maatwebsite\Excel\Facades\Excel;

class ComisariaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $anios = Comisaria::groupby('year')->pluck('year')->toArray();

        if (!in_array(Carbon::now()->year, $anios)) {
            array_push($anios, Carbon::now()->year);
        }

        $Comisarias = Comisaria::with('delitosPNP')->where('year', Carbon::now()->year)->get();

        return view('auth.comisaria.index', ['Anios' => $anios, 'Comisarias' => $Comisarias]);
    }

    public function store(Request $request)
    {
        $year = $request->get('year');
        $Comisarias = json_decode($request->get('Comisarias'));

        $OldComisarias = Comisaria::where('year', $request->get('year'))->pluck('id')->toArray();

        $arrayComisarias_ids = [];

        if($Comisarias != null && count($Comisarias) > 0){
            foreach ($Comisarias as $r){

                $Comisaria = null;

                if($r->id != null && $r->id != 0) {
                    $Comisaria = Comisaria::find($r->id);
                    array_push($arrayComisarias_ids, $r->id);
                }

                if($Comisaria == null) {
                    $Comisaria = new Comisaria();
                }

                $Comisaria->year = $year;
                $Comisaria->delitoPNP_id = $r->delitoPNP_id;
                $Comisaria->enero = $r->enero;
                $Comisaria->febrero = $r->febrero;
                $Comisaria->marzo = $r->marzo;
                $Comisaria->abril = $r->abril;
                $Comisaria->mayo = $r->mayo;
                $Comisaria->junio = $r->junio;
                $Comisaria->julio = $r->julio;
                $Comisaria->agosto = $r->agosto;
                $Comisaria->septiembre = $r->septiembre;
                $Comisaria->octubre = $r->octubre;
                $Comisaria->noviembre = $r->noviembre;
                $Comisaria->diciembre = $r->diciembre;
                $Comisaria->save();

                if(count($arrayComisarias_ids) <= 0){
                    foreach ($OldComisarias as $o){
                        DB::table('comisarias')->where('id', $o)->delete();
                    }
                }else{
                    $result = array_diff($OldComisarias, $arrayComisarias_ids);
                    if(count($result) > 0){
                        foreach ($result as $w){
                            DB::table('comisarias')->where('id', $w)->delete();
                        }
                    }
                }
            }
        }else{
            DB::table('Comisarias')->delete();
        }

        $status = true;

        return response()->json(['Success' => $status]);

    }

    public function filtro_fecha($anio)
    {
        $Comisarias = Comisaria::with('delitosPNP')->where('year', $anio)->get();
        return response()->json([ 'Comisarias' => $Comisarias]);
    }

    public function print_filter_excel($anio){
        return Excel::download(new ComisariasExport($anio),
            'Comisarias.xlsx');
    }

    public function print_filter_pdf($anio)
    {
        $Comisarias = Comisaria::with('delitosPNP')->where('year', $anio)->get();

        $data = array(
            'anio' => $anio, 'comisarias' => $Comisarias
        );

        $pdf = PDF::loadView('auth.exports.comisaria.listadoPDF', $data)->setPaper('a4', 'landscape');
        return $pdf->download('Comisarias.pdf');
    }
}
