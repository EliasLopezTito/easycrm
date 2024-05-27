<?php

namespace Incidencias\Exports;

use Illuminate\Contracts\View\View;
use Incidencias\PatrullajeIntegrado;
use Maatwebsite\Excel\Concerns\FromView;

class PatrullajeIntegradosExport implements FromView
{
    public function __construct($fechaInicio, $fechaFinal)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
    }

    public function view(): View
    {
        $patrullajeIntegrados = PatrullajeIntegrado::with('turnos')->with('users')->with('camionetas')
            ->with('trabajadors')->with('estados')->with('zonas')->with('zonas.sectors')
            ->whereDate('fecha_registro','>=', $this->fechaInicio)
            ->whereDate('fecha_registro','<=', $this->fechaFinal)
            ->get();

        return view('auth.exports.patrullajeIntegrados.listadoExcel', [
            'fechaInicio' => $this->fechaInicio, 'fechaFinal' => $this->fechaFinal,
            'patrullajeIntegrados' => $patrullajeIntegrados
        ]);
    }
}
