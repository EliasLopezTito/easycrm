@extends('layouts.store.app')

@section('viewComment')
    <!-- MESSAGE -->
    <section class="cart-back"></section>
    <section class="cart-wrapp wrapp-comment cart-product-view"></section>
@endsection

@section('main')

    <section class="main-banner owl-carousel">
        @if($Banners != null)
            @foreach($Banners as $q)
                <a href="{{ $q->href }}"><div>{{ Html::image('uploads/banners/'.$q->image->name)  }}</div></a>
            @endforeach
        @endif
    </section>

    <div class="categorias">
        <div class="slider-categories owl-carousel">
            @foreach($Categories as $q)
                <div class="cat-item">
                    <a href="{{ route('product.categorie', ['id' => $q->id]) }}">
                    {{ Html::image('/uploads/categories/'.$q->image->name) }} {{ $q->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- RECOMENDACION -->
    <section class="row">
        <section class="cards-tit">
            <h2>Te recomendamos</h2>
            <a href="{{ route('favorites')  }}" class="product-ver">Ver todos</a>
        </section>
        <section class="grid-cards">
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
                        <div class="product-price">S/  {{ $q['discount'] ? number_format($q['price_venta'], 2) : number_format($q['price'], 2) }} &nbsp
                            @if($q['discount'])
                                <span class="price-offer">S/. {{ number_format($q['price'], 2) }}
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </section>
    </section>

    <!-- OFFERS -->
    <section class="row">
        <section class="cards-tit">
            <h2>Las mejores ofertas</h2>
            <a href="{{ route('offers') }}" class="product-ver">Ver todos</a>
        </section>
        <section class="grid-cards">
            @foreach($ProductsOfferts as $q)
                <div class="cards-product">
                    <div class="product-offer">{{ number_format($q->porcentage_discount, 0) }} %</div>
                    <a href="{{ asset('/product/product-').$q->id }}">
                        {{ Html::image('uploads/products/'.$q->image->name, '', array('title' => $q->name)) }}
                        <h1>{{ $q->name }}</h1>
                        <h3>{{ $q->user->name }}</h3>
                    </a>
                    <div class="product-like"><a href=""><i class="fas fa-heart"></i> {{ count($q->clientFavorite) }} <span class="txtrecom">recomiendan</span></a></div>
                        <div class="product-comment"><a href="javascript:void(0)" class="btn-comment" onclick="viewComment('{{ $q['id'] }}')">{{ count($q->clientComments) }} <i class="fas fa-comments"></i></a></div>
                    <a href="{{ asset('/product/product-').$q->id }}">
                        <div class="product-price">S/  {{ number_format($q->price_venta, 2) }} &nbsp;
                            @if($q->discount)
                                <span class="price-offer">S/. {{ number_format($q->price, 2) }}
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </section>
    </section>

    <!-- BUSINESS-->
    <section class="row">
        <section class="cards-tit">
            <h2>Los mejores negocios</h2>
            <a href="{{ route('stores') }}" class="product-ver">Ver todos</a>
        </section>
        <section class="grid-cards">
            @foreach( $Stores as $q)
                <div class="cards-product">
                    <a href="{{ asset('/stores/store-').$q['id'] }}">
                        {{ Html::image('uploads/users/'.$q['image'], '', array('title' => $q['name'], 'class' => 'imgstore')) }}
                    <h1>{{ $q['name'] }}</h1>
                    <br>
                    <div class="product-like"><a href=""><i class="fas fa-heart"></i> {{ $q['clientStoreFavorite'] }}
                            <span class="txtrecom">recomiendan</span></a></div>
                </div>
            @endforeach
        </section>
    </section>

@endsection