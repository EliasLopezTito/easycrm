<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patrullaje Integrados</title>
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
    .text-uppercase{ text-transform: uppercase;} .margin-top-10{ margin-top: 10px;} .margin-top-30{ margin-top: 30px;} .text-right{ text-align: right}
    table tbody td{ border: 1px solid #000000;text-align: center; font-size: 12px;padding: 0;margin: 0 }
    table thead th{ text-align: center; font-size: 12px; background-color: #000000; color: #ffffff; padding: 0;margin: 0}
</style>

<body>

    <div class="container-fluid text-center text-uppercase ">
        <h3 class="font-weight">Reporte de Patrullaje Integrados</h3>
        <small> Desde: {{ date('d-m-Y', strtotime($fechaInicio)) }} Hasta </small>{{ date('d-m-Y', strtotime($fechaFinal)) }}
    </div>

    <div class="container-fluid margin-top-10">
        <table>
            <thead>
            <tr>
                <th>TURNO</th>
                <th>DÍA</th>
                <th>HORA INICIO</th>
                <th>HORA FINAL</th>
                <th>CAMIONETA</th>
                <th>PLACA</th>
                <th>ZONAS</th>
                <th>CHOFER</th>
                <th>EFECTIVO POLICIAL</th>
                <th>USUARIO REGISTRO</th>
                <th>FECHA REGISTRO</th>
            </tr>
            </thead>
            <tbody>
            @foreach($patrullajeIntegrados as $pi)
                <tr>
                    <td>{{ $pi->turnos->name }}</td>
                    <td>{{ $pi->dia }}</td>
                    <td>{{ $pi->hora_inicio }}</td>
                    <td>{{ $pi->hora_final }}</td>
                    <td>{{ $pi->camionetas->name }}</td>
                    <td>{{ $pi->placa }}</td>
                    <td> {{ $pi->zonas->pluck('sectors.name') }} </td>
                    <td>{{ $pi->trabajadors->name }}</td>
                    <td>{{ $pi->efectivo_policial }}</td>
                    <td>{{ $pi->users->name }}</td>
                    <td>{{ date('d-m-Y', strtotime($pi->fecha_registro)) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container-fluid text-right text-uppercase margin-top-30">
        <small> Total: {{ Count($patrullajeIntegrados)}} </small>
    </div>

</body>
</html>
