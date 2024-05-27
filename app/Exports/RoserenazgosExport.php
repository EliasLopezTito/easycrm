<?php

namespace Incidencias\Exports;

use Illuminate\Contracts\View\View;
use Incidencias\Roserenazgo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class RoserenazgosExport implements FromView
{
    public function __construct($anio)
    {
        $this->anio = $anio;
    }

    public function view(): View
    {
        $roserenazgos = Roserenazgo::with('clasificacionIncidencias')->where('year', $this->anio)->get();

        return view('auth.exports.roserenazgo.listadoExcel', [
            'anio' => $this->anio,
            'roserenazgos' => $roserenazgos
        ]);
    }
}
