@extends('layouts.store.app')

@section('main')

    <!-- BUSINESS-->
    <section class="row">
        <section class="cards-tit">
            <h2>Los mejores negocios</h2>
        </section>
        <section class="grid-cards">
            @foreach($Users as $q)
                <div class="cards-product">
                    <a href="{{ asset('/stores/store-'.$q['id']) }}">
                        {{ Html::image('/uploads/users/'.$q['image'], '', array('title' => $q['name'], 'class' => 'imgstore')) }}
                    </a>
                    <h1>{{ $q['name'] }}</h1>
                    <br>
                    <div class="product-like"><a href=""><i class="fas fa-heart"></i> {{ $q['clientStoreFavorite'] }} <span class="txtrecom">recomiendan</span></a></div>
                </div>
            @endforeach
        </section>
    </section>

@endsection