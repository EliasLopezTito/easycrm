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
            Editar Adicional del curso <b class="text-danger">{{ $CarreraData->name }}</b>
        </h1>
    </section>

    <section class="content bg-white p-20">

        <form action="{{ route('user.store-edit-client-adicional-unit') }}">
            @csrf
            <input type="hidden" name="idLeadAdicional" value="{{ $clientAdicionalData->id }}">
            @if (Auth::user()->email == "useraul@gmail.com" || Auth::user()->id == 131)
                <div class="form-group">
                    <label for="codeStudent">Código de Alumno:</label>
                    <input type="text" class="form-control" id="codeStudent" name="codeStudent" value="{{ $clientAdicionalData->codigo_alumno_adicional }}">
                </div>
                <div class="form-group">
                    <label for="carrera_adicional_id">Cursos:</label>
                    <select name="carrera_adicional_id" id="carrera_adicional_id" class="form-control">
                        @foreach ($carreraSelect as $carrera)
                            <option value="{{ $carrera->id }}" {{ $carrera->id == $clientAdicionalData->carrera_adicional_id ? 'selected' : '' }}>
                                {{ $carrera->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if (Auth::user()->email == "useraul@gmail.com" || Auth::user()->id == 131)
                <div class="form-group">
                    <label for="sede">Sedes:</label>
                    <select name="sede_id" id="sede" class="form-control">
                        @foreach ($sedeData as $sede)
                            <option value="{{ $sede->id }}" {{ $sede->id == $clientAdicionalData->sede_adicional_id ? 'selected' : '' }}>
                                {{ $sede->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="locals">Locales:</label>
                    <select name="local_id" id="locals" class="form-control">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
            @endif
            <div class="form-group">
                <label for="tipo_operacion_id">Tipo de operación:</label>
                <select name="tipo_operacion_id" id="tipo_operacion_id" class="form-control">
                    @foreach ($tipoPagoData as $tipoPago)
                        <option value="{{ $tipoPago->id }}" {{ $tipoPago->id == $clientAdicionalData->tipo_operacion_adicional_id ? 'selected' : '' }}>
                            {{ $tipoPago->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="modalidad_pago">Modalidado de pago:</label>
                <select name="modalidad_pago" id="modalidad_pago" class="form-control">
                    <option value="1" {{ 1 == $clientAdicionalData->modalidad_pago_adicional ? 'selected' : '' }}>Presencial</option>
                    <option value="2" {{ 2 == $clientAdicionalData->modalidad_pago_adicional ? 'selected' : '' }}>Virtual</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nro_operacion">Número de operación:</label>
                <input type="text" class="form-control" id="nro_operacion" name="nro_operacion" value="{{ $clientAdicionalData->nro_operacion_adicional  }}">
            </div>
            <div class="form-group">
                <label for="monto">Monto:</label>
                <input type="text" class="form-input decimal" id="monto" name="monto" value="{{ $clientAdicionalData->monto_adicional  }}">
            </div>
            <div class="form-group">
                <label for="nombre_titular">Nombre del título:</label>
                <input type="text" class="form-input" id="nombre_titular" name="nombre_titular" value="{{ $clientAdicionalData->nombre_titular_adicional  }}">
            </div>
            <div class="form-group">
                <label for="promocion">Promoción:</label>
                <input type="text" class="form-input" id="promocion" name="promocion" value="{{ $clientAdicionalData->promocion_adicional  }}">
            </div>
            <div class="form-group">
                <label for="observacion">Observación:</label>
                <input type="text" class="form-input" id="observacion" name="observacion" value="{{ $clientAdicionalData->observacion_adicional  }}">
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="form-group text-center">
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
        @if (Auth::user()->email == "useraul@gmail.com" || Auth::user()->id == 131)
            let routeSedeFiltro = "{{ route('user.filtroLocal', ':id') }}";
            let idLocal = "{{ $clientAdicionalData->local_adicional_id ?? '' }}";
        @endif
    </script>
    <script src="{{ asset('auth/js/cliente/v2/editClient.js') }}"></script>
@endsection