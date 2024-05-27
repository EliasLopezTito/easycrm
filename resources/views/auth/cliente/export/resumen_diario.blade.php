 <table>
        <thead>
            <tr>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">ASESOR</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">TOTAL REGISTROS CAMAPAÑA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">TOTAL DE REGISTROS DÍA ACTUAL</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">TOTAL DE MATRICULAS</th>
                @foreach($Fuentes as $f)
                    <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">{{ strtoupper($f->name) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($Asesores as $a)
                <tr>
                    <td>{{ $a->name }}</td>
                    <td>{{ count($ClientesCampana->where('user_id', $a->id)) }}</td>
                    <td>{{ count($ClientesActual->where('user_id', $a->id)) }}</td>
                    <td>{{ count($ClientesCampana->where('user_id', $a->id)->where('estado_id', [\easyCRM\App::$ESTADO_CIERRE])) }}</td>
                    @foreach($Fuentes as $f)
                        <td>{{ count($ClientesCampana->where('user_id', $a->id)->where('fuente_id', $f->id)) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot></tfoot>
 </table>
