<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Roserenazgos</title>
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
    table{width: 100%}
    table tbody td{ border: 1px solid #000000;text-align: center; font-size: 13px;padding: 0;margin: 0 }
    table thead th{ text-align: center; font-size: 13px; background-color: #000000; color: #ffffff; padding: 0;margin: 0}
    table tfoot td{ text-align: center; font-size: 13px; background-color: #000000; color: #ffffff; padding: 0;margin: 0}
</style>

<body>

    <div class="container-fluid text-center text-uppercase ">
        <h3 class="font-weight">Reporte de Roserenazgos</h3>
        <small> Año: {{ $anio }} </small>
    </div>

    <div class="container-fluid margin-top-10">
        <table>
            <thead>
            <tr>
                <th>AÑO</th>
                <th>GENÉRICO</th>
                <th>ESPECIFICO</th>
                <th>ENE</th>
                <th>FEB</th>
                <th>MAR</th>
                <th>ABR</th>
                <th>MAY</th>
                <th>JUN</th>
                <th>JUL</th>
                <th>AGO</th>
                <th>SEP</th>
                <th>OCT</th>
                <th>NOV</th>
                <th>DIC</th>
                <th>TOTAL</th
            </tr>
            </thead>
            <tbody>
            @foreach($roserenazgos as $r)
                <tr>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->year }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->clasificacionIncidencias->name }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->modalidadIncidencias->name }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->enero }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->febrero }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->marzo }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->abril }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->mayo }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->junio }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->julio }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->agosto }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->septiembre }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->octubre }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->noviembre }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->diciembre }}</td>
                    <td style="border: 2px solid #000000;text-align: center">{{ $r->enero + $r->febrero + $r->marzo + $r->abril + $r->mayo + $r->junio + $r->julio + $r->agosto + $r->septiembre + $r->octubre
                + $r->noviembre + $r->diciembre}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">TOTALES</td>
                <td>{{ $roserenazgos->pluck('enero')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('febrero')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('marzo')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('abril')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('mayo')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('junio')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('julio')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('agosto')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('septiembre')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('octubre')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('noviembre')->sum() }}</td>
                <td>{{ $roserenazgos->pluck('diciembre')->sum() }}</td>
                <td>
                    {{ $roserenazgos->pluck('enero')->sum() + $roserenazgos->pluck('febrero')->sum() + $roserenazgos->pluck('marzo')->sum() + $roserenazgos->pluck('abril')->sum() + $roserenazgos->pluck('mayo')->sum() +
                       $roserenazgos->pluck('junio')->sum() + $roserenazgos->pluck('julio')->sum() + $roserenazgos->pluck('agosto')->sum() + $roserenazgos->pluck('septiembre')->sum() + $roserenazgos->pluck('octubre')->sum() +
                       $roserenazgos->pluck('noviembre')->sum() + $roserenazgos->pluck('diciembre')->sum()}}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>
