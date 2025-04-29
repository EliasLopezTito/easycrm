@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('auth/css/report/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h3>Buscar cliente</h3>

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <div class="input-group">
                    <input type="text" class="form-control" id="numberSearch" name="numberSearch" placeholder="Celular o DNI">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary" id="btnConsultClient"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </li>
        </ol>
    </section>

    <section class="content p-20 bg-white">

        <div class="table-responsive">
            <table class="table table-hover" id="clienteDataSearch">
                <thead>
                    <th>Carrera o curso</th>
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