@extends('layouts.store.app')

@section('viewComment')
    <!-- MESSAGE -->
    <section class="cart-back"></section>
    <section class="cart-wrapp wrapp-comment cart-product-view"></section>
@endsection

@section('main')

    <!-- OFFERS -->
    <section class="row">
        <section class="cards-tit">
            <h2>Te recomendamos</h2>
        </section>
        <section class="grid-cards">
            @if($Products != null && count($Products) > 0)
                @foreach($Products as $q)
                    <div class="cards-product">
                        <a href="{{ asset('/product/product-').$q['id'] }}">
                            {{ Html::image('uploads/products/'.$q['image'], '', array('title' => $q['name'])) }}
                            <h1>{{ $q['name'] }}</h1>
                            <h3>{{ $q['user'] }}</h3>
                        </a>
                        <div class="product-like"><a href="{{ asset('/product/product-').$q['id'] }}"><i class="fas fa-heart"></i> {{ $q['clientFavorite'] }} <!--<span class="txtrecom">recomiendan</span>--></a></div>
                        <div class="product-comment">
                            <a href="javascript:void(0)" class="btn-comment" onclick="viewComment('{{ $q['id'] }}')">{{ $q['clientComments'] }} <i class="fas fa-comments"></i></a></div>
                        <a href="{{ asset('/product/product-').$q['id'] }}">
                            @if($q['discount'])
                                <div class="product-price">S/  {{ number_format($q['price_venta'], 2) }} &nbsp
                                    <span class="price-offer">S/ {{ number_format($q['price'], 2) }}
                                </div>
                            @else
                                <div class="product-price">S/  {{ number_format($q['price'], 2) }}
                                </div>
                            @endif


                        </a>
                    </div>
                @endforeach
            @endif

        </section>
    </section>

@endsection