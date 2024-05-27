@extends('layouts.store.app')

@section('viewComment')
    <!-- MESSAGE -->
    <section class="cart-back"></section>
    <section class="cart-wrapp wrapp-comment cart-product-view"></section>
@endsection

@section('main')

    <!-- STORE DETAILS -->
    <section class="store-wrapp">
        <section class="store-portada">
            {{ Html::image('uploads/users/'.$Store->image_banner->name, '', array('style' => 'height:100%' )) }}
        </section>
        <section class="store-perfil">
            <div class="item">
                {{ Html::image('uploads/users/'.$Store->image_logo->name, '') }}
            </div>
            <div class="item">
                <p class="store-nom">{{ $Store->name  }}</p>
                <p class="store-sdes">{{ $Store->address  }}</p>

                <p class="store-stit">Horario de atención</p>
                <p class="store-sdes">
                    Lunes a viernes: 8:00 am - 8:00 pm<br>
                    Sábados: 8:00 am - 12:00 pm
                </p>
                <p class="store-stit">Tipo de entrega</p>
                <p class="store-sdes">
                    @foreach($UserTypeSends as $ut)
                       {{ $ut->type_sends['name']}} |
                    @endforeach
                </p>
                <p class="store-stit">Tipo de pago</p>
                <p class="store-sdes">
                    @foreach($UserTypePays as $ut)
                         {{ $ut->type_pays['name']}} |
                    @endforeach
                </p>
            </div>
            <div class="item">
                <p class="store-stit">Descripción</p>
                <p class="store-sdes">
                    {{ $Store->description }}
                </p>
                <p class="store-stit">Categorias</p>
                <p class="store-sdes">
                    @foreach($Store->categories as $q)
                        {{ $q->categories->name }} |
                    @endforeach
                </p>
                <i class="fas fa-heart"></i> <span class="store-stit">
                    {{ count($Store->clientStoreFavorite) }} recomiendan</span>
            </div>
        </section>
    </section>

    <!-- PRODUCTS -->
    <section class="row">
        <section class="cards-tit">
            <h2>Mis productos</h2>
        </section>
        <section class="grid-cards">
            @foreach($ProductsBusiness as $q)
            <div class="cards-product">
                <a href="{{ asset('/product/product-').$q->id }}">
                    {{ Html::image('uploads/products/'.$q->image->name, '', array('title' => $q->image->name)) }}
                    <h1>{{ $q->name  }}</h1>
                    <br>
                </a>
                <div class="product-like"><a href="{{ asset('/product/product-').$q->id }}"><i class="fas fa-heart"></i> {{ count($q->clientFavorite) }} <span class="txtrecom">recomiendan</span></a></div>
                <div class="product-comment"><a href="javascript:void(0)" class="btn-comment" onclick="viewComment('{{ $q->id }}')">{{ count($q->clientComments) }} <i class="fas fa-comments"></i></a></div>
                <a href="{{ asset('/product/product-').$q->id }}">
                    <div class="product-price">S/ {{ $q->discount ? number_format($q->price_venta, 2) : number_format($q->price, 2) }}</div>
                </a>
            </div>
            @endforeach
        </section>
    </section>

    <!-- OFFERS -->
    <section class="row">
        <section class="cards-tit">
            <h2>Mis ofertas</h2>
        </section>
        <section class="grid-cards">

            @foreach($ProductsOffers as $q)
            <div class="cards-product">
                <div class="product-offer">{{ number_format($q->porcentage_discount, 0) }} %</div>
                <a href="{{ asset('/product/product-').$q->id }}">
                    {{ Html::image('uploads/products/'.$q->image->name, '', array('title' => $q->name)) }}
                    <h1>{{ $q->name  }}</h1>
                    <br>
                </a>
                <div class="product-like"><a href="{{ asset('/product/product-').$q->id }}"><i class="fas fa-heart"></i> {{ count($q->clientFavorite) }} <span class="txtrecom">recomiendan</span></a></div>
                <div class="product-comment"><a  href="javascript:void(0)" class="btn-comment" onclick="viewComment('{{ $q->id }}')">{{ count($q->clientComments) }} <i class="fas fa-comments"></i></a></div>
                <a href="{{ asset('/product/product-').$q->id }}"><div class="product-price">S/ {{ number_format($q->price_venta, 2) }} &nbsp;<span class="price-offer">{{ number_format($q->price, 2) }}</span></div></a>
            </div>
            @endforeach
        </section>
    </section>

    <!-- PATROCINADOS -->
    <section class="row">
        <section class="cards-tit"><h2>Productos Patrocinados</h2></section>

        <div class="sponsored-content">
            <div class="sponsored-slider owl-carousel">
                @foreach($ProductsPatro as $q)
                    <div class="cards-product">
                        <a href="{{ asset('/product/product-'.$q->id) }}">
                            {{ Html::image('uploads/products/'.$q->image->name, '', array('title' => $q->name)) }}
                        </a>
                        <h1>{{ $q->name  }}</h1>
                        <div class="product-like"><i class="fas fa-heart"></i> <a href="" class="txtrecom">{{ count($q->clientFavorite) }} recomiendan</a></div>
                        <div class="product-comment"><a href="" class="txtrecom">{{ count($q->clientComments) }} </a> <i class="fas fa-comments"></i></div>
                        <div class="product-price">S/ {{ $q->discount ? number_format($q->price_venta, 2) : number_format($q->price, 2) }}</div>
                        <div class="product-actions"></div>
                    </div>
                @endforeach
            </div>
        </div>

    </section>

@endsection