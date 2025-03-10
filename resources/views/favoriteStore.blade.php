@extends('layouts.store.app')

@section('title')
    <title> Comprando | Tiendas Favoritas </title>
@endsection


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
            <p class="account-tit">Tiendas favoritas</p>
            <div class="recomen-wrapp"></div>
        </div>
    </section>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            getItemsStoreFavorite(false);
        })
    </script>
@endsection