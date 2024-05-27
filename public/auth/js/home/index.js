var OnSuccessFiltro, OnFailureFiltro;
$(function(){

    const $cliente_id = localStorage.getItem("cliente_id"),  $reasignar_id = $("#reasignar_id");
    const $cards_list = $("#cards-list"), $cards_detail = $("#cards-detail");

    var $estado_id_filter = $("#estado_id_filter").val(),
    $vendedor_id = $("#vendedor_id").val(), $name = $("#name").val(), $startDate = "", $endDate = "";

    pagination = true;

    if($cliente_id != null && $cliente_id != "")
        verSeguimiento($cliente_id);
    else
        listarLeads();

    $reasignar_id.change(function(){
        if($(this).val() != ""){
            var formData = new FormData();
            formData.append("_token", $("input[name=_token]").val());
            formData.append("reasignar_id", $(this).val());
            formData.append("array_leads", getAllCheckedLeads());
            confirmAjax("/usuario/reasignar", formData, "POST", "¿Está seguro de reasignar " + getAllCheckedLeads().length + " Leads a " + $reasignar_id.find("option:selected").text() + " ?", null, function(data){
                if(data.Success){
                    resetView();listarLeads();
                }else{
                    toastr.error("Hubo un error al hacer la reasignación, vuelva a intentarlo", "Error",  "Error");
                }
            });
            $(this).val("");
        }
    });

    function getAllCheckedLeads(){
        var array_leads = [];
        $cards_list.find('input[type="checkbox"]:checked').each(function (i, item) {
            array_leads[i] = parseInt($(item).attr("id").split("-")[1]);
        });
        return array_leads;
    }

    $("#name").on("keypress", function(e){
        if (e.keyCode == 13) $(".filterSearch").click();
    });

    function listarLeads(){

        listPagination(true);

        $(window).scroll(function(){
            if(pagination)listPagination(false);
        });

        function listPagination(reset){

            const $Page = reset ? window.location.origin + "/?page=1" : $('.endless-pagination').data('next-page');
            const $Filters = "estado_id="+$estado_id_filter+"&vendedor_id="+$vendedor_id+"&name="+$name+"&fecha_inicio="+$startDate+"&fecha_final="+$endDate;

            if($Page !== null){

                if(reset){

                    $("#loading-clients").show();

                    $.get($Page+"&"+$Filters, function(data){
                        $('.clientes').html("").append(data.clientes);
                        $('.endless-pagination').data('next-page', data.next_page);
                    }).always(function(){
                        $("#loading-clients").hide();
                    });
                }else{
                    clearTimeout( $.data(this, "scrollCheck") );

                    $.data( this, "scrollCheck", setTimeout( function(){

                        $.data( this, "scrollCheck", setTimeout(function(){
                            var scroll_postion_for_clientes_load = $(window).height() + $(window).scrollTop() + 100;
                            if(scroll_postion_for_clientes_load >= $(document).height()){
                                $("#loading-clients").show();
                                $.get($Page+"&"+$Filters, function(data){
                                    $('.clientes').append(data.clientes);
                                    $('.endless-pagination').data('next-page', data.next_page);
                                }).done(function(){
                                    $("#loading-clients").hide();
                                });
                            }
                        }));
                    }, 350));
                }
            }
        }
    }

    moment.locale('es');

    var start = (parseInt(moment().format('D')) >= 16) ? moment().startOf('month').add(15, 'days') : moment().subtract(1, 'month').startOf('month').add(15, 'days');
    var end = (parseInt(moment().format('D')) >= 16) ? moment().endOf('month').add(15, 'days') : moment().subtract(1, 'month').endOf('month').add(15, 'days');

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        autoUpdateInput: false,
        startDate: start,
        endDate: end,
        ranges: {
            'Todos': [moment(new Date("2018-01-01")), moment()],
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Esta Campaña': [
                (parseInt(moment().format('D')) >= 16) ? moment().startOf('month').add(15, 'days') : moment().subtract(1, 'month').startOf('month').add(15, 'days'),
                (parseInt(moment().format('D')) >= 16) ? moment().endOf('month').add(15, 'days') : moment().subtract(1, 'month').endOf('month').add(15, 'days')],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Todo el Año': [moment().subtract(1, 'month').startOf('year'), moment().subtract(1, 'month').endOf('year')]
        }
    }, cb);

    cb(start, end);

    $("body").on("click", ".card button.btn",  function () {
        const $id = $(this).attr("data-info").split("-")[1];
        verSeguimiento($id);
    });

    $("body").on("click", ".card .btn-delete",  function () {
        const $id = $(this).attr("data-info").split("-")[1];
        const formData = new FormData();
        formData.append('_token', $("input[name=_token]").val());
        formData.append('id', $id);
        confirmAjax("/cliente/delete", formData, "POST", "¿Está seguro de eliminar el Lead " + $id, null, function(data) {
            if(data.Success){
                resetView();listarLeads();
            }
        });
    });

    $("#modalRegistrarInscripcion").on("click", function () {
        invocarModal("/cliente/partialView", function ($modal) {
            if ($modal.attr("data-reload") === "true"){
                resetView();listarLeads();
            }
        });
    });

    $(".filterSearch").on("click", function () {
        $estado_id_filter = $("#estado_id_filter").val();
        $vendedor_id = $("#vendedor_id").val();
        $name = $("#name").val();
        $startDate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD");
        $endDate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD");
        resetView(); listarLeads();
        $("#modal-right").modal("hide");
        localStorage.setItem("cliente_id", "");
    });

    function verSeguimiento($id) {
        invocarVista("/cliente/partialViewSeguimiento/"+$id, function(data){
            if(data != null && data != "") showView(data);
            else toastr.error("El Lead que está búscando no se ha encontrado", "Error",  "Error");
        });
    }

    function showView(data){
        pagination = false;
        $cards_list.addClass("hidden");
        $cards_detail.append(data).removeClass("hidden");
    }

    function resetView(){
        pagination = true;
        $cards_list.removeClass("hidden");
        $cards_detail.html("").addClass("hidden");
    }

});
