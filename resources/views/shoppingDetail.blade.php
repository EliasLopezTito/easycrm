@extends('layouts.store.app')

@section('main')

    <!-- MI CUENTA -->
    <section class="account-wrapp">
        <div class="account-div">
            <ul>
                <li><a href="{{ route('client.account') }}">Perfil</a></li>
                <li><a href="{{ route('client.shopping') }}">Órdenes</a></li>
                <li><a href="{{ route('client.favorite') }}">Recomendados</a></li>
                <li><a href="{{ route('client.storeFavorite') }}">Tiendas</a></li>
            </ul>
        </div>

        <div class="account-div">
            <p class="account-tit">Mis órdenes: Orden Nro {{ explode("O", $Shopping->code)[1] }}</p>

            <section class="confirm-wrapp cart-wrappx">
                @foreach($ShoppingDetail as $q)
                    <div class="cart-item">
                        <div class="cart-col">
                            {{ Html::image('/uploads/products/'.$q->product->image->name) }}
                            <p class="cart-pro">{{ $q->product->name }}</p>
                            <p class="cart-store">De: {{ $q->product->user->name }}</p>
                        </div>
                        <div class="cart-col">S/ {{ number_format( ($q->subtotal/$q->quantity), 2 )  }}</div>
                        <div class="cart-col">{{ $q->quantity }}</div>
                        <div class="cart-col">S/ {{ number_format($q->subtotal , 2) }}</div>
                    </div>
                @endforeach
                <p class="cart-stotal">TOTAL ORDEN: S/ {{ number_format($Shopping->total, 2) }}</p>
                <a href="{{ route('client.shopping') }}" class="botones cart-btncomprar">Regresar</a>
                <div class="clear"></div>
            </section>
        </div>

    </section>

@endsection