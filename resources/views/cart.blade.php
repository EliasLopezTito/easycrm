@extends('layouts.store.app')

@section('main')
    <!-- PRODUCTOS DETAILS -->
    <section class="confirm-wrapp cart-wrappx">
        <p class="cart-titulo">Estás a unos segundos de disfrutar tus productos :)</p>
        <p>Tienes <span class="CartCount">0</span> items en tu carrito. <?php echo $cartCount == 0 ? "No te quedes, elige tu primer producto <a href='/' class='alindex'>aquí</a>" : "" ?></p>

        <div id="cart-total-items"></div>

        <div class="clear"></div>
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

@section('scripts')
    <script type="text/javascript">
        getItemsCart(false);
    </script>
@endsection