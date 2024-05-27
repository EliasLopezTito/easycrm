
<h3> Reporte de Roserenazgos del Año {{ $anio }}</h3>

    <table>
        <thead>
            <tr>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">AÑO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">GENÉRICO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">ESPECIFICO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">ENERO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">FEBRERO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">MARZO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">ABRIL</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">MAYO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">JUNIO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">JULIO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">AGOSTO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">SEPTIEMBRE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">OCTUBRE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">NOVIEMBRE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">DICIEMBRE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">TOTAL</th>
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
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center" colspan="3">TOTALES</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('enero')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('febrero')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('marzo')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('abril')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('mayo')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('junio')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('julio')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('agosto')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('septiembre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('octubre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('noviembre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $roserenazgos->pluck('diciembre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">
                    {{ $roserenazgos->pluck('enero')->sum() + $roserenazgos->pluck('febrero')->sum() + $roserenazgos->pluck('marzo')->sum() + $roserenazgos->pluck('abril')->sum() + $roserenazgos->pluck('mayo')->sum() +
                       $roserenazgos->pluck('junio')->sum() + $roserenazgos->pluck('julio')->sum() + $roserenazgos->pluck('agosto')->sum() + $roserenazgos->pluck('septiembre')->sum() + $roserenazgos->pluck('octubre')->sum() +
                       $roserenazgos->pluck('noviembre')->sum() + $roserenazgos->pluck('diciembre')->sum()}}
                </td>
            </tr>
        </tfoot>
    </table>
