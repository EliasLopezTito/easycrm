@extends('layouts.cakestore.app')

@section('title')
    <title> Comprando | Restablecer contaseña </title>
@endsection

@section('main')

    <div class="breadcrumb-wrapper">
        <div class="wrapper">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <div class="inner">
                    <a href="{{ route('index') }}" title="Back to the frontpage">Inicio</a>
                    <span aria-hidden="true">/</span>
                    <span>Restablecer contraseña</span>
                </div>
            </nav>
        </div>
    </div>

    <main class="wrapper main-content" >
        <div class="grid">
            <div class="grid__item large--one-third push--large--one-third text-center">
                <h1>Restablecer contraseña</h1>
                <div class="form-vertical">
                    <form method="post" action="{{ route('client.password.request') }}" id="create_customer" accept-charset="UTF-8">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                            <label for="email" class="hidden-label">Correo electrónico</label>
                                <input id="email" type="email" class="input-full" name="email"
                                       value="{{ $email or old('email') }}" placeholder="Correo Electrónico" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                            <label for="password" class="hidden-label">Contraseña</label>
                                <input id="password" type="password" class="input-full" name="password" placeholder="Contraseña" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="{{ $errors->has('password_confirmation') ? ' has-error' : '' }} has-feedback">
                            <label for="password-confirm" class="hidden-label">Confirma contraseña</label>
                                <input id="password-confirm" type="password" class="input-full" name="password_confirmation"
                                       placeholder="Repita contraseña" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <p>
                            <input type="submit" value="Restablecer contraseña" class="btn btn--full log">
                        </p>

                        <a href="{{ route('index') }}" class="a-home">Regresar a inicio</a>

                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection