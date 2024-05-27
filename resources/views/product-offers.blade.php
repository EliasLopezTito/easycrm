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
            <h2>Las mejores ofertas</h2>
        </section>
        <section class="grid-cards">
            @foreach($Products as $q)
                <div class="cards-product">
                    <div class="product-offer"> {{ number_format($q->porcentage_discount, 0) }} %</div>
                    <a href="{{ asset('/product/product-').$q->id }}">
                        {{ Html::image('/uploads/products/'.$q->image->name, '', array('title' => $q->name)) }}
                        <h1>{{ $q->name }}</h1>
                        <h3>{{ $q->user->name }}</h3>
                    </a>
                    <div class="product-like"><a href="{{ asset('/product/product-').$q->id }}"><i class="fas fa-heart"></i> {{ count($q->clientFavorite) }} <span class="txtrecom">recomiendan</span></a></div>
                    <div class="product-comment"><a href="javascript:void(0)" class="btn-comment" onclick="viewComment('{{ $q['id'] }}')">{{ count($q->clientComments) }} <i class="fas fa-comments"></i></a></div>
                    <a href="{{ asset('/product/product-').$q->id }}">
                        <div class="product-price">S/  {{ number_format($q->price_venta, 2) }} &nbsp
                            @if($q->discount)
                                <span class="price-offer">S/. {{ number_format($q->price   , 2) }}
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </section>
    </section>

@endsection