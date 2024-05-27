@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" href="auth/plugins/datatable/datatables.min.css">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Listado Modalidades
            <small>Mantenimiento</small>
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <button type="button" id="modalRegistrarModalidad" class="btn-primary"><i class="fa fa-plus"></i> Registrar Modalidad</button>
            </li>
        </ol>
    </section>

    <section class="content">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <table id="tableModalidad" class="table table-bordered table-striped display nowrap margin-top-10 dataTable no-footer"></table>
            </div>
        </div>
    </section>

</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="auth/plugins/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="auth/plugins/datatable/dataTables.config.min.js"></script>
    <script type="text/javascript" src="auth/js/modalidad/index.min.js"></script>
@endsection
