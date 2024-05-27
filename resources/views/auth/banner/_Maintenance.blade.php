@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('auth/layout/css/user/_Maintenance.css') }}
@endsection

@section('content')
    <section class="content-header">
        <h1> Portada <small> Mantenimiento </small></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">

            <form>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="{{ $Banner != null ? $Banner->id : 0 }}">
                    <div class="row">

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <div class="image_banner">
                                    <div class="image-banner-bg image-bg">
                                        <img src="{{ $Banner != null ?  "/uploads/banners/".$Banner->image->name : "/auth/layout/img/default_banner.jpg" }}" class="img-responsive" alt="Upload Banner">
                                    </div>
                                    <input type="file" class="styled form-control" id="image" accept="image/jpeg, image/png">
                                </div>
                            </div>

                            <div class="col-sm-6 mt-15">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="name"> Nombre </label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $Banner != null ? $Banner->name : "" }}" autocomplete="off" required >
                                    </div>
                                    <div class="col-sm-6 switch-field">
                                        <div class="switch-title">Estado</div>
                                        <input id="siVisible" type="radio" name="visible" value="1" {{ $Banner == null || ($Banner != null && $Banner->statu) ? "checked" : ""  }}>
                                        <label for="siVisible">Apto</label>
                                        <input id="NoVisible" type="radio" name="visible" value="0" {{ ($Banner != null && !$Banner->statu) ? "checked" : ""  }} >
                                        <label for="NoVisible">No Apto</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="departament_id"> Departamento: </label>
                                        <select name="departament_id" id="departament_id" class="form-control" required>
                                            <option value=""> -- Seleccionne -- </option>
                                            @foreach($Departaments as $c)
                                                <option value="{{ $c->id }}" {{ $Banner != null && $Banner->departament_id == $c->id ? "selected" : ""  }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="city_id"> Ciudad: </label>
                                        <select name="city_id" id="city_id" class="form-control" required>
                                            <option value=""> -- Seleccionne -- </option>
                                            @if($Banner != null)
                                                @foreach($Cities as $c)
                                                    <option value="{{ $c->id }}" {{ $Banner != null && $Banner->city_id == $c->id ? "selected" : ""  }}>{{ $c->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="href"> Url: </label>
                                        <input type="text" name="href" id="href" value="{{ $Banner != null ? $Banner->href : "" }}" class="form-control" required>
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
    {{ Html::script('/auth/plugins/select2/js/select2.js') }}
    <!-- MaintenenceJs -->
    {{ Html::script('/auth/views/banner/_Maintenance.js') }}
@endsection

