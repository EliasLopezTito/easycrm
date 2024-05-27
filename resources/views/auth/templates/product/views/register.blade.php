@extends('auth.templates.product.app')

@section('scripts-template')
    {{ Html::script('ajax/lodash.min.js') }}
    {{ Html::script('auth/js/angularjs/models/product/register.js') }}
@endsection

@section('content-template')

    <div class="page-header">
        <div class="page-title"><h3>Registrar Producto</h3></div>
    </div>

    <div class="form">
        <form name="frmadd" method="POST" enctype="multipart/form-data" ng-submit="add()">
            {{ csrf_field() }}

            <div class="panel panel-default">
                <div class="panel-heading"><h6 class="panel-title"><i class="icon-clipboard"></i> Formulario</h6></div>
                <div class="panel-body">
                    <div class="block-inner text-danger">
                        <h6 class="heading-hr">
                            Hora de vender <small class="display-block">
                                Para mostrar el producto en la web, complete el siguiente formulario</small>
                        </h6>
                    </div>

                    <div class="image-profile">
                        {{ Html::image('auth/images/user.jpg', 'Producto' , array('class' => 'profile')) }}
                        <div class="upload-profile">
                            <input id="report-screenshot" name="profile" type="file" class="styled form-control" ng-files="setTheFiles($files)" valid-file
                                   ng-model="product.profile" accept="image/*" required />
                        </div>
                    </div>

                    <div class="information-business">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="name">Nombre</label>
                                        <input id="name" type="text" name="name" class="form-control"
                                               ng-model="product.name" required autofocus />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="price">Precio</label>
                                        <div class=" input-group">
                                        <span class="input-group-addon">S/.</span>
                                        <input id="price" type="text" name="price" class="form-control" ng-model="product.price" required >
                                        <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="subtitle-form">
                                        <h6>Categorias</h6>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <div class="row">
                                          <span ng-repeat="categorie in categories">
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline">
                                                    <label class="categorie" for="C@{{ categorie.id }}"> @{{ categorie.name }}
                                                        <input type="checkbox" value="C@{{ categorie.id }}"
                                                               ng-click="updateQuestionValue(categorie)"
                                                               ng-model="categorie.checked" name="categorie" id="C@{{ categorie.id }}" >
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="subtitle-form">
                                        <h6>Bañado / Cubierto</h6>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <div class="row">

                                            <span ng-repeat="flavor in flavors">
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline">
                                                    <label class="categorie" for="F@{{ flavor.id }}"> @{{ flavor.name }}
                                                        <input type="checkbox" value="F@{{ flavor.id }}"
                                                               ng-click="updateQuestionValueFlavor(flavor)"
                                                               ng-model="flavor.checked" name="flavor" id="F@{{ flavor.id }}" >
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-gruop">
                            <div class="subtitle-form">
                                <h6>Características</h6>
                            </div>

                            <div class="row">
                                <div class="col-md-4 tp-relav">
                                    <label for="floor">Pisos </label>
                                    <select id="floor" name="floor" data-placeholder="Seleccione opción..."
                                            data-ng-model="product.floor" class="select-full" tabindex="2" required>
                                        <option value="">-- Seleccione una opción--</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                                <div class="col-md-4 tp-relav form-group">
                                    <label for="proportion">Proporción </label>
                                    <select id="proportion" name="proportion" data-placeholder="Seleccione opción..."
                                            data-ng-model="product.proportion" class="select-full" tabindex="2" required>
                                        <option value="">-- Seleccione una opción--</option>
                                        <option value="Pequeña">Pequeña</option>
                                        <option value="Mediana">Mediana</option>
                                        <option value="Grande">Grande</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="shape">Forma</label>
                                    <select id="shape" name="shape" data-placeholder="Seleccione opción..."
                                            data-ng-model="product.shape" class="select-full" tabindex="2" required>
                                        <option value="">-- Seleccione una opción--</option>
                                        <option value="Circular">Circular</option>
                                        <option value="Rectangular">Rectangular</option>
                                        <option value="Cuadrarda">Cuadrarda</option>
                                    </select>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                    <label for="information">Descripción </label>
                                        <textarea id="information"  name="information" class="form-control" ng-model="product.information"   required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row form-actions">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-save pull-right"
                                        ng-disabled="frmadd.$invalid || valuecat.length==0 || valueflav.length==0"> Guardar </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection