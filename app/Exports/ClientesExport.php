<?php

namespace easyCRM\Exports;

use Carbon\Carbon;
use easyCRM\App;
use easyCRM\Cliente;
use easyCRM\ClienteMatricula;
use easyCRM\ClienteSeguimiento;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;

class ClientesExport implements FromView, Responsable
{
    use Exportable;
    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = 'leads.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];


    public function __construct($fechaInicio, $fechaFinal, $estado, $vendedor, $modalidad, $carrera, $turno)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->estado = $estado;
        $this->vendedor = $vendedor;
        $this->modalidad = $modalidad;
        $this->carrera = $carrera;
        $this->turno = $turno;
    }

    public function view(): View
    {
        $fechaInicio = $this->fechaInicio;
        $fechaFinal = $this->fechaFinal;
        $estado = $this->estado;
        $vendedor = $this->vendedor;
        $modalidad = $this->modalidad;
        $carrera = $this->carrera;
        $turno = $this->turno;

        $clientes = Cliente::whereHas('users', function ($query) {
            $query->whereIn('profile_id', [App::$PERFIL_VENDEDOR, App::$PERFIL_PROVINCIA]);
        })
            ->where(function ($q) use ($estado) {
                if ($estado) {
                    $q->where('estado_id', $estado);
                }
            })
            ->where(function ($q) use ($fechaInicio) {
                if ($fechaInicio) {
                    $q->whereDate('ultimo_contacto', '>=', $fechaInicio);
                }
            })
            ->where(function ($q) use ($fechaFinal) {
                if ($fechaFinal) {
                    $q->whereDate('ultimo_contacto', '<=', $fechaFinal);
                }
            })
            ->where(function ($q) use ($vendedor) {
                if ($vendedor && $vendedor != 0) {
                    $q->where('user_id', $vendedor);
                }
            })
            ->where(function ($q) use ($modalidad) {
                if ($modalidad) {
                    $q->where('modalidad_id', $modalidad);
                }
            })
            ->where(function ($q) use ($carrera) {
                if ($carrera) {
                    $q->where('carrera_id', $carrera);
                }
            })
            ->where(function ($q) use ($turno) {
                if ($turno) {
                    $q->where('turno_id', $turno);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $ClienteMatriculas = ClienteMatricula::with('clientes')->with('clientes.users')
            ->with('clientes.fuentes')->with('clientes.enterados')->with('clientes.estados')
            ->whereHas('clientes', function ($q) use ($vendedor) {
                if ($vendedor && $vendedor != 0) {
                    $q->where('user_id', $vendedor);
                }
            })
            ->whereHas('clientes', function ($q) use ($estado) {
                if ($estado) {
                    $q->where('estado_id', $estado);
                }
            })
            ->where(function ($q) use ($fechaInicio) {
                if ($fechaInicio) {
                    $q->whereDate('created_at', '>=', $fechaInicio);
                }
            })
            ->where(function ($q) use ($fechaFinal) {
                if ($fechaFinal) {
                    $q->whereDate('created_at', '<=', $fechaFinal);
                }
            })
            ->where(function ($q) use ($modalidad) {
                if ($modalidad) {
                    $q->where('modalidad_adicional_id', $modalidad);
                }
            })
            ->where(function ($q) use ($carrera) {
                if ($carrera) {
                    $q->where('carrera_adicional_id', $carrera);
                }
            })
            ->where(function ($q) use ($turno) {
                if ($turno) {
                    $q->where('turno_adicional_id', $turno);
                }
            })
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('auth.cliente.export.excel', ['Clientes' => $clientes, 'ClientesMatriculas' => $ClienteMatriculas]);
    }
}
