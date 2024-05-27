@extends('layouts.store.app')

@section('title')
    <title> Comprando | Pedido Enviado </title>
@endsection

@section('main')
    <!-- PRODUCTOS DETAILS -->
    <section class="confirm-wrapp">
        <div class="ok">
            <p class="ok-tit">¡Felicitaciones!</p>
            <br>
            <p>
                Ya enviamos tu pedido, el negocio se pondrá en contacto contigo lo mas pronto posible.<br><br><br>
                Gracias por comprar en <span class="ok-resal">Comprando.pe</span>.<br><br><br>
            </p>
            <p class="ok-tit">¡Vuelve pronto!</p>
            <hr><br>
            <a href="{{ route('index') }}">Volver a comprar</a>
            <a href="{{ route('client.account') }}">Ver mi cuenta</a>
        </div>
    </section>
@endsection