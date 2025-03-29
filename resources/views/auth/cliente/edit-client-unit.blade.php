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
            Editar Cliente Unidad
        </h1>
    </section>

    <section class="content">

        <form action="{{ route('user.store-edit-client-unit') }}">
            @csrf
            <input type="hidden" name="idClient" value="{{ $clientData->id }}">
            <div class="form-group">
                <label for="name">Nombres Completos:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $clientData->nombres }}">
            </div>
            <div class="form-group">
                <label for="paternalSurname">Apellido Paterno:</label>
                <input type="text" class="form-control" id="paternalSurname" name="paternalSurname" value="{{ $clientData->apellido_paterno }}">
            </div>
            <div class="form-group">
                <label for="maternalSurname">Apellido Materno:</label>
                <input type="text" class="form-control" id="maternalSurname" name="maternalSurname" value="{{ $clientData->apellido_materno }}">
            </div>
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" value="{{ $clientData->dni  }}">
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" class="form-control" id="celular" name="celular" value="{{ $clientData->celular  }}">
            </div>
            <div class="form-group">
                <label for="whatsapp">Whatsapp:</label>
                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ $clientData->whatsapp  }}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $clientData->email  }}">
            </div>
            <div class="form-group">
                <label for="direction">Dirreción:</label>
                <input type="text" class="form-control" id="direction" name="direction" value="{{ $clientData->direccion }}">
            </div>
            <div class="form-group">
                <label for="date">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $clientData->fecha_nacimiento }}">
            </div>
            <div class="form-group">
                <label for="provinces">Provincias:</label>
                <select name="provincia_id" id="provinces" class="form-control">
                    @foreach ($provincesData as $province)
                        <option value="{{ $province->id }}" {{ $province->id == $clientData->provincia_id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="districts">Distritos:</label>
                <select name="distrito_id" id="districts" class="form-control">
                    <option value="">Seleccionar</option>
                </select>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Editar</button>
            </div>
        </form>

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
        let routeFiltro = "{{ route('user.filtroDistrito', ':id') }}";
        let idDistrito = "{{ $clientData->distrito_id ?? '' }}";
    </script>
    <script src="{{ asset('auth/js/cliente/v2/editClient.js') }}"></script>
@endsection