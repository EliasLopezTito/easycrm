<?php

namespace Incidencias\Exports;

use Illuminate\Contracts\View\View;
use Incidencias\Comisaria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ComisariasExport implements FromView
{
    public function __construct($anio)
    {
        $this->anio = $anio;
    }

    public function view(): View
    {
        $comisarias = Comisaria::with('delitosPNP')->where('year', $this->anio)->get();

        return view('auth.exports.comisaria.listadoExcel', [
            'anio' => $this->anio,
            'comisarias' => $comisarias
        ]);
    }
}
