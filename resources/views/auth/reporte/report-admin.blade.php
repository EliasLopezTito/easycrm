@extends('auth.layout.app')

@section('styles')

@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Reporte Administrativo
        </h1>

        <ol class="breadcrumb top-5">
            <li class="breadcrumb-item">
                <label for="">Fecha</label>
                <div id="reportrange" class="text-capitalize" style="">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-angle-down"></i>
                </div>
            </li>
        </ol>
    </section>

    <section class="content">

        <div class="table-responsive">
            <table class="table table-hover">
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
                    
                </tbody>
            </table>
        </div>

    </section>

</div>
@endsection

@section('scripts')
    <script></script>    
@endsection