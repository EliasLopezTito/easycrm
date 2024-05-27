@extends('auth.templates.order.app')

@section('scripts-template')
    {{ Html::script('auth/js/angularjs/models/order/getdata.js') }}
@endsection


@section('content-template')

    <div class="page-header">
        <div class="page-title"><h3>Listado Pedidos</h3></div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title"><i class="icon-table"></i>Pedidos</h6></div>
        <div class="datatable-media">
            <form class="form-inline listdatatable">
                <div class="form-group">
                    <label >Buscar: </label>
                    <input type="text" ng-model="search" class="form-control" placeholder="Buscar...">
                </div>
            </form>
            <table class="table table-bordered ">
                <thead>
                <tr>
                    <th ng-click="sort('name')">N° Pedido
                        <span class="glyphicon sort-icon" ng-show="sortKey=='name'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th ng-click="sort('price')">Cliente
                        <span class="glyphicon sort-icon" ng-show="sortKey=='price'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th ng-click="sort('dni')">DNI Cliente
                        <span class="glyphicon sort-icon" ng-show="sortKey=='price'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th ng-click="sort('type')">Tipo de Entrega
                        <span class="glyphicon sort-icon" ng-show="sortKey=='type'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th ng-click="sort('created_at')">Fecha de Solicitud
                        <span class="glyphicon sort-icon" ng-show="sortKey=='created_at'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th ng-click="sort('status')">Estado del Pedido
                        <span class="glyphicon sort-icon" ng-show="sortKey=='status'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr dir-paginate="order in orders|orderBy:sortKey:reverse|filter:search|itemsPerPage:12"
                    ng-class="order.status == 'Pendiente' ? 'danger-user' :
                    order.status == 'Completado' ? 'procsing-user' :
                    order.status == 'Entregado' ? 'success-user' : ''">
                    <td>@{{ order.code }}</td>
                    <td>@{{ order.clientName +" "+order.clientLastName }}</td>
                    <td>@{{ order.clientDni }}</td>
                    <td>@{{ order.type }}</td>
                    <td>@{{ order.created_at }}</td>
                    <td>@{{ order.status }}</td>
                    <td>
                        <div class="table-controls">

                            <a href="#default_modal" ng-click="viewDetail(order)"
                               data-toggle="modal" role="button"
                               class="btn btn-default btn-icon btn-xs tip" title="Ver Detalle"
                               data-original-title="Ver Detalle"><i class="icon-eye2"></i></a>

                            <a href="#statu_modal" ng-click="changeStatus(order.id)"
                               data-toggle="modal" role="button"
                               class="btn btn-default btn-icon btn-xs tip" title="Actualizar Estado"
                               data-original-title="Actualizar Estado"><i class="icon-spinner8"></i></a>

                            <a ng-if="order.type == 'Delivery' && order.status == 'Completado'"
                               href="#deliver_modal"  ng-click="asignedDelivery(order.id)"
                               data-toggle="modal" role="button"
                               class="btn btn-default btn-icon btn-xs tip" title="Asignar Repartidor"
                               data-original-title="Asignar Repartidor"><i class="icon-car"></i></a>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>

            <div class="pagination-content">
            <dir-pagination-controls max-size="6"  direction-links="true" boundary-links="true" ></dir-pagination-controls>
            </div>

        </div>
    </div>


    <div id="default_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"></h4>
                </div>

                <div class="modal-body with-padding">
                  {{ Html::image('/images/icons/loader-pay.gif', 'Loading',
                  array('class' => 'order-loader')) }}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Statu modal -->
    <div id="statu_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="icon-question4"></i>Manejo de estados</h4>
                </div>

                <form name="frmadd" method="POST" enctype="multipart/form-data" ng-submit="orderChangeStatus()">
                <div class="modal-body with-padding">
                    {{ csrf_field() }}
                    <div>
                        <input type="hidden" ng-mcdel="order.codeOrder" id="codeOrder" name="codeOrder">
                        <label for="statu">Estado : </label>
                        <select id="statu" name="statu" data-placeholder="Choose subscription..."
                                data-ng-model="order.statu" data-ng-options="o.name for o in options"
                                class="select-full" tabindex="2" required>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-action" ng-disabled="disableBtn">Asignar y Notificar</button>
                    <button id="cerrar_model" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    <!-- /Statu modal -->



    <!-- Delivered modal -->
    <div id="deliver_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="icon-question4"></i>Repartidores</h4>
                </div>

                <form name="frmdelv" method="POST" enctype="multipart/form-data" ng-submit="orderChangeDelivered()">
                    <div class="modal-body with-padding">
                        {{ csrf_field() }}
                        <div>
                            <input type="hidden" ng-mcdel="delivered.codeOrder" id="delivered" name="delivered">
                            <label for="delivered">Repartidor : </label>
                            <select id="delivered" name="delivered" data-placeholder="Choose subscription..."
                                    data-ng-model="delivered.repartier" data-ng-options="o.name for o in optionsDelivered"
                                    class="select-full" tabindex="2" required>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-action" ng-disabled="disableBtnDelivered">Asignar y Notificar</button>
                        <button id="cerrar_model" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- /Delivered modal -->

@endsection