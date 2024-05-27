<?php

namespace easyCRM\Http\Controllers\Auth;

use easyCRM\Carrera;
use easyCRM\Horario;
use Illuminate\Http\Request;
use easyCRM\Http\Controllers\Controller;

class HorarioController extends Controller
{
    public function filtroHorario($turno_id, $carrera_id, $tipo)
    {
        return response()->json(Horario::where('turno_id', $turno_id)->where('carrera_id', $carrera_id)
            ->where('tipo', $tipo)
            ->orderBy('horario', 'asc')->get());
    }

    public function filtroCarrera($modalidad_id)
    {
        return response()->json(Carrera::where('modalidad_id', $modalidad_id)->orderBy('name', 'asc')->get());
    }

}
