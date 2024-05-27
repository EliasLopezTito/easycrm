@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
@endsection

@section('content')
    <section class="content-header">
        <h1> {{ Auth::user()->perfil_id == \NavegapComprame\App::$PERFIL_ADMINISTRADOR ? "" : "Mis" }}  Órdenes <small> Listado </small></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" id="userPerfil" value="{{ $UserPerfil }}">
                <div class="box box-primary">
                    <div class="box-body">
                        <table id="tableOrder" class="table table-striped table-bordered table-hover"></table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')

    {{ Html::script('/auth/plugins/DataTables/js/jquery.dataTables.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/js/dataTables.bootstrap.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/dataTables.buttons.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/buttons.flash.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/jszip.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/plugins/buttons/buttons.html5.min.js')  }}
    {{ Html::script('/auth/plugins/DataTables/js/dataTables.config.min.js')  }}

    {{ Html::script('/auth/views/order/index.js')  }}

@endsection
