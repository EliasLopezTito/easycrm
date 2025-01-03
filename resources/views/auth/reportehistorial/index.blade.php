@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" href="auth/plugins/datatable/datatables.min.css">
@endsection

@section('contenido')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                Reporte Historial
                <small>Mantenimiento</small>
            </h1>

        </section>
        <br>
        <div class="content-header">
            <div class="form-row">
                <div class="form-group col-lg-5 col-md-6">
                    <label for="desde" class="m-0 label-primary">Desde</label>
                    <input type="date" class="form-control" id="desde" value="{{ Date('Y-m-d') }}">
                </div>
                <div class="form-group col-lg-5 col-md-6">
                    <label for="hasta" class="m-0 label-primary">Hasta</label>
                    <input type="date" class="form-control" id="hasta" value="{{ Date('Y-m-d') }}">
                </div>
                <div class="form-group col-lg-2 col-md-12 d-flex flex-column">
                    <button href="javascript:void(0)" type="submit" class="btn btn-bold btn-pure btn-primary"
                        onclick="consultarHistorial()"
                        style="padding: 10px 10px 10px 10px;
                        margin-top: 20px;
                        border-color: snow;
                        background-color: #2ecc71;
                        border-radius: 20px;
                        text-align: center;
                        color: #ffffff;"><i
                            class="fa fa-search"></i>
                        Consultar Historial</button>
                </div>
            </div>
        </div>
        <hr>
        <section class="content-header">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="alert alert-success" role="alert">
                                <span class="fa fa-check-circle"></span> <!-- Icono de check -->
                                <strong>¡Atención!</strong> Para ver la información en la tabla, tienes que filtrar por
                                fecha y click en consultar.
                            </div>
                        </div>
                    </div>
                    <table id="tableHistorial" width="100%" class='table dataTables_wrapper container-fluid dt-bootstrap4 no-footer'></table>
                </div>
            </div>
            <div class="form-group col-lg-3 col-md-12 d-flex flex-column">
                <a href="javascript:void(0)" class="btn-m btn-success-m" onclick="clickExcelHistorial()"
                    style="padding: 10px 10px 10px 10px;
                margin-top: 20px;
                border-color: snow;
                background-color: #2ecc71;
                border-radius: 20px;
                text-align: center;
                color: #ffffff;">
                    <i class="fa fa-file"></i> Exportar excel</a>
            </div>
        </section>


    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="auth/plugins/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="auth/plugins/datatable/dataTables.config.min.js"></script>
    <script type="text/javascript" src="auth/js/historial/index.js"></script>
@endsection
