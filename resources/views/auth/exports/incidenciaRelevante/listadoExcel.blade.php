
    <h3> Reporte de Incidencia Relevantes : Desde {{ date('d-m-Y', strtotime($fechaInicio)) }} Hasta {{ date('d-m-Y',  strtotime($fechaFinal)) }}</h3>

    <table>
        <thead>
            <tr>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">N° INCIDENCIA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">TURNO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">OPERADOR DE CAMARA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">FECHA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">HORA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">LUGAR INCIDENCIA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center">N° CALLE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">CATEGORÍA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">CLASIFICACIÓN INCIDENCIA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 50px;text-align: center">MODALIDAD INCIDENCIA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">VEHÍCULO UTILIZADO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">ARMA UTILIZADA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">OBJETO ROBADO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 30px;text-align: center">MACRO SECTOR</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">SECTOR</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">SUBSECTOR</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 80px;text-align: center">DESCRIPCIÓN</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 20px;text-align: center">FECHA DE REGISTRO</th>
            </tr>
        </thead>
        <tbody>
        @foreach($incidenciaRelevante as $ir)
            <tr>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->nro_incidencia }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->turnos->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->trabajadors->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ date('d-m-Y', strtotime($ir->fecha)) }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->hora }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->lugares->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->nro_calle }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->categories->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->clasificacionincidencias->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->modalidadincidencias->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->vehiculos->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->armas->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->objeto }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->macros->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->sectors->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->subsectors->name }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ $ir->descripcion_incidencia }}</td>
                <td style="border: 2px solid #000000;text-align: center">{{ date('d-m-Y', strtotime($ir->fecha_registro)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
