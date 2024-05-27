<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incidencia Relevantes</title>
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
    .text-uppercase{ text-transform: uppercase;} .margin-top-10{ margin-top: 10px;}
    table tbody td{ border: 1px solid #000000;text-align: center; font-size: 10px;padding: 0;margin: 0 }
    table thead th{ text-align: center; font-size: 10px; background-color: #000000; color: #ffffff; padding: 0;margin: 0}
</style>

<body>

<div class="container-fluid text-center text-uppercase ">
    <h3 class="font-weight">Reporte de Incidencia Relevantes</h3>
    <small> Desde: {{ date('d-m-Y', strtotime($fechaInicio)) }} Hasta </small>{{ date('d-m-Y', strtotime($fechaFinal)) }}
</div>

<div class="container-fluid margin-top-10">
    <table>
        <thead>
        <tr>
            <th>N°</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>LUGAR</th>
            <th>N° Calle</th>
            <th>CLASIFI.</th>
            <th>MODALI.</th>
            <th>VEHÍCU.</th>
            <th>ARMA</th>
            <th>OBJETO ROB.</th>
            <th>SECTOR</th>
            <th>SUBSECTOR</th>
            <th>DESCR.</th>
            <th>FECHA REG.</th>
        </tr>
        </thead>
        <tbody>
        @foreach($incidenciaRelevantes as $ir)
            <tr>
                <td>{{ $ir->nro_incidencia }}</td>
                <td>{{ date('d-m-Y', strtotime($ir->fecha)) }}</td>
                <td>{{ $ir->hora }}</td>
                <td>{{ $ir->lugares->name }}</td>
                <td>{{ $ir->nro_calle }}</td>
                <td>{{ $ir->clasificacionincidencias->name }}</td>
                <td>{{ $ir->modalidadincidencias->name }}</td>
                <td>{{ $ir->vehiculos->name }}</td>
                <td>{{ $ir->armas->name }}</td>
                <td>{{ $ir->objeto }}</td>
                <td>{{ $ir->sectors->name }}</td>
                <td>{{ $ir->subsectors->name }}</td>
                <td>{{ $ir->descripcion_incidencia }}</td>
                <td>{{ date('d-m-Y', strtotime($ir->fecha_registro)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
