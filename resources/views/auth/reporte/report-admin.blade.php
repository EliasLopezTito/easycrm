@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('auth/css/report/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Reporte Administrativo
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <label for="fechaInicio">Fecha de Inicio</label>
                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="{{ $formattedFirstDayMonth }}">
            </li>
            <li class="breadcrumb-item">
                <label for="fechaInicio">Fecha de Final</label>
                <input type="date" class="form-control" id="fechaFinal" name="fechaFinal" value="{{ $formattedToday }}">
            </li>
            <li class="breadcrumb-item btn-link">
                <button type="button" class="btn btn-primary" id="btnConsult">Consultar</button>
            </li>
        </ol>
    </section>

    <section class="content">

        <div class="table-responsive">
            <table class="table table-hover" id="clienteData">
                <thead>
                    <th>Cliente</th>
                    <th>DNI</th>
                    <th>Celular</th>
                    <th>Whatsapp</th>
                    <th>Email</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Plataforma</th>
                    <th>Fuente</th>
                    <th>Provincia</th>
                    <th>Distrito</th>
                    <th>Especialidad</th>
                    <th>Modalidad</th>
                    <th>Estado</th>
                    <th>Detalle Estado</th>
                    <th>Turno</th>
                    <th>Horario</th>
                    <th>Sede</th>
                    <th>Observación</th>
                    <th>Fecha Ultimo Contacto</th>
                    <th>Año</th>
                    <th>Mes</th>
                </thead>
                <tbody>
                    @foreach ($clientData as $client)
                            <tr>
                                <td>{{ $client->Cliente }}</td>
                                <td>{{ $client->DNI }}</td>
                                <td>{{ $client->Celular }}</td>
                                <td>{{ $client->Whatsapp }}</td>
                                <td>{{ $client->Email }}</td>
                                <td>{{ $client->fecha_nacimiento }}</td>
                                <td>{{ $client->Plataforma }}</td>
                                <td>{{ $client->Fuente }}</td>
                                <td>{{ $client->Provincia }}</td>
                                <td>{{ $client->Distrito }}</td>
                                <td>{{ $client->Especialidad }}</td>
                                <td>{{ $client->Modalidad }}</td>
                                <td>{{ $client->Estado }}</td>
                                <td>{{ $client->Detalle_Estado }}</td>
                                <td>{{ $client->Turno }}</td>
                                <td>{{ $client->Horario }}</td>
                                <td>{{ $client->Sede }}</td>
                                <td>{{ $client->Observacion }}</td>
                                <td>{{ $client->Fecha_ultimo_contacto }}</td>
                                <td>{{ $client->Año }}</td>
                                <td>{{ $client->Mes }}</td>
                            </tr>
                        @endforeach
                </tbody>                
            </table>
        </div>

    </section>

</div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script>
        const routeSpanish = "{{ asset('auth/js/datatable/languaje/spanish.json') }}";
        const token = "{{ csrf_token() }}";
        const routeAjax = "{{ route('user.store-reporte-admin') }}";
    </script>
    <script src="{{ asset('auth/js/datatable/dom.js') }}"></script>
@endsection