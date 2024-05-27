<?php


namespace easyCRM;


use Carbon\Carbon;

class App
{

    public static $ESTADO_NUEVO = 1;
    public static $ESTADO_SEGUIMIENTO = 2;
    public static $ESTADO_OPORTUNUDAD = 3;
    public static $ESTADO_CIERRE = 4;
    public static $ESTADO_NOCONTACTADO = 5;
    public static $ESTADO_PERDIDO = 6;
    public static $ESTADO_OTROS = 7;
    public static $ESTADO_REINGRESO = 12;
    public static $ESTADO_REASIGNADO = 13;
    public static $ESTADO_REMARKETING = 15;
    public static $ESTADO_REINTENTO = 16;

    public static $ESTADO_DETALLE_MATRICULADO = 8;
    public static $ESTADO_DETALLE_NUEVO = 21;
    public static $ESTADO_DETALLE_REINGRESO = 32;
    public static $ESTADO_DETALLE_TRASLADO = 35;
    public static $ESTADO_DETALLE_REINTENTO = 39;

    public static $ACTIVO = 1;
    public static $INACTIVO = 0;

    public static $PERFIL_ADMINISTRADOR = 1;
    public static $PERFIL_VENDEDOR = 2;
    public static $PERFIL_CALL = 3;
    public static $PERFIL_RESTRINGIDO = 4;
    public static $PERFIL_PERDIDOS = 5;
    public static $PERFIL_CAJERO = 6;
    public static $PERFIL_PROVINCIA = 7;
    public static $PERFIL_SUPER_ADMINISTRADOR = 11;

    public static $USUARIO_PROVINCIA = 46;

    public static $LLAMADA = 1;

    public static $TURNO_MANANA = 1;
    public static $TURNO_TARDE  = 2;
    public static $TURNO_GLOABAL = 3;
    public static $TURNO_NOCHE = 4;

    public static $TURNO_HORA_MANANA = "21:00:01";
    public static $TURNO_HORA_TARDE = "17:00:01";
    public static $TURNO_HORA_GLOBAL = "13:00:01";

    public static $MODALIDAD_CARRERA = 1;
    public static $MODALIDAD_CURSO = 2;

    public static $FUENTES_GOOGLE_ADS = 1;
    public static $FUENTES_FACEBOOK_ADS = 2;

    public static $DIVISION_SEMESTRE_INICIO = 1;
    public static $DIVISION_SEMESTRE_TERMINO = 2;
    public static $DIVISION_SEMESTRE_COMPARTIDO = 3;

    public static $SEMIPRESENCIAL_PRIMEROS_AUXILIOS = 19;
    public static $SEMI_MOVILIZACION_MASAJE = 20;
    public static $SEMI_PRESENCIAL_INYECTABLES = 24;
    public static $SEMI_PRESENCIAL_SATURA = 26;

    public static $TOKEN_API_FACEBOOK = null;

    public static $FUENTE_REINTENTO = 24;

    public static function ObtenerFechasPorRango($date_time_from, $date_time_to)
    {
        $start = Carbon::createFromFormat('Y-m-d', substr($date_time_from, 0, 10));
        $end = Carbon::createFromFormat('Y-m-d', substr($date_time_to, 0, 10));

        $dates = []; $dias = []; $i = 1;

        while ($start->lte($end)) {
            $dias[] = $i++;
            $dates[] = $start->copy()->format('Y-m-d');
            $start->addDay();
        }

        return ['dias' => $dias, 'fechas' => $dates];
    }

    public static function formatDateStringSpanish($date)
    {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($date);
        $mes = $meses[($fecha->format('n')) - 1];
        $date = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

        return $date;
    }

}
