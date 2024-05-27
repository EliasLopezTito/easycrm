<?php

namespace Incidencias\Exports;

use Illuminate\Contracts\View\View;
use Incidencias\IncidenciaRelevante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class IncidenciaRelevantesExport implements FromView
{

    public function __construct($fechaInicio, $fechaFinal)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
    }

    public function view(): View
    {
        $incidenciaRelevantes = IncidenciaRelevante::with('turnos')->with('users')->with('categories')->with('trabajadors')
            ->with('clasificacionIncidencias')->with('modalidadIncidencias')->with('vehiculos')->with('armas')->with('macros')->with('sectors')->with('subsectors')
            ->with('lugares')
            ->whereDate('fecha_registro','>=', $this->fechaInicio)
            ->whereDate('fecha_registro','<=', $this->fechaFinal)
            ->get();

        return view('auth.exports.incidenciaRelevante.listadoExcel', [
            'fechaInicio' => $this->fechaInicio, 'fechaFinal' => $this->fechaFinal,
            'incidenciaRelevante' => $incidenciaRelevantes
        ]);
    }

}
