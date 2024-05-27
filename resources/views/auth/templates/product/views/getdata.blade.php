@extends('auth.templates.product.app')

@section('scripts-template')
    {{ Html::script('auth/js/angularjs/models/product/getdata.js') }}
@endsection


@section('content-template')

    <div class="page-header">
        <div class="page-title"><h3>Listado Productos</h3></div>
    </div>

    <div class="add-product">
    <a href="{{ asset('auth/product/create') }}"> Registrar Producto </a>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h6 class="panel-title"><i class="icon-table"></i>Productos</h6></div>
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
                    <th>Imagen
                        <span class="glyphicon sort-icon"></span>
                    </th>
                    <th ng-click="sort('name')">Nombre
                        <span class="glyphicon sort-icon" ng-show="sortKey=='name'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th ng-click="sort('price')">Precio
                        <span class="glyphicon sort-icon" ng-show="sortKey=='price'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th ng-click="sort('created_at')">Fecha de Registro
                        <span class="glyphicon sort-icon" ng-show="sortKey=='created_at'" ng-class="{'icon-arrow-up2':reverse,'icon-arrow-down2':!reverse}"></span>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr dir-paginate="product in products|orderBy:sortKey:reverse|filter:search|itemsPerPage:12">
                    <td><a href="{{ asset('/auth/upload/products') }}/@{{ product.image }}"  class="lightbox">
                         <img src="{{ asset('/auth/upload/products') }}/@{{ product.image }}" alt="@{{ product.name }}" class="img-media"></a>
                    </td>
                    <td>@{{ product.name }}</td>
                    <td>@{{ product.price }}</td>
                    <td>@{{ product.created_at }}</td>
                    <td>
                        <div class="table-controls">
                            <a href="javascript:void(0)" ng-click="deleteProduct(product.id)"
                               class="btn btn-default btn-icon btn-xs tip" title="Eliminar"
                               data-original-title="Eliminar Producto"><i class="icon-remove2"></i></a>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>

            <div class="pagination-content">
            <dir-pagination-controls max-size="12"  direction-links="true" boundary-links="true" ></dir-pagination-controls>
            </div>

        </div>
    </div>


    <!-- Small modal -->
    <div id="small_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="icon-question4"></i>¿Estás seguro de suspender este artículo?</h4>
                </div>

                <div class="modal-body with-padding"></div>

                <div class="modal-footer">
                    <button ng-click="deleteBusiness()" class="btn btn-action" ng-disabled="disableBtn">Aceptar</button>
                    <button id="cerrar_model" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /small modal -->

    <!-- Small modal -->
    <div id="small_modal_cancel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #2cb373;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="icon-question4"></i>¿Estás seguro de recuperar este artículo?</h4>
                </div>

                <div class="modal-body-recover with-padding"></div>

                <div class="modal-footer">
                    <button ng-click="recoverBusiness()" class="btn btn-action" ng-disabled="disableBtn">Aceptar</button>
                    <button id="cerrar_model_cancel" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /small modal -->

@endsection