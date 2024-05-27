@extends('layouts.store.app')

@section('main')
    <!-- MI CUENTA -->
    <section class="account-wrapp">
        <div class="account-div">
            <div class="result-opt">
                <p>Ordenar por:</p>
                <br>
                <a id="btnMenorPrecio" href="javascript:void(0)" class="result-a">Menor precio</a>
                <a id="btnMayorPrecio" href="javascript:void(0)" class="result-a">Mayor precio</a>
            </div>
        </div>
        <div class="account-div">
            <input type="hidden" id="q" name="q" value="{{ $q }}">
            <p class="resul-txt">Resultados: {{ count($products) }} coincidencia(s) con la palabra "{{ $q }}"</p>

            <div class="filter_result">
                @foreach($products as $q)
                <div class="result">
                    <a href="{{ asset('/product/product-').$q->id }}">
                        {{ Html::image('uploads/products/'.$q->image->name, '', array('title' => $q->name)) }}
                        <p class="result-pro">{{ $q->name }}</p>
                        <p class="result-sto">{{ $q->user->name }}</p>
                        <p class="result-pre">S/ {{ $q->discount ? number_format($q->price_venta, 2)  : number_format($q->price, 2)  }}</p>
                    </a>
                </div>
                @endforeach
            </div>


        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        var order = 'asc';
        $("#btnMenorPrecio").click(function () { order = 'asc'; filter(); });
        $("#btnMayorPrecio").click(function () { order = 'desc';  filter(); });

        function filter() {

            const formData = new FormData();
            formData.append('_token', $("meta[name=csrf-token]").attr("content"));
            formData.append('q', $("#q").val().trim());
            formData.append('order', order);

            actionAjax("/product/filter", formData, "POST", function (data) {
                if(data.length > 0){
                    var html = '';
                    $.each(data, function (i,v) {
                        html += '<div class="result">' +
                            '<a href="/product/product-'+v.id+'">' +
                            '<img src="/uploads/products/' + v.image.name + '">' +
                            '<p class="result-pro">'+v.name+'</p>\n' +
                            '<p class="result-sto">' + v.user.name + '</p>\n' +
                            '<p class="result-pre">S/ ' + agregarCommaMillions(parseFloat((v.price.toString().replace(/,/g, ''))).toFixed(2)) + '</p>\n' +
                            '</a>\n' +
                            '</div>';
                    });
                    $(".filter_result").html(html);
                }else{
                    $(".filter_result").html("<p> No se encontrarón resultados en su búsqueda.</p>");
                }
            });
        }

    </script>
@endsection