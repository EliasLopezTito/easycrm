@extends('auth.layout.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('auth/css/report/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <div class="row" style="width: 100%;">
            @if (Auth::check() && Auth::user()->profile_id == 1)
                <div class="col-md-6 col-12">
                    <h3 class="mb-0">
                        Nombre Asesor(a): <b class="text-danger">{{ $responseData['imgData']->usersAsesor }}</b>
                    </h3>
                </div>
            @endif
            <div class="col-md-6 col-12">
                <h3 class="mb-0">
                    Nombre Cajero(a): <b class="text-danger">{{ $responseData['userData']['name'] }}</b>
                </h3>
            </div>
        </div>
    </section>    

    <section class="content" style="background-color: white; margin-top: 20px; margin-bottom: 20px; padding: 20px;">
        @if ($responseData['followUpData']['cashier_observation'] != null)
            <div class="form-group">
                <label for="observationCashier">Observación Cajera</label>
                <textarea name="observationCashier" id="observationCashier" name="observationCashier" cols="30" rows="10" style="resize: none;" class="form-control" disabled>{{ $responseData['followUpData']['cashier_observation'] }}</textarea>
            </div>
        @endif
        @if ($responseData['followUpData']['supervisory_observation'] != null)
            <div class="form-group">
                <label for="observationSupervisory">Observación Supervisora</label>
                <textarea name="observationSupervisory" id="observationSupervisory" name="observationSupervisory" cols="30" rows="10" style="resize: none;" class="form-control" disabled>{{ $responseData['followUpData']['supervisory_observation'] }}</textarea>
            </div>
        @endif
        @if ($responseData['followUpData']['dni_front_observation'] != null)
            <div class="form-group">
                <label for="dniFrontObservation">Observación DNI (Frontal)</label>
                <textarea name="dniFrontObservation" id="dniFrontObservation" name="dniFrontObservation" cols="30" rows="10" style="resize: none;" class="form-control" disabled>{{ $responseData['followUpData']['dni_front_observation'] }}</textarea>
            </div>
        @endif
        @if ($responseData['followUpData']['dni_rear_observation'] != null)
            <div class="form-group">
                <label for="dniDearObservation">Observación DNI (Reverso)</label>
                <textarea name="dniDearObservation" id="dniDearObservation" name="dniDearObservation" cols="30" rows="10" style="resize: none;" class="form-control" disabled>{{ $responseData['followUpData']['dni_rear_observation'] }}</textarea>
            </div>
        @endif
        @if ($responseData['followUpData']['izy_pay_observation'] != null)
            <div class="form-group">
                <label for="izyPayObservation">Observación IZY PAY</label>
                <textarea name="izyPayObservation" id="izyPayObservation" name="izyPayObservation" cols="30" rows="10" style="resize: none;" class="form-control" disabled>{{ $responseData['followUpData']['izy_pay_observation'] }}</textarea>
            </div>
        @endif
        @if ($responseData['followUpData']['vaucher_observation'] != null)
            <div class="form-group">
                <label for="vaucherObservation">Observación Vaucher</label>
                <textarea name="vaucherObservation" id="vaucherObservation" name="vaucherObservation" cols="30" rows="10" style="resize: none;" class="form-control" disabled>{{ $responseData['followUpData']['vaucher_observation'] }}</textarea>
            </div>
        @endif
        <form action="{{ route('user.client.store-see-observation') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="idClient" value="{{ $responseData['imgData']->idUnico }}">
            <div class="form-group">
                <label for="name">Nombres Completos:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $responseData['imgData']->nombresClient }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            <div class="form-group">
                <label for="paternalSurname">Apellido Paterno:</label>
                <input type="text" class="form-control" id="paternalSurname" name="paternalSurname" value="{{ $responseData['imgData']->apellidoPaternoClient }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            <div class="form-group">
                <label for="maternalSurname">Apellido Materno:</label>
                <input type="text" class="form-control" id="maternalSurname" name="maternalSurname" value="{{ $responseData['imgData']->apellidoMaternoClient }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" value="{{ $responseData['imgData']->dniClient }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" class="form-control" id="celular" name="celular" value="{{ $responseData['imgData']->phoneClient }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $responseData['imgData']->emailClient }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            <div class="form-group">
                <label for="direction">Dirreción:</label>
                <input type="text" class="form-control" id="direction" name="direction" value="{{ $responseData['imgData']->addressClient }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            <div class="form-group">
                <label for="date">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $responseData['imgData']->dateOfBirth }}" @if (Auth::check() && Auth::user()->profile_id != 1) disabled @endif>
            </div>
            @if ($responseData['followUpData']['dni_front_observation'] != null)
                <div class="form-group">
                    <label for="dniFrontUpdate" style="margin-top: 10px;">Foto del DNI (Parte Frontal)</label>
                    <input type="file" name="dniFrontUpdate" id="dniFrontUpdate" class="form-input" accept="image/png, image/jpeg, image/jpg" required>
                </div>
            @endif
            @if ($responseData['followUpData']['dni_rear_observation'] != null)
                <div class="form-group">
                    <label for="dniRearUpdate" style="margin-top: 10px;">Foto del DNI (Parte Posterior)</label>
                    <input type="file" name="dniRearUpdate" id="dniRearUpdate" class="form-input" accept="image/png, image/jpeg, image/jpg" required>
                </div>
            @endif
            @if ($responseData['followUpData']['izy_pay_observation'] != null)
                <div class="form-group">
                    <label for="izyPayUpdate" style="margin-top: 10px;">Foto del IZYPAY</label>
                    <input type="file" name="izyPayUpdate" id="izyPayUpdate" class="form-input" accept="image/png, image/jpeg, image/jpg" required>
                </div>
            @endif
            @if ($responseData['followUpData']['vaucher_observation'] != null)
                <div class="form-group">
                    <label for="vaucherUpdate" style="margin-top: 10px;">Foto del Comprobante de Pago</label>
                    <input type="file" name="vaucherUpdate" id="vaucherUpdate" class="form-input" accept="image/png, image/jpeg, image/jpg" required>
                </div>
            @endif
            <div class="form-group">
                <label for="schoolNameUpdate" style="margin-top: 10px;">Nombre del Colegio</label>
                <input type="text" name="schoolNameUpdate" id="schoolNameUpdate" class="form-input" value="{{ $responseData['imgData']->schoolName }}">
            </div>
            <div class="form-group">
                <label for="completionDateUpdate" style="margin-top: 10px;">Fecha de Termino</label>
                <input type="date" name="completionDateUpdate" id="completionDateUpdate" class="form-input" value="{{ $responseData['imgData']->completionDate }}">
            </div>
            @if ($errors->any())
                <div class="alert alert-danger p-2">
                    <ul class="list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="from-group text-center">
                <button type="submit" class="btn btn-secondary">Guardar</button>
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
@endsection