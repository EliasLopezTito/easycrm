$(function () {

    const $button = $(".modal-footer > button");

    var $export_estado_id = $("#export_estado_id"), $export_vendedor_id = $("#export_vendedor_id"), $export_modalidad_id = $("#export_modalidad_id"),
        $export_carrera_id = $("#export_carrera_id"), $export_turno_id = $("#export_turno_id"), $export_fecha_inicio = $("#export_fecha_inicio"),
        $export_fecha_final = $("#export_fecha_final");

    $export_modalidad_id.change(function () {
        $export_carrera_id.html("").append(`<option value="">-- Todos --</option>`);
        if ($(this).val() != 0) {
            actionAjax("/curso/filtroCurso/" + $(this).val(), null, "GET", function (data) {
                $.each(data, function (i, e) {
                    $export_carrera_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });

    moment.locale('es');

    var start = (parseInt(moment().format('D')) >= 16) ? moment().startOf('month').add(15, 'days') : moment().subtract(1, 'month').startOf('month').add(15, 'days');
    var end = (parseInt(moment().format('D')) >= 16) ? moment().endOf('month').add(15, 'days') : moment().subtract(1, 'month').endOf('month').add(15, 'days');

    function cb(start, end) {
        $('#export_reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#export_reportrange').daterangepicker({
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

    $button.click(function () {

        $export_fecha_inicio.val(moment($('#export_reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD"));
        $export_fecha_final.val(moment($('#export_reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD"));

        const $export_estado_id_v = $export_estado_id.val() != "" ? $export_estado_id.val() : 0;
        const $export_vendedor_id_v = $export_vendedor_id.val() != "" ? $export_vendedor_id.val() : 0;
        const $export_modalidad_id_v = $export_modalidad_id.val() != "" ? $export_modalidad_id.val() : 0;
        const $export_carrera_id_v = $export_carrera_id.val() != "" ? $export_carrera_id.val() : 0;
        const $export_turno_id_v = $export_turno_id.val() != "" ? $export_turno_id.val() : 0;

        actionAjax('/cliente/exportExcel/' + $export_fecha_inicio.val().replace(/\//g, '-') + '/' + $export_fecha_final.val().replace(/\//g, '-') +
            '/' + $export_estado_id_v + '/' + $export_vendedor_id_v + '/' + $export_modalidad_id_v + '/' + $export_carrera_id_v + '/' + $export_turno_id_v, null, "GET", function (data) {
                $.each(data, function (i, e) {
                    swal("Bien!", "Exportación de leads realizada con éxito, en unos minutos encontrará el link de descarga en sus notificaciones.", "success");
                });
            });

        /* 
          window.open('/cliente/exportExcel/'+$export_fecha_inicio.val().replace(/\//g, '-')+'/'+$export_fecha_final.val().replace(/\//g, '-')+
          '/'+$export_estado_id_v+'/'+$export_vendedor_id_v+'/'+$export_modalidad_id_v+'/'+$export_carrera_id_v+'/'+$export_turno_id_v)
   */
    });
});
