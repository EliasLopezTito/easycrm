$(function(){

    const $vendedor_id = $("#vendedor_id");

    moment.locale('es');

    var $startDate = (parseInt(moment().format('D')) >= 16) ? moment().startOf('month').add(15, 'days') : moment().subtract(1, 'month').startOf('month').add(15, 'days');
    var $endDate = (parseInt(moment().format('D')) >= 16) ? moment().endOf('month').add(15, 'days') : moment().subtract(1, 'month').endOf('month').add(15, 'days');

    function cb($startDate, $endDate) {
        $('#reportrange span').html($startDate.format('MMMM D, YYYY') + ' - ' + $endDate.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        autoUpdateInput: false,
        startDate: $startDate,
        endDate: $endDate,
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 15 días': [moment().subtract(14, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Esta Campaña': [
                (parseInt(moment().format('D')) >= 16) ? moment().startOf('month').add(15, 'days') : moment().subtract(1, 'month').startOf('month').add(15, 'days'),
                (parseInt(moment().format('D')) >= 16) ? moment().endOf('month').add(15, 'days') : moment().subtract(1, 'month').endOf('month').add(15, 'days')],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Desde Siempre': [moment(new Date("2018-01-01")), moment()]
        }
    }, cb);

    cb($startDate, $endDate);

    filtrarReporte();

    $vendedor_id.on("change", function(){
        filtrarReporte();
    });

    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        filtrarReporte();
    });

    function filtrarReporte(){

        var formData = new FormData();
        formData.append("_token",$("input[name=_token]").val());
        formData.append("fecha_inicio", moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD"));
        formData.append("fecha_final", moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD"));
        formData.append("vendedor_id", $("#vendedor_id").val());

        actionAjax("/reporte/filtro_vendedores", formData, "POST", function(data){

            Highcharts.chart('vision_general_estados', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Visión General Estados'
                },
                accessibility: {
                    announceNewData: {
                        enabled: true
                    }
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Registros'
                    }
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '({point.count})',
                        },
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> <b> ({point.count})</b> del total<br/>'
                },

                series: [
                    {
                        name: "Registros",
                        colorByPoint: true,
                        data: data.Vendedores
                    }
                ],
                drilldown: {
                    series: data.VendedoresDetalles
                }
            });

            Highcharts.chart('vision_general_pipeLine', {
                chart: {
                    type: 'funnel'
                },
                title: {
                    text: 'Registros Totales'
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b> ({point.y:,.0f})',
                            softConnector: true
                        },
                        center: ['40%', '50%'],
                        neckWidth: '30%',
                        neckHeight: '25%',
                        width: '80%'
                    }
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: 'Registrados',
                    data: data.EstadosGlobal
                }],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            plotOptions: {
                                series: {
                                    dataLabels: {
                                        inside: true
                                    },
                                    center: ['50%', '50%'],
                                    width: '100%'
                                }
                            }
                        }
                    }]
                }
            });

            Highcharts.chart('acciones', {

                chart: {
                    styledMode: true
                },

                tooltip: {
                    pointFormat: 'Registrados: <b>{point.y:,.0f}</b>'
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b> ({point.y:,.0f})',
                            softConnector: true
                        },
                        center: ['40%', '50%'],
                        neckWidth: '30%',
                        neckHeight: '25%',
                        width: '80%'
                    }
                },

                title: {
                    text: 'Registros por Acciones'
                },

                series: [{
                    type: 'pie',
                    allowPointSelect: true,
                    keys: ['name', 'y', 'selected', 'sliced'],
                    data: data.Acciones,
                    showInLegend: true
                }]
            });
        });


    }

});
