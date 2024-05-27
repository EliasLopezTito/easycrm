 <table>
        <thead>
            <tr>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">APELLIDOS</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">NOMBRES</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">DNI</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">CELULAR</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">WHATSAPP</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">E-MAIL</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">FECHA NACIMIENTO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">ASESORA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">MODALIDAD</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">CARRERA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">ESTADO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">ESTADO DETALLE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">TURNO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">HORARIO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">SEDE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">LOCAL</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">TURNO SEMI-PRESENCIAL</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">HORARIO SEMI-PRESENCIAL</th>

                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">TIPO OPERACIÓN</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">N° OPERACIÓN</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">MONTO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">NOMBRE TITULAR</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">CÓDIGO ALUMNO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">PROMOCIÓN</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">OBSERVACIÓN</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #95d73e;color: #FFFFFF;width: 15px;text-align: center;">FECHA DE REGISTRO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">FUENTE</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">¿CÓMO TE ENTERASTE?</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">PROVINCIA</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">DISTRITO</th>
                <th style="border: 2px solid #000000;font-weight: bold;background-color: #000000;color: #FFFFFF;width: 15px;text-align: center;">DIRECCIÓN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Clientes as $q)
                <tr>
                    <td>{{ strtoupper($q->apellidos) }}</td>
                    <td>{{ strtoupper($q->nombres) }}</td>
                    <td>{{ $q->dni }}</td>
                    <td>{{ $q->celular }}</td>
                    <td>{{ $q->whatsapp }}</td>
                    <td>{{ strtoupper($q->email) }}</td>
                    <td>{{ $q->fecha_nacimiento != null ? \Carbon\Carbon::parse($q->fecha_nacimiento)->format('d-m-Y') : "" }}</td>
                    @if($q->users != null)
                        <td>{{ strtoupper($q->users->name. " ". $q->users->last_name) }}</td>
                    @else
                        <td style="background-color: red;color:red">{{ strtoupper(\Illuminate\Support\Facades\DB::table('users')->where('id', $q->user_id)->first()->name). " (ASESOR ELIMINADO)" }}</td>
                    @endif
                    <td>{{ $q->modalidades != null ? strtoupper($q->modalidades['name']) : "" }}</td>
                    {{-- <td>{{ strtoupper($q->carreras->name) }}</td>
                    <td>{{ strtoupper($q->estados->name) }}</td>
                    <td>{{ strtoupper($q->estadoDetalle->name) }}</td> --}}
                    <td>{{ $q->carreras['name'] !=null ? $q->carreras['name'] : "" }}</td>
                    <td>{{ $q->estados['name'] != null ? $q->estados['name'] : "" }}</td>
                    <td>{{ $q->estadoDetalle['name'] != null ? $q->estadoDetalle['name'] : "" }}</td>


                    <td>{{ $q->turnos != null ? strtoupper($q->turnos['name']) : "" }}</td>
                    <td>{{ $q->horarios != null ? strtoupper($q->horarios->horario) : ""}}</td>
                    <td>{{ $q->sedes != null ? strtoupper($q->sedes['name']) : ""}}</td>
                    <td>{{ $q->locales != null ? strtoupper($q->locales['name']) : ""}}</td>

                    <td>{{ $q->turnosSemiPresencial != null ? strtoupper($q->turnosSemiPresencial['name']) : "" }}</td>
                    <td>{{ $q->horariosSemiPresencial != null ? strtoupper($q->horariosSemiPresencial->horario) : ""}}</td>

                    <td>{{ $q->tipoOperaciones != null ? strtoupper($q->tipoOperaciones['name']) : ""}}</td>
                    <td>{{ $q->nro_operacion }}</td>
                    <td>{{ $q->monto }}</td>
                    <td>{{ $q->nombre_titular }}</td>
                    <td>{{ $q->codigo_alumno }}</td>
                    <td>{{ $q->promocion }}</td>
                    <td>{{ $q->observacion }}</td>
                    {{-- <td>{{ Carbon\Carbon::parse($q->created_at)->format('d-m-Y H:m:s')}}</td> --}}
                    <td>{{ $q->created_at}}</td>
                    <td>{{ $q->fuentes != null ? strtoupper($q->fuentes['name']) : "" }}</td>
                    <td>{{ $q->enterados != null ? strtoupper($q->enterados['name']) : "" }}</td>
                    <td>{{ $q->provincias != null ? strtoupper($q->provincias['name']) : "" }}</td>
                    <td>{{ $q->distritos != null ? strtoupper($q->distritos['name']) : "" }}</td>
                    <td>{{ $q->direccion == null ? 'NO HAY DIRECCION' : preg_replace('/[^a-zA-Z0-9\s]/', '', $q->direccion) }}</td>
                </tr>
            @endforeach

            @foreach($ClientesMatriculas as $c)
                <tr>
                    <td>{{ strtoupper($c->clientes->apellidos) }}</td>
                    <td>{{ strtoupper($c->clientes->nombres) }}</td>
                    <td>{{ $c->clientes->dni }}</td>
                    <td>{{ $c->clientes->celular }}</td>
                    <td>{{ $c->clientes->whatsapp }}</td>
                    <td>{{ strtoupper($c->clientes->email) }}</td>
                    <td>{{ $c->clientes->fecha_nacimiento != null ? \Carbon\Carbon::parse($c->clientes->fecha_nacimiento)->format('d-m-Y') : "" }}</td>
                    @if($c->clientes->users != null)
                        <td>{{ strtoupper($c->clientes->users->name. " ". $c->clientes->users->last_name) }}</td>
                    @else
                        <td style="background-color: red;color:red">{{ strtoupper(\Illuminate\Support\Facades\DB::table('users')->where('id', $c->clientes->user_id)->first()['name']). " (ASESOR ELIMINADO)" }}</td>
                    @endif
                    <td>{{ $c->modalidades != null ? strtoupper($c->modalidades['name']) : "" }}</td>
                    <td>{{ $c->carreras['name'] != null ? strtoupper($c->carreras['name']) : '' }}</td>
                    <td>{{ $c->clientes->estados['name'] != null ? strtoupper($c->clientes->estados['name']) : '' }}</td>
                    <td>{{ $c->clientes->estadoDetalle['name'] != null ? strtoupper($c->clientes->estadoDetalle['name']) : '' }}</td>

                    <td>{{ $c->turnos != null ? strtoupper($c->turnos['name']) : "" }}</td>
                    <td>{{ $c->horarios != null ? strtoupper($c->horarios->horario) : ""}}</td>
                    <td>{{ $c->sedes != null ? strtoupper($c->sedes['name']) : "" }}</td>
                    <td>{{ $c->locales != null ? strtoupper($c->locales['name']) : "" }}</td>

                    <td>{{ $c->turnosSemi != null ? strtoupper($c->turnosSemi['name']) : "" }}</td>
                    <td>{{ $c->horariosSemi != null ? strtoupper($c->horariosSemi->horario) : ""}}</td>

                    <td>{{ $c->tipoOperaciones != null ? strtoupper($c->tipoOperaciones['name']) : ""}}</td>
                    <td>{{ $c->nro_operacion_adicional }}</td>
                    <td>{{ $c->monto_adicional }}</td>
                    <td>{{ $c->nombre_titular_adicional }}</td>
                    <td>{{ $c->codigo_alumno_adicional  }}</td>
                    <td>{{ $c->promocion_adicional  }}</td>
                    <td>{{ $c->observacion_adicional  }}</td>
                    {{-- <td>{{ Carbon\Carbon::parse($c->created_at)->format('d-m-Y H:m:s')}}</td> --}}
                    <td>{{ $c->created_at}}</td>
                    <td>{{ $c->clientes->fuentes != null ? strtoupper($c->clientes->fuentes['name']) : "" }}</td>
                    <td>{{ $c->clientes->enterados != null ? strtoupper($c->clientes->enterados['name']) : "" }}</td>
                    <td>{{ $c->clientes->provincias != null ? strtoupper($c->clientes->provincias['name']) : "" }}</td>
                    <td>{{ $c->clientes->distritos != null ? strtoupper($c->clientes->distritos['name']) : "" }}</td>
                    <td>{{ ($c->clientes->direccion == null) ? 'NO HAY DIRECCION' : preg_replace('/[^a-zA-Z0-9\s]/', '', $c->clientes->direccion) }}</td>
                </tr>
            @endforeach

        </tbody>
        <tfoot></tfoot>
 </table>
