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
            Editar Cliente
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <label for="fechaInicio">Celular o DNI</label>
                <input type="text" class="form-control" id="numberSearch" name="numberSearch">
            </li>
            <li class="breadcrumb-item btn-link">
                <button type="button" class="btn btn-primary" id="btnConsultClient">Consultar</button>
            </li>
        </ol>
    </section>

    <section class="content">

        <div class="table-responsive">
            <table class="table table-hover" id="clienteDataSearch">
                <thead>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Celular</th>
                    <th>Whatsapp</th>
                    <th>Email</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acción</th>
                </thead>
                <tbody>
                    
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
        const routeAjax = "{{ route('user.store-search-client') }}";
        var routeEditClient = "{{ route('user.edit-client-unit', ['id' => ':id']) }}";
    </script>
    <script src="{{ asset('auth/js/datatable/dom.js') }}"></script>
@endsection