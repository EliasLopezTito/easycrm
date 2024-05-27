
    <h3> Reporte de Patrullaje Integrados : Desde {{ date('d-m-Y', strtotime($fechaInicio)) }} Hasta {{ date('d-m-Y',  strtotime($fechaFinal)) }}</h3>

    <table>
        <thead>
            <tr>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">TURNO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 10px;text-align: center">DÍA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">HORA INICIO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">HORA FINAL</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">CAMIONETA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">PLACA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">ZONAS</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">CHOFER</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">EFECTIVO POLICIAL</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">USUARIO REGISTRO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">FECHA REGISTRO</th>
            </tr>
        </thead>
        <tbody>
        @foreach($patrullajeIntegrados as $pi)
            <tr>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->turnos->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->dia }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->hora_inicio }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->hora_final }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->camionetas->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->placa }}</td>
                <td style="border: 2px solid #000000;text-align: center"> {{ $pi->zonas->pluck('sectors.name') }} </td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->trabajadors->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->efectivo_policial }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $pi->users->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ date('d-m-Y', strtotime($pi->fecha_registro)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p> Total: {{ Count($patrullajeIntegrados)}} </p>
