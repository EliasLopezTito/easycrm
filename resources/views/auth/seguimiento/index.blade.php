@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" href="auth/plugins/datatable/datatables.min.css">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Seguimiento Actividades
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                @csrf
                <table id="tableSeguimiento" class="table table-bordered table-striped display nowrap margin-top-10 dataTable no-footer"></table>
            </div>    
        </div>     
    </section>

</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="auth/plugins/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="auth/plugins/datatable/dataTables.config.min.js"></script>
    <script type="text/javascript" src="auth/js/seguimiento/index.js"></script>
@endsection
