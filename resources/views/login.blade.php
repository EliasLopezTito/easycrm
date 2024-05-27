@extends('layouts.store.app')

@section('main')

    <section class="confirm-wrapp">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <section class="wrapp-login">
            <div class="divlog">
                <p>¡Hola! Ingresa a tu cuenta</p>
                <form action="{{ route('client.login.post') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required>
                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                        <a href="" class="a-nego">Olvidaste tus datos?</a>
                    </div>
                    <input type="submit" class="botones" value="Iniciar Sesión">
                </form>
                <p class="o">- o -</p>
                <a href="{{ route('client.provider', 'facebook') }}" class="botones btnface">Ingresa con Facebook</a>
            </div>

            <div class="divlog">
                <p>¿Eres nuevo? ¡Qué esperas!</p>
                <a href="{{ route('client.register') }}" class="botones btncrear">Crear una cuenta</a>
                <br>
                <p class="o">- o -</p>
                <a href="javascript:void(0)" class="a-nego">Crear una cuenta como negocio</a>
            </div>

        </section>

    </section>

@endsection