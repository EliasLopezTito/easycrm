@extends('layouts.store.app')

@section('main')
    <!-- PRODUCTOS DETAILS -->
    <p class="tpago-tit">¡Ya casi esta listo! Revisa y confirma tu pedido</p>
    <form action="{{ route('order.checkoutConfirm')  }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" id="typePay" name="typePay" value="{{ $typePay->id }}">
        <section class="confirm-wrapp">
            <section class="tpago-wrapp">
                <div class="tpago-item">
                    <div class="tpago-radio">
                        <p class="tpago-stit">¿Cómo llegará tu pedido?</p><br>
                        <i class="fas fa-truck"></i> Acordada con el vendedor
                    </div>
                    <div class="tpago-radio line">
                        <p class="tpago-stit">¿Cómo lo pagarás?</p><br>
                        <i class="{{ $typePay->icon }}"></i> {{$typePay->name}}
                    </div>
                </div>
                <div class="tpago-item">
                    <div class="tpago-radio">
                        <p class="tpago-stit">¿Cuánto pagarás?</p><br>
                        <i class="{{ $typePay->icon }}"></i> El pago total es de: <span class="tpago-stotal">S/ {{ $totalPrice }}</span>
                    </div>
                    <div class="tpago-radio line">
                        <p class="tpago-stit">¿Este es tu número de contacto?</p><br>
                        <i class="fas fa-phone-square-alt"></i>
                        <input type="tel" name="phone" id="phone" class="tpago-cel"
                               value="{{ Auth::guard('client')->user()->phone }}" onkeypress="return isNumberKey(event)" minlength="7" maxlength="9"  placeholder="-" required>
                    </div>
                    <div class="tpago-radio">
                        <button type="submit" id="confirmarCompra" class="botones tpago-btn">Confimar pedido</button>
                    </div>
                </div>
            </section>
        </section>
    </form>
@endsection

