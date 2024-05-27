@extends('layouts.store.app')

@section('title')
    <title> Comprando | Registrarme </title>
@endsection


@section('main')

    <!-- REGISTER ME-->
    <section class="confirm-wrapp">
        <section class="wrapp-register">
            <div class="regitem">
                <p class="details-tit">Completa tus datos</p>
                <form method="post" action="{{ route('client.register.post') }}" id="create_customer" accept-charset="UTF-8">
                    {{ csrf_field() }}

                    <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" name="name"  placeholder="Nombres" value="{{ old('name') }}" autofocus required>
                        @if ($errors->has('name'))
                            <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                         </span>
                        @endif
                    </div>

                    <div class="{{ $errors->has('lastname') ? ' has-error' : '' }}">
                        <input type="text" id="lastname" name="lastname" placeholder="Apellidos" value="{{ old('lastname') }}"  required>
                        @if ($errors->has('lastname'))
                            <span class="help-block">
                            <strong>{{ $errors->first('lastname') }}</strong>
                         </span>
                        @endif
                    </div>

                    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}"  required>
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
                    </div>

                    <div class="{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <input type="tel" id="phone" name="phone" placeholder="Celular" value="{{ old('phone') }}" required>
                        @if ($errors->has('phone'))
                            <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                         </span>
                        @endif
                    </div>

                    <div class="regcheck">
                        <input type="checkbox" name="acepto" id="acepto">&nbsp;
                        <label for="acepto">Acepto los <a href="">Términos y Condiciones</a> y la <a href="">Declaración de Privacidad</a>.</label>
                    </div>
                    <div class="clear"></div>
                    <input type="submit" class="botones btnreg" value="Registrarme">
                    <div class="regcheck"></div>
                </form>
            </div>
        </section>
    </section>

@endsection