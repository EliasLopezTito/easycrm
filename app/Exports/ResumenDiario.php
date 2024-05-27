<?php

namespace easyCRM\Exports;

use easyCRM\App;
use easyCRM\Cliente;
use easyCRM\Fuente;
use easyCRM\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResumenDiario implements FromView
{
    public function __construct($fechaInicio, $fechaFinal, $fechaActual)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->fechaActual = $fechaActual;
    }

    public function view(): View
    {
        $fechaInicio = $this->fechaInicio; $fechaFinal = $this->fechaFinal; $fechaActual = $this->fechaActual;

        $ClientesCampana = Cliente::whereHas('users', function ($query) { $query->whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA]); } )
            ->whereDate('ultimo_contacto', '>=', $fechaInicio)
            ->whereDate('ultimo_contacto', '<=', $fechaFinal)
            ->orderBy('ultimo_contacto', 'desc')
            ->get();

        $clientesActual = Cliente::whereHas('users', function ($query) { $query->whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA]); } )
            ->whereDate('ultimo_contacto', '=', $fechaActual)
            ->orderBy('ultimo_contacto', 'desc')
            ->get();

        $Asesores = User::whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA])->where('activo', true)->orderBy('name', 'asc')->get();

        $Fuentes = Fuente::all();

        return view('auth.cliente.export.resumen_diario', ['ClientesCampana' => $ClientesCampana, 'ClientesActual' => $clientesActual,
            'Asesores' => $Asesores, 'Fuentes' => $Fuentes]);
    }
}
