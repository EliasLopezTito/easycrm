
<h3> Reporte de Comisarias del Año {{ $anio }}</h3>

    <table>
        <thead>
            <tr>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">AÑO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">DELITO</th>
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
        @foreach($comisarias as $r)
            <tr>
                <td style="border: 2px solid #000000;text-align: center">{{ $r->year }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $r->delitosPNP->name }}</td>
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
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center" colspan="2">TOTALES</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('enero')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('febrero')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('marzo')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('abril')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('mayo')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('junio')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('julio')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('agosto')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('septiembre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('octubre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('noviembre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">{{ $comisarias->pluck('diciembre')->sum() }}</td>
                <td style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">
                    {{ $comisarias->pluck('enero')->sum() + $comisarias->pluck('febrero')->sum() + $comisarias->pluck('marzo')->sum() + $comisarias->pluck('abril')->sum() + $comisarias->pluck('mayo')->sum() +
                       $comisarias->pluck('junio')->sum() + $comisarias->pluck('julio')->sum() + $comisarias->pluck('agosto')->sum() + $comisarias->pluck('septiembre')->sum() + $comisarias->pluck('octubre')->sum() +
                       $comisarias->pluck('noviembre')->sum() + $comisarias->pluck('diciembre')->sum()}}
                </td>
            </tr>
        </tfoot>
    </table>
