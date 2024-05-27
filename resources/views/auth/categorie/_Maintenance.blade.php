@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('auth/layout/css/user/_Maintenance.css') }}
@endsection

@section('content')
    <section class="content-header">
        <h1> Categoría <small> Mantenimiento </small></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">

                    <form>
                <input type="hidden" id="id" name="id" value="{{ $Categorie != null ? $Categorie->id : 0 }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-6">

                            <div class="img-content user" style="width: 50px; height: 50px">
                                    <img src="{{ $Categorie != null ?  "/uploads/categories/".$Categorie->image->name : "/auth/layout/img/default.png" }}" class="img-responsive" alt="Logo Image" title="Logo">
                                <input type="file" class="styled form-control" accept="image/jpeg, image/png" style="position: absolute;bottom: -35px;width: 215px;left: -65px;"  id="image" {{ $Categorie != null ? "" : "" }}>
                            </div>

                            <label for="name"> Nombre: </label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ $Categorie != null ? $Categorie->name : "" }}" required autocomplete="off" >
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-6 mt-15">
                            <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- MaintenenceJs -->
    {{ Html::script('/auth/views/categorie/_Maintenance.js') }}
@endsection
