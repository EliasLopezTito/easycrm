@extends('layouts.store.app')

@section('main')

    <!-- CART MOBILE -->
    <section class="cart-back"></section>
    <section class="cart-wrapp-mobile">
        <p class="cart-titulo">Haz agregado a tu carrito</p>
        <div class="cart-item">
            <div class="cart-col cart-des">
                <div id="cart-total-movil-items"></div>
                <a href="{{ route('cart') }}" class="botones cart-btncomprar">Ver carrito</a>
                <a href="{{ route('index') }}" class="botones botonesborder cart-btnseguir">Seguir comprado</a>
            </div>
        </div>
    </section>

    <!-- CART-BACK -->
    <section class="cart-wrapp">
        <p class="cart-titulo">Estás a unos segundos de disfrutar tus productos :)</p>
        <p>Tienes <span class="CartCount">0</span> items en tu carrito</p>
        <div id="cart-total-items"></div>
    </section>

    <!-- PIDELO AHORA MOBILE -->
    <section class="pedido-wrapp-mobile">
        <p class="cart-titulo">Hola! tu pedido es:</p>

        <div class="cart-item">
            <div id="cart-item-now-movil" style="width: 100%"></div>
            <form action="{{ route('order.checkoutStore')  }}" method="POST"  style="width: 100%">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <p class="cart-titulo pedido-p">Cómo lo quieres pagar?</p>
                <div class="pedido-items">
                    @foreach($UserTypePays as $q)
                        <div class="tpago-radio">
                            <input type="radio" id="{{ $q->type_pays->id }}" name="type_pay_id" value="{{ $q->type_pays->id }}" required="required">
                            <label class="tapgo-radiolbl" for="{{ $q->type_pays->id }}">{{ $q->type_pays->name }}</label>
                        </div>
                    @endforeach
                </div>
                @if(count($UserTypePays) > 0)
                    <button type="submit" class="botones cart-btncomprar">Continuar</button>
                @else
                    <p> Esta empresa no tiene metodo de pago.</p>
                @endif
            </form>
        </div>

    </section>
    <!-- PIDELO AHORA DESKTOP-->

    <section class="pedido-wrapp">
        <p class="cart-titulo">Hola! tu pedido es:</p>
        <div id="cart-item-now"></div>
        <form action="{{ route('order.checkoutStore')  }}" method="POST" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <p class="cart-titulo">Cómo lo quieres pagar?</p>
            <div class="pedido-items">
                @foreach($UserTypePays as $q)
                    <div class="tpago-radio">
                        <input type="radio" id="{{ $q->type_pays->id }}" name="type_pay_id" value="{{ $q->type_pays->id }}" required="required">
                        <label class="tapgo-radiolbl" for="{{ $q->type_pays->id }}">{{ $q->type_pays->name }}</label>
                    </div>
                @endforeach
            </div>
            @if(count($UserTypePays) > 0)
                <button type="submit" class="botones tpago-btn tpago-btn1">Continuar</button>
            @else
                <p> Esta empresa no tiene metodo de pago.</p>
            @endif
        </form>
    </section>


    <!-- PRODUCTOS DETAILS -->
    <section class="details-wrapp">
        <div class="details-columns">
            <div class="details-colum">
                {{ Html::image('uploads/products/'.$Product->image->name, '', array('class' => 'imgdisplay')) }}
            </div>
            <div class="details-colum">
                <p class="details-tit">{{ $Product->name }}</p>
                <p class="details-des">{{ $Product->description }}</p>
                @if(!$FavoriteClient)
                    <a href="javascript:void(0)" class="favorite-value" onclick="addToFavorite('{{ $Product->id }}')"><i class="fas fa-heart"></i>
                        <span class="FavorieCount"> {{ $Favorites }} </span></a>
                @else
                <a href="javascript:void(0)" class="favorite-value"><i class="fas fa-heart active"></i>
                    <span class="FavorieCount"> {{ $Favorites }} </span></a>
                @endif
                &nbsp;
                <a href="#comment"> <i class="fas fa-comments"></i>
                    {{ count($Product->clientComments) }}
                </a>
                <hr>
                <div class="options-wrapp">
                    <div class="details-options">
                        <p class="otions-tit"><i class="fas fa-dollar-sign"></i> Precio</p>
                        <p class="option-price">S/ {{ $Product->discount ? $Product->price_venta : $Product->price }}</p>
                    </div>
                    <div class="details-options">
                        <p class="otions-tit"><i class="fas fa-truck"></i> Tipo de entrega</p>
                        <ul style="margin-top: 10px">
                            @foreach($UserTypeSends as $ut)
                                <li> {{ $ut->type_sends['name']}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="options-wrapp">
                    <div class="details-options">
                        <p class="otions-tit"><i class="fas fa-clock"></i> Horario de atención</p>
                        Lun - Vie: 8:00am - 8:00pm<br>
                        Sab: 8:00am - 2:00pm
                    </div>
                    <div class="details-options">
                        <p class="otions-tit"><i class="fas fa-hand-holding-usd"></i> Tipo de pago</p>
                        <ul>
                            @foreach($UserTypePays as $ut)
                                <li> {{ $ut->type_pays['name']}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="div-btncar">
                    <a href="javascript:void(0)" onclick="addNowProductoToCart('{{ $Product->id }}')" class="botones btn-pidelo">¡Pídelo, YA!</a>
                    <a href="javascript:void(0)" onclick="addtoCart('{{ $Product->id }}')" class="botones btn-carro botonesborder">Agregar a carrito</a>
                </div>
            </div>
        </div>
        <hr>
        <div class="details-columns">
            <div class="details-colum">
                <p class="details-tit">Información del producto</p>
                <?php echo $Product->information ?>
            </div>
            <div class="details-colum">
                <p class="details-tit">Sobre el negocio</p>
                {{ Html::image('uploads/users/'.$Product->user->image_logo->name, '', array('class' => 'busin-logo')) }}
                <p class="busin-tit">{{ $Product->user->name }}</p>
                <p class="details-des">Venta de artículos de primera necesidad y verduras mas otros productos necesarios</p>
                @if(!$FavoriteStoreClient)
                    <a href="javascript:void(0)" class="favoriteStore-value" onclick="addToStoreFavorite('{{ $Product->user_id }}')">
                        <i class="fas fa-heart"></i> <span class="StoreFavorieCount"> {{ $FavoritesStore }} </span></a>
                @else
                    <a href="javascript:void(0)" class="favoriteStore-value"><i class="fas fa-heart active"></i>
                        <span class="StoreFavorieCount"> {{ $FavoritesStore }} </span></a>
                @endif
                <a href="javascript:void(0)" onclick="addToStoreFavorite('{{ $Product->user_id }}')" class="details-a">Recomendar</a>
            </div>
        </div>
        <hr>
        <div class="details-columns">
            <div class="details-colum">
                <p class="details-tit" id="comment">Calificación</p>
                <i class="fas fa-heart {{ $FavoriteClient ? "active" : "" }}"></i> {{ $Favorites }} {{ $Favorites == 1 ? "persona lo recomienda" : "personas lo recomiendan" }}
                <br><br>
                <p class="otions-tit">Comentarios</p>
                @foreach($ProductsComment as $p)
                    <p class="coment-nom">Por: {{ $p->client->name }}</p> <p class="coment-fech">{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y g:i a') }}</p>
                    <p>{{ $p->comment }}</p>
                    <hr>
                @endforeach
                <form action="{{ route('product.comment')  }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" id="product_id" name="product_id" value="{{  $Product->id  }}">
                    <textarea class="details-coment" id="comment" name="comment" placeholder="Ingresa tu comentario" required="required"></textarea>
                    <input type="submit" value="Enviar comentario" class="botones btncoment">
                </form>
            </div>
            <div class="details-colum nimodo">
                <p class="details-tit">Otros productos del negocio</p>
                <div class="content-other">
                    <div class="other-slider owl-carousel">
                        @foreach($ProductsBusiness as $q)
                            <div class="cards-product">
                                <a href="{{ asset('/product/product-').$q->id }}">
                                    {{ Html::image('uploads/products/'.$q->image->name, '', array('title' => $q->name)) }}
                                </a>
                                <h1>{{ $q->name }}</h1> <br>
                                <div class="product-like"><i class="fas fa-heart"></i><a href="" class="txtrecom"> {{ count($q->clientFavorite) }} recomiendan</a></div>
                                <div class="product-comment"><a  href="javascript:void(0)"> {{ count($q->clientComments) }}</a> <i class="fas fa-comments"></i></div>
                                <div class="product-price">S/ {{ $q->discount ? number_format($q->price_venta, 2) : number_format($q->price, 2) }}</div>
                                <div class="product-actions"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
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