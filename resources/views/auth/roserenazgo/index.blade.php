@extends('layouts.auth.app')

@section('styles')
    {{ Html::style('/auth/plugins/DataTables/css/dataTables.bootstrap.min.css')  }}
    <style type="text/css">
        table td{ padding:0 !important; margin: 0 !important;}
        table tfoot td{ background: #222d32;color: #fff;}
        table tfoot td:first-child{ font-weight: bold; padding-top: 7px !important;text-align: center}
        table tfoot td input{ border: 0 !important; outline: 0; background-color: transparent !important;color: #FFFFFF !important; text-align: right}
    </style>
@endsection

@section('content')

    <div class="container-fluid">
        <form>
        <input type="hidden" id="reserenazgos" value="{{ count($Roserenazgos) }}">
        <div class="row">
            <div class="col-sm-12">
                <div class="row mt-15">
                    <div class="col-sm-2">
                        <label for="anio"> AÑO: </label>
                        <select name="anio" id="anio" class="form-control">
                            @foreach($Anios as $a)
                                <option value="{{ $a }}" {{ $a == \Carbon\Carbon::now()->year ? "selected" : ""}}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="mt-25">
                            <button type="submit" class="btn btn-primary col-sm-12" id="btnGuardar" >
                                <i class="fa fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="mt-25">
                            <button type="button" class="btn btn-success col-sm-12" id="btnExportExcel">
                                <i class="fa fa-file-excel-o"></i> Exportar Excel
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="mt-25">
                            <button type="button" class="btn btn-danger col-sm-12" id="btnExportPdf">
                                <i class="fa fa-file-pdf-o"></i> Exportar PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-15">
            <div class="col-sm-12 p-0">
                <button id="btnAgregarRoserenazgo" type="button" class="btn btn-warning pull-right mb-15"> <i class="fa fa-plus"></i></button>
            </div>
            <div class="col-sm-12 table-responsive p-0">
                <table id="tablaRoserenazgo" class="table table-striped table-bordered table-hover dataTable no-footer">
                    <thead>
                    <tr>
                        <th>GENÉRICO</th>
                        <th>ESPECIFICO</th>
                        <th>ENE</th>
                        <th>FEB</th>
                        <th>MAR</th>
                        <th>ABR</th>
                        <th>MAY</th>
                        <th>JUN</th>
                        <th>JUL</th>
                        <th>AGO</th>
                        <th>SEP</th>
                        <th>OCT</th>
                        <th>NOV</th>
                        <th>DIC</th>
                        <th>TOTAL</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <td colspan="2">TOTALES</td>
                        <td><input id="eneroTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="febreroTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="marzoTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="abrilTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="mayoTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="junioTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="julioTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="agostoTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="septiembreTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="octubreTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="noviembreTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="diciembreTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td><input id="totalTotal" type="text" class="form-control" readonly="readonly"></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        </form>
    </div>

@endsection

@section('scripts')
    {{ Html::script('/auth/plugins/DataTables/js/jquery.dataTables.min.js')  }}
    {{ Html::script('/auth/plugins/select2/js/select2.js') }}

    {{ Html::script('/auth/views/roserenazgo/index.js') }}
    {{ Html::script('/auth/views/roserenazgo/indexMaintenance.js') }}
@endsection
