@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('auth/layout/css/user/_Maintenance.css') }}
@endsection

@section('content')
    <section class="content-header">
        <h1> Producto <small> Mantenimiento </small></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">

                     <form>
                        <div class="modal-body">
                            <input type="hidden" id="id" name="id" value="{{ $Product != null ? $Product->id : 0 }}">
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="img-content user">
                                                    <div class="image-user-bg">
                                                        <img src="{{ $Product != null ?  "/uploads/products/".$Product->image->name : "/auth/layout/img/default.png" }}" class="img-responsive" alt="Producto Image" title="Logo">
                                                    </div>
                                                    <input type="file" class="styled form-control" name="image" id="image" accept="image/jpeg, image/png">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                @if(Auth::user()->perfil_id == \NavegapComprame\App::$PERFIL_ADMINISTRADOR)
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <label for="user_id"> Negocio: </label>
                                                        <select name="user_id" id="user_id" class="form-control" required >
                                                            @foreach($Users as $q)
                                                                <option value="{{ $q->id }}" {{ $Product != null && $Product->user_id == $q->id ? "selected" : "" }}>{{ $q->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <label for="name"> Nombre: </label>
                                                        <input type="text" name="name" id="name" class="form-control" value="{{ $Product != null ? $Product->name : "" }}" autocomplete="off" required >
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 switch-field p-0">
                                                    <div class="switch-title">Patrocinado:</div>
                                                    <input id="siPatrocinado" type="radio" name="patrocinado" value="1" {{ ($Product != null && $Product->patrocinado) ? "checked" : ""  }}>
                                                    <label for="siPatrocinado">Si</label>
                                                    <input id="NoPatrocinado" type="radio" name="patrocinado" value="0" {{ ($Product == null || ($Product != null && !$Product->patrocinado)) ? "checked" : ""  }} >
                                                    <label for="NoPatrocinado">No</label>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="price"> Precio: </label>
                                                        <input type="text" name="price" id="price" class="form-control decimal" value="{{ $Product != null ? $Product->price : "" }}" autocomplete="off" required >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4 switch-field">
                                                <div class="switch-title">Descuento:</div>
                                                <input id="siDescuento" type="radio" name="discount" value="1" {{ ($Product != null && $Product->discount) ? "checked" : ""  }}>
                                                <label for="siDescuento">Si</label>
                                                <input id="NoDescuento" type="radio" name="discount" value="0" {{ ($Product == null || ($Product != null && !$Product->discount)) ? "checked" : ""  }} >
                                                <label for="NoDescuento">No</label>
                                            </div>
                                            <div class="hidden content-discount">
                                                <div class="col-sm-4">
                                                    <label for="porcentage_discount">Porcentaje:</label>
                                                    <input type="text" name="porcentage_discount" id="porcentage_discount" class="form-control decimal" value="{{ $Product != null ? $Product->porcentage_discount : "" }}" autocomplete="off"  >
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="price_venta">Precio Venta:</label>
                                                    <input type="text" name="price_venta" id="price_venta" class="form-control decimal" value="{{ $Product != null ? $Product->price_venta : "" }}" autocomplete="off"  readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="category_id"> Categorías: </label>
                                                <select name="category_id[]" id="category_id" class="form-control" required data-initial="{{ ($Product != null && count($ProductCategories) > 0 ) ?  implode(",", $ProductCategories)  : "" }}" multiple="multiple">
                                                    @if(Auth::user()->id == \NavegapComprame\App::$PERFIL_ADMINISTRADOR)
                                                        @foreach($Categories as $q)
                                                            <option value="{{ $q->id }}">{{ $q->name  }}</option>
                                                        @endforeach
                                                    @else
                                                        @if($Categories != null && count($Categories) > 0)
                                                            @foreach($Categories as $q)
                                                                <option value="{{ $q->categories->id }}">{{ $q->categories->name  }}</option>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="description"> Descripción: </label>
                                                <textarea name="description" id="description" class="form-control" cols="30" rows="2">{{ $Product != null ? $Product->description : "" }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="information"> Información: </label>
                                                <textarea name="information" id="information" class="form-control" rows="5">{{ $Product != null ? $Product->information : "" }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Guardar</button>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    {{ Html::script('/auth/plugins/ckeditor/ckeditor.js') }}
    <script>
        CKEDITOR.replace( 'information' );
    </script>
    {{ Html::script('/auth/plugins/select2/js/select2.js') }}
    <!-- MaintenenceJs -->
    {{ Html::script('/auth/views/product/_Maintenance.js') }}
@endsection


