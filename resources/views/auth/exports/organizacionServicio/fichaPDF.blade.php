<?php  setlocale(LC_TIME,"es_ES"); ?>
<!doctype html>
<html lang="eS">
<head>
    <meta charset="UTF-8">
    <title>Organización del Servicio</title>
</head>
<style type="text/css">
    body{
        background-image: url("/auth/image/logo-mdjm.jpg");
        background-repeat: no-repeat;
        background-position: top right;
        background-size: 150px 80px;
        padding-top: 45px;
    }
    .container-fluid{ width: 100% }  .text-center{ text-align: center} .font-weight{ font-weight: bold }
    .text-uppercase{ text-transform: uppercase;} .margin-top-10{ margin-top: 10px;} .margin-top-5{ margin-top: 5px;}
    .margin-top-50{ margin-top: 50px;}
    .boder-solid{border: 1px solid #000000;}
    table{ width: 100%;}
    table tbody td{ text-align: center; font-size: 13px;padding: 0;margin: 0; }
    table thead th{ text-align: center; font-size: 10px; background-color: #000000; color: #ffffff; padding: 5px 0px;margin: 0}
    table#DistribucionPersonal thead th, table#DistribucionPersonal tbody td{border: 1px solid #000000;}
    table#PersonalSector thead th, table#PersonalSector  tbody td{border: 1px solid #000000;}
    table#PersonalServicio thead th, table#PersonalServicio  tbody td{border: 1px solid #000000;}
    table#PersonalAusente thead th, table#PersonalAusente  tbody td{border: 1px solid #000000;}
    table#ResumenGeneral thead th, table#ResumenGeneral  tbody td{border: 1px solid #000000;}
    table#Cabecera  td{ text-align: left;padding: 2px 0; text-transform: uppercase }
    h5{margin: 5px 0;padding: 8px 0}
</style>

<body>

    <div class="container-fluid text-center text-uppercase ">
        <h3 class="font-weight">Ficha de Organización</h3>
    </div>

    <div class="container-fluid margin-top-10">
        <table id="Cabecera" class="container-fluid">
            <tr>
                <td>FECHA</td>
                <td>{{ date('d-m-Y', strtotime($organizacion->created_at)) }} </td>
            </tr>
            <tr>
                <td>HORA</td>
                <td>{{ date('H:i:s', strtotime($organizacion->created_at)) }} </td>
            </tr>
            <tr>
                <td>MES</td>
                <td>{{ strftime('%B', strtotime($organizacion->created_at)) }} </td>
            </tr>
            <tr>
                <td>TURNO</td>
                <td>{{ $organizacion->turnos->name }} </td>
            </tr>
            <tr>
                <td>SUPERVISOR</td>
                <td>{{ $organizacion->user_name }} </td>
            </tr>
        </table>
    </div>

    <div class="container-fluid margin-top-5">
        <table id="DistribucionPersonal">
            <thead>
                <tr>
                    <th>TURNO</th>
                    <th>MÓDULOS</th>
                    <th>WHATSAPP</th>
                    <th>TELEFONISTA</th>
                    <th>OPERADOR RADIO</th>
                    <th>C-1</th>
                    <th>C-2</th>
                    <th>C-3</th>
                    <th>BASE 2</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $organizacion->turnos->name }} </td>
                    <td>{{ $organizacion->servicios[0]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[1]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[2]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[3]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[4]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[5]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[6]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[7]->cantidad }}</td>
                    <td>{{ $organizacion->servicios[0]->cantidad + $organizacion->servicios[1]->cantidad + $organizacion->servicios[2]->cantidad +
                    $organizacion->servicios[3]->cantidad + $organizacion->servicios[4]->cantidad + $organizacion->servicios[5]->cantidad +
                    $organizacion->servicios[6]->cantidad + $organizacion->servicios[7]->cantidad}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container-fluid margin-top-5">
        <h5 style="font-weight: bold;text-transform: uppercase">Personal Por Sector</h5>
        <table id="PersonalSector">
            <thead>
                <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>RSF</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $organizacion->sectores[0]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[1]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[2]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[3]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[4]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[5]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[6]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[7]->cantidad }}</td>
                    <td>{{ $organizacion->sectores[8]->cantidad }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container-fluid margin-top-10">
        <h5 style="font-weight: bold;text-transform: uppercase">Personal Por Servicio</h5>
        <table id="PersonalServicio">
            <thead>
            <tr>
                <th>NOMBRES</th>
                <th>SERVICIO</th>
                <th>RÉGIMEN LABORAL</th>
            </tr>
            </thead>
            <tbody>
            @foreach($organizacion->personalesServicio as $ps)
                <tr>
                    <td>{{ $ps->trabajadores->name }}</td>
                    <td>{{ $ps->servicios->name }}</td>
                    <td>{{ $ps->regimen}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container-fluid margin-top-10">
        <h5 style="font-weight: bold;text-transform: uppercase">Personal Ausente</h5>
        <table id="PersonalAusente">
            <thead>
            <tr>
                <th>NOMBRES</th>
                <th>MOTIVO</th>
                <th>SERVICIO</th>
                <th>RÉGIMEN LABORAL</th>
            </tr>
            </thead>
            <tbody>
            @foreach($organizacion->personalesMotivo as $pm)
                <tr>
                    <td>{{ $pm->trabajadores->name }}</td>
                    <td>{{ $pm->motivosPersonal->name }}</td>
                    <td>{{ $pm->servicios->name }}</td>
                    <td>{{ $pm->regimen}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container-fluid margin-top-10">
        <h5 style="font-weight: bold;text-transform: uppercase">Resumen General</h5>
        <table id="ResumenGeneral">
            <thead>
            <tr>
                <th>EFECTIVOS</th>
                <th>DESCUENTOS</th>
                <th>TOTAL</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $organizacion->servicios[0]->cantidad + $organizacion->servicios[1]->cantidad + $organizacion->servicios[2]->cantidad +
                    $organizacion->servicios[3]->cantidad + $organizacion->servicios[4]->cantidad + $organizacion->servicios[5]->cantidad +
                    $organizacion->servicios[6]->cantidad + $organizacion->servicios[7]->cantidad}}</td>
                    <td>{{ count($organizacion->personalesMotivo)}}</td>
                    <td>{{ ($organizacion->servicios[0]->cantidad + $organizacion->servicios[1]->cantidad + $organizacion->servicios[2]->cantidad +
                    $organizacion->servicios[3]->cantidad + $organizacion->servicios[4]->cantidad + $organizacion->servicios[5]->cantidad +
                    $organizacion->servicios[6]->cantidad + $organizacion->servicios[7]->cantidad) + count($organizacion->personalesMotivo)}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container-fluid margin-top-50" style="text-align: right">
        <img src="http://incidenciasmjm.artechperu.pro/auth/image/firma.jpg"  width="180" alt="Firma Supervisor">
    </div>

</body>
</html>
