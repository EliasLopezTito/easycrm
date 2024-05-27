@extends('layouts.auth.app')

@section('styles')
    <link rel="stylesheet" href="/auth/plugins/DataTables/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <section class="content-header">
        <h1> Trabajador <small> Mantenimiento </small></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h2 class="box-title">Listado</h2>
                        <button type="button" class="btn btn-primary pull-right" id="btnNewTrabajador">
                            <span class="fa fa-plus"></span>  Nuevo Trabajador
                        </button>
                    </div>
                    <div class="box-body">
                        <table id="tableTrabajador" class="table table-striped table-bordered table-hover"></table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')

    <script type="text/javascript" src="/auth/plugins/DataTables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/auth/plugins/DataTables/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="/auth/plugins/DataTables/plugins/buttons/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="/auth/plugins/DataTables/plugins/buttons/buttons.flash.min.js"></script>
    <script type="text/javascript" src="/auth/plugins/DataTables/plugins/buttons/jszip.min.js"></script>
    <script type="text/javascript" src="/auth/plugins/DataTables/plugins/buttons/buttons.html5.min.js"></script>
    <script type="text/javascript" src="/auth/plugins/DataTables/js/dataTables.config.min.js"></script>

    <script type="text/javascript" src="/auth/views/trabajador/index.js"></script>
@endsection
