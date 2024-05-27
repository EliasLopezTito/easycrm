@extends('layouts.store.app')

@section('title')
    <title> Comprando | Categorías</title>
@endsection

@section('main')

    <!-- BUSINESS-->
    <section class="row">
        <section class="cards-tit">
            <h2>Categorias</h2>
        </section>
        <section class="categories">
            @foreach($Categories as $q)
                    <div class="item">
                        <a href="{{ route('product.categorie', ['id' => $q->id]) }}">
                        {{ Html::image('/uploads/categories/'.$q->image->name) }}
                        <br>
                        {{ $q->name }}
                        </a>
                    </div>
            @endforeach
        </section>
    </section>

@endsection