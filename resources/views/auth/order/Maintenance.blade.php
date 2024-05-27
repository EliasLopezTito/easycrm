@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
    <style type="text/css">table td{ vertical-align: middle !important; text-align: center}</style>
@endsection

@section('content')
    <section class="content-header">
        <h1> {{ Auth::user()->perfil_id == \NavegapComprame\App::$PERFIL_ADMINISTRADOR ? "" : "Mis" }}  Órdenes <small> Listado </small></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">

                <form>

                    <div class="box-body">
                        <h4 class="modal-title">Detalle de la órden {{ explode("O",  $Order->code )[1] }}</h4>

                        <br><br>

                        <div class="row">
                            <div class="col-sm-12">
                                <p style="font-weight: bold;text-transform: uppercase">TIENDA: {{ $Order->user->name }}</p>
                            </div>
                            <div class="col-sm-12">
                                <p style="font-weight: bold;text-transform: uppercase">CLIENTE: {{ $Order->client->name. " ".$Order->client->last_name }}</p>
                            </div>
                            @if(( ($Order->order_status_id == 2 || $Order->order_status_id == 3) && \NavegapComprame\App::$PERFIL_EMPRESA == Auth()->user()->perfil_id && Auth()->user()->statu) ||
                            \NavegapComprame\App::$PERFIL_ADMINISTRADOR == Auth()->user()->perfil_id)
                                <div class="col-sm-12">
                                    <p style="font-weight: bold;text-transform: uppercase">DIRECCIÓN: {{ $Order->client->address }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p style="font-weight: bold;text-transform: uppercase">DNI: <span style="color:red">{{ $Order->client->dni }}</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p style="font-weight: bold;text-transform: uppercase">CELULAR: <span style="color:red">{{ $Order->client->phone }}</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p style="font-weight: bold;text-transform: uppercase">E-MAIL: <span style="color:red">{{ $Order->client->email }}</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p style="font-weight: bold;text-transform: uppercase">TIPO DE PAGO: <span style="color:red">{{ $Order->typePay->name }}</span></p>
                                </div>
                            @endif
                        </div>


                        <input type="hidden" id="id" name="id" value="{{ $Order != null ? $Order->id : 0 }}">
                        <input type="hidden" id="code" name="code" value="{{ explode("O",  $Order->code )[1] }}">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Order->orderDetail as $q)
                                <tr>
                                    <td> {{ Html::image('/uploads/products/'.$q->product->image->name, '', array('style' => 'width:75px')) }}</td>
                                    <td> {{ $q->product->name }}</td>
                                    <td> {{ $q->quantity }}</td>
                                    <td> {{ $q->subtotal }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <br>

                        <div class="col-sm-12 text-right">
                            <p style="font-size: 20px;font-weight: bold;">Total: <span style="color:red"> {{ $Order->total }}</span></p>
                        </div>

                        <br>

                        <div class="col-sm-12 text-right">
                            <p style="font-size: 20px;font-weight: bold;">Comisión de Servicio: <span style="color:red"> {{ number_format(( ($Order->total*$Order->client->city->comision)) , 2)}}</span></p>
                        </div>

                        @if($Order->order_status_id == 1 )
                            <input type="hidden" name="order_status_id" id="order_status_id" value="2">
                        @elseif($Order->order_status_id == 2)
                            <input type="hidden" name="order_status_id" id="order_status_id" value="3">
                        @endif

                        <br>

                        <div class="col-sm-12 text-center">
                            @if(($Order->order_status_id == 1 && \NavegapComprame\App::$PERFIL_EMPRESA == Auth()->user()->perfil_id && Auth()->user()->statu) ||
                       (\NavegapComprame\App::$PERFIL_ADMINISTRADOR == Auth()->user()->perfil_id && $Order->order_status_id == 1))
                                <button type="submit" class="btn btn-primary" id="btnSubmit">Aceptar Pedido</button>
                            @elseif(($Order->order_status_id == 2 && \NavegapComprame\App::$PERFIL_EMPRESA == Auth()->user()->perfil_id && Auth()->user()->statu) ||
                                (\NavegapComprame\App::$PERFIL_ADMINISTRADOR == Auth()->user()->perfil_id && $Order->order_status_id == 2))
                                <button type="submit" class="btn btn-primary" id="btnSubmit">Completar Pedido</button>
                            @endif
                        </div>

                        <br>

                    </div>

                </form>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="/auth/views/order/Maintenance.js"></script>
@endsection


