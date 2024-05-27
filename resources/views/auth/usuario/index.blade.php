@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" href="auth/plugins/datatable/datatables.min.css">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Listado Usuarios
            <small>Mantenimiento</small>
        </h1>

        <ol class="breadcrumb">
            <form action="" class="mx-5 px-5">
                <select name="estado_usuario" id="estado_usuario" class="form-input">
                    <option value="" hidden>-- Seleccione el estado de usuario --</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                    <option value="">Todos</option>
                </select>
            </form>
            <li class="breadcrumb-item">
                <button type="button" id="modalRegistrarUsuario" class="btn-primary"><i class="fa fa-user-plus"></i> Registrar Usuario</button>
            </li>
        </ol>
    </section>

    <section class="content">
        @csrf
        <table id="tableUsuario" class="table table-bordered table-striped display nowrap margin-top-10 dataTable no-footer"></table>
    </section>

</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="auth/plugins/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="auth/plugins/datatable/dataTables.config.min.js"></script>
    <script type="text/javascript" src="auth/js/usuario/index.js"></script>
@endsection
