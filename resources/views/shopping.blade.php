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
            <p class="account-tit">Mis órdenes</p>

            @foreach($Shoppings as $q)
                <div class="compras-wrapp">
                    <div class="compras-item">
                        <p>Nro de Orden:</p>
                        <span class="nped">{{ explode("O", $q->code)[1] }}</span>
                    </div>
                    <div class="compras-item">
                        <p>Fecha:</p>
                        {{ \Carbon\Carbon::parse($q->created_at)->format("d/m/yy") }}
                    </div>
                    <div class="compras-item">
                        <p>Total:</p>
                        S/ {{ number_format($q->total, 2) }} soles
                    </div>
                    <div class="compras-item">
                        <p>Estado</p>
                        {{ $q->orderStatus->name }}
                    </div>
                    <div class="compras-item">
                        <a href="{{ route('client.shoppingDetail', ['id' => $q->id ]) }}">Ver detalle</a>
                    </div>
                </div>
            @endforeach

    </section>

@endsection