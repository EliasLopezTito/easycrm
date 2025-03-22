<?php

namespace easyCRM\Traits;

use Carbon\Carbon;

trait Procesos
{
    public function obtenerDatosPorFiltro($clientes, $filtro, $tipo = 'filtro')
    {
        $clientes_filtro = $clientes->filter(function ($value, $key) use ($filtro) {
            if (empty($filtro)) {
                return true;
            }

            $validacion_filtro = true;

            foreach ($filtro as $filtroItem) {
                if ($value->{$filtroItem['columna']} != $filtroItem['valor']) {
                    $validacion_filtro = false;
                    break;
                }
            }

            return $validacion_filtro;
        });

        if ($tipo == 'cantidad') {
            return COUNT($clientes_filtro);
        }

        return $clientes_filtro;
    }

    public function obtenerFiltroLeadPorCreatedAtAndLastContact($query, $fecha_inicio, $fecha_final, $filter_lead_report)
    {
        if ($filter_lead_report == 'created_at_last_contact') {
            $query->where(function ($query) use ($fecha_inicio, $fecha_final) {
                $query->whereBetween('clientes.created_at', [
                    Carbon::parse($fecha_inicio)->startOfDay(),
                    Carbon::parse($fecha_final)->endOfDay()
                ]);
                $query->orWhereBetween('clientes.ultimo_contacto', [
                    Carbon::parse($fecha_inicio)->startOfDay(),
                    Carbon::parse($fecha_final)->endOfDay()
                ]);
            });
        } else if ($filter_lead_report == 'created_at') {
            $query->whereBetween('clientes.created_at', [
                Carbon::parse($fecha_inicio)->startOfDay(),
                Carbon::parse($fecha_final)->endOfDay()
            ]);
        } else if ($filter_lead_report == 'last_contact') {
            $query->whereBetween('clientes.ultimo_contacto', [
                Carbon::parse($fecha_inicio)->startOfDay(),
                Carbon::parse($fecha_final)->endOfDay()
            ]);
        }

        return $query;
    }
    public function obtenerFiltroLeadPorCreatedAtAndLastContactPrueba($query, $fecha_inicio, $fecha_final, $filter_lead_report)
    {
        $query->whereBetween('cliente_matriculas.created_at', [
            Carbon::parse($fecha_inicio)->startOfDay(),
            Carbon::parse($fecha_final)->endOfDay()
        ]);

        return $query;
    }
}
