<style>
    table td{ vertical-align: middle !important; text-align: center}
</style>

<div class="modal fade" id="modalOrderMaintenance"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Detalle de la órden {{ explode("O",  $Order->code )[1] }}</h4>
            </div>

            <form>
                <div class="modal-body">
                <div class="col-sm-12 p-0 mt-15">
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
                </div>

                <input type="hidden" id="id" name="id" value="{{ $Order != null ? $Order->id : 0 }}">
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

                    <div class="col-sm-12 text-right">
                        <p style="font-size: 20px;font-weight: bold;">Total: <span style="color:red"> {{ $Order->total }}</span></p>
                    </div>

                    @if($Order->order_status_id == 1 )
                        <input type="hidden" name="order_status_id" id="order_status_id" value="2">
                    @elseif($Order->order_status_id == 2)
                        <input type="hidden" name="order_status_id" id="order_status_id" value="3">
                    @endif

                </div>
                <div class="modal-footer mt-15">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    @if(($Order->order_status_id == 1 && \NavegapComprame\App::$PERFIL_EMPRESA == Auth()->user()->perfil_id && Auth()->user()->statu) ||
                        (\NavegapComprame\App::$PERFIL_ADMINISTRADOR == Auth()->user()->perfil_id && $Order->order_status_id == 1))
                        <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Aceptar Pedido</button>
                    @elseif(($Order->order_status_id == 2 && \NavegapComprame\App::$PERFIL_EMPRESA == Auth()->user()->perfil_id && Auth()->user()->statu) ||
                        (\NavegapComprame\App::$PERFIL_ADMINISTRADOR == Auth()->user()->perfil_id && $Order->order_status_id == 2))
                        <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Completar Pedido</button>
                    @endif
                </div>
            </form>

        </div>
    </div>
</div>

<!-- MaintenenceJs -->
<script type="text/javascript" src="/auth/views/order/_Maintenance.js"></script>

