@extends('layouts.store.app')

@section('main')
    <!-- PRODUCTOS DETAILS -->
    <p class="tpago-tit">¿Cómo quieres pagar?</p>
    <section class="confirm-wrapp">
        <form action="{{ route('order.checkoutStore')  }}" method="POST" >
        <section class="tpago-wrapp">
            <div class="tpago-item">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @foreach($TypePays as $q)
                        <div class="tpago-radio radioline">
                            <input type="radio" id="{{ $q->id }}" name="type_pay_id" value="{{ $q->id }}" required="required">
                            <label class="tapgo-radiolbl" for="{{ $q->id }}">{{ $q->name }}</label>
                        </div>
                    @endforeach
                    <button type="submit" class="botones tpago-btn tpago-btn1">Continuar</button>
            </div>
            <div class="tpago-item">
                <p class="tpago-stit">Productos seleccionados</p>

                @foreach($stores as $s)
                    <div class="tpago-store">
                        <p class="otions-tit">{{ $s['store_name'] }}</p>
                        @foreach($s['Products'] as $p)
                            <div class="tpago-products">
                                <div>{{ $p['product']['quantity'] }}</div>
                                <div>{{ $p['product']['item']->name }}</div>
                                <div>S/ {{ number_format($p['product']['price'], 2) }}</div>
                            </div>
                        @endforeach

                        <p class="tpago-stotal">Total a pagar: S/ {{ $s['store_price_total'] }} </p>
                    </div>
            @endforeach

                <p class="otions-tit tpago-total">El pago total es de: S/ {{ $totalPrice }}</p>

                <button type="submit" class="botones tpagodos">Continuar</button>

            </div>
        </section>
        </form>
    </section>
@endsection

@section('scripts')
    {{ Html::script('') }}
@endsection