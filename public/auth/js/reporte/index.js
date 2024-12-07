$(function () {
    console.log('actualizado marco');
    const $kpis = $("#kpis");

    const $filter_lead_report = $("#filter_lead_report");

    let estado = ESTADOS.NUEVO; let textoEstado = "Registros";

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

    filtrarReporte(estado, true);

    $('.list-button button').on("click", function () {
        estado = $(this).attr("data-info");
        textoEstado = estado == ESTADOS.NUEVO ? "Registros" : "Matrículas";
        filtrarReporte(estado, false);
    });

    $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
        filtrarReporte(estado, true);
    });

    $filter_lead_report.on("change", function () {
        filtrarReporte(estado, true);
    });

    function filtrarReporte(estado, actionFull) {

        var formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("fecha_inicio", moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD"));
        formData.append("fecha_final", moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD"));
        formData.append("estado_id", estado);
        formData.append("action_full", actionFull);
        formData.append("filter_lead_report", $("#filter_lead_report").val());

        let $html = ""; if (actionFull) $kpis.html($html);

        actionAjax("/reporte", formData, "POST", function (data) {

            $("button.lead-color").text("Registros (" + data.Clientes + ")");

            if (estado == ESTADOS.CIERRE) {
                $(".leads-matriculados").removeClass("hidden");
                $(".enterados-col").removeClass("col-md-12").addClass("col-md-6");
            } else {
                $(".leads-matriculados").addClass("hidden");
                $(".enterados-col").removeClass("col-md-6").addClass("col-md-12");
            }
            /* console.log('esta es la data : ', data); */
            if (actionFull) {
                $.each(data.EstadosGlobal, function (i, v) {
                    if (![ESTADOS.PERDIDO, ESTADOS.OTROS, ESTADOS.REASIGNADO].includes(v[3]))
                        $html += '<div><div class="content-count" style="background-color:' + v[2] + '"> ' + v[0] + ' <br> <span> ' + v[1] + ' </span></div></div>';
                });

                $kpis.html($html)

                Highcharts.chart('vision_general_estados', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Visión General Estados'
                    },
                    subtitle: {
                        text: 'Puedes ver más detalle, dandole click a una de las siguientes columnas: '
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
                        },
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> <b> ({point.count})</b> del total<br/>'
                    },

                    series: [
                        {
                            name: "Registros",
                            colorByPoint: true,
                            data: data.Estados
                        }
                    ],
                    drilldown: {
                        series: null
                    }
                });

                /*Highcharts.chart('registroDias', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Registros por Días'
                    },
                    xAxis: {
                        categories: data.RegistroDias[0].dias
                    },
                    yAxis: {
                        title: {
                            text: 'Registros'
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: false
                        }
                    },
                    series: data.RegistroDias[0].data
                });*/
            }

            Highcharts.chart('enterados', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '¿Cómo te enteraste de Loayza?'
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
                            format: '({point.count})'
                        }
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
                        data: data.Enterados
                    }
                ],
                drilldown: {
                    series: null
                }
            });

            Highcharts.chart('usuarios', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Matrículas por Asesoras'
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
                    },
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> <b> ({point.count})</b> del total<br/>'
                },

                series: [
                    {
                        name: "Registros",
                        colorByPoint: true,
                        data: data.Usuarios
                    }
                ],
                drilldown: {
                    series: null
                }
            });

            Highcharts.chart('carreras', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: textoEstado + ' por Carreras'
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
                            format: '({point.count})'
                        }
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
                        data: data.Carreras
                    }
                ],
                drilldown: {
                    series: null
                }
            });

            Highcharts.chart('cursos', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: textoEstado + ' por Cursos'
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
                            format: '({point.count})'
                        }
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
                        data: data.Cursos
                    }
                ],
                drilldown: {
                    series: null
                }
            });

            Highcharts.chart('turnos', {

                chart: {
                    styledMode: true
                },

                tooltip: {
                    pointFormat: 'Registros: <b>{point.y:,.0f}</b>'
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
                    text: textoEstado + ' por Turnos'
                },

                series: [{
                    type: 'pie',
                    allowPointSelect: true,
                    keys: ['name', 'y', 'selected', 'sliced'],
                    data: data.Turnos,
                    showInLegend: true
                }]
            });

            Highcharts.chart('modalidades', {

                chart: {
                    styledMode: true
                },

                tooltip: {
                    pointFormat: 'Registros: <b>{point.y:,.0f}</b>'
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
                    text: textoEstado + ' por Modalidades'
                },

                series: [{
                    type: 'pie',
                    allowPointSelect: true,
                    keys: ['name', 'y', 'selected', 'sliced'],
                    data: data.Modalidades,
                    showInLegend: true
                }]
            });

            Highcharts.chart('fuentes', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Origen de los Registros'
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
                            format: '({point.count})'
                        }
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
                        data: data.Fuentes
                    }
                ],
                drilldown: {
                    series: null
                }
            });

            /*  Highcharts.chart('provincias', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: textoEstado + ' por Provincias'
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
                                format: '({point.count})'
                            }
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
                            data: data.Provincias
                        }
                    ],
                    drilldown: {
                        series: null
                    }
                }); */

            Highcharts.chart('leadsAsesoras', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '¿Cuantos Leads le llegan a cada asesor?'
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
                            format: '({point.y})'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: se le registraron <b>{point.y} leads</b><br/>'
                },
                series: [
                    {
                        name: "Registros",
                        colorByPoint: true,
                        data: data.recibeLeadAsesor
                    }
                ],
                drilldown: {
                    series: null
                }
            });

            /* CODIGO AÑADIDO PARA VERIFICAR */
            Highcharts.chart('leadsAsesorasSin', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Leads para asesores sin Reasignaciones'
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
                            format: '({point.y})'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: se le registraron <b>{point.y} leads</b><br/>'
                },
                series: [
                    {
                        name: "Registros",
                        colorByPoint: true,
                        data: data.recibeLeadAsesorSin
                    }
                ],
                drilldown: {
                    series: null
                }
            });

            var dataLeadsGeneral = data.recibeLeadsNuevosAsesor;
            const arrayAsesores = [], arrayNuevo = [], arraySeguimiento = [], arrayOportunidad = [], arrayCierre = [], arrayNoContactado = [], arrayPerdido = [], arrayOtros = [], arrayReingreso = [],
            arrayReasignado = [], arrayProximaCampaña = [], arrayRemarketing = [], arrayReintento = [], arrayInvalido = [];
            for (let i = 0; i < dataLeadsGeneral.length; i++) {
                arrayAsesores.push(dataLeadsGeneral[i].asesor);
                arrayNuevo.push(parseInt(dataLeadsGeneral[i].nuevo));
                arraySeguimiento.push(parseInt(dataLeadsGeneral[i].seguimiento));
                arrayOportunidad.push(parseInt(dataLeadsGeneral[i].oportunidad));
                arrayCierre.push(parseInt(dataLeadsGeneral[i].cierre));
                arrayNoContactado.push(parseInt(dataLeadsGeneral[i].no_contactado));
                arrayPerdido.push(parseInt(dataLeadsGeneral[i].perdido));
                arrayOtros.push(parseInt(dataLeadsGeneral[i].otros));
                arrayReingreso.push(parseInt(dataLeadsGeneral[i].reingreso));
                arrayReasignado.push(parseInt(dataLeadsGeneral[i].reasignado));
                arrayProximaCampaña.push(parseInt(dataLeadsGeneral[i].proxima_campaña));
                arrayRemarketing.push(parseInt(dataLeadsGeneral[i].remarketing));
                arrayReintento.push(parseInt(dataLeadsGeneral[i].reintento));
                arrayInvalido.push(parseInt(dataLeadsGeneral[i].invalido));
            }

            Highcharts.chart('leadsAsesorasNuevos', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Distribución y Seguimiento de Leads',
                    align: 'left',
                },
                xAxis: {
                    categories: arrayAsesores,
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad de Leads'
                    },
                    stackLabels: {
                        enabled: true
                    }
                },
                legend: {
                    align: 'left',
                    x: 70,
                    verticalAlign: 'top',
                    y: 0,
                    floating: false,
                    backgroundColor:
                        Highcharts.defaultOptions.legend.backgroundColor || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                    name: 'Leads Nuevo',
                    data: arrayNuevo
                }, {
                    name: 'Leads Seguimiento',
                    data: arraySeguimiento
                }, {
                    name: 'Leads Oportunidad',
                    data: arrayOportunidad
                }, {
                    name: 'Leads Cierre',
                    data: arrayCierre
                }, {
                    name: 'Leads No Contactados',
                    data: arrayNoContactado
                }, {
                    name: 'Leads Perdido',
                    data: arrayPerdido
                }, {
                    name: 'Leads Otros',
                    data: arrayOtros
                }, {
                    name: 'Leads Reingreso',
                    data: arrayReingreso
                }, {
                    name: 'Leads Reasignado',
                    data: arrayReasignado
                }, {
                    name: 'Leads Proxima Campaña',
                    data: arrayProximaCampaña
                }, {
                    name: 'Leads Remarketing',
                    data: arrayRemarketing
                }, {
                    name: 'Leads Reintento',
                    data: arrayReintento
                }, {
                    name: 'Leads Invalido',
                    data: arrayInvalido
                }]
            });

        });
    }


    $("#btnRealizarFiltro").on("click", function () {


        actionAjax("/reporte", formData, "POST", function (data) {

            /* Filtro de Estados */
            Highcharts.chart('pipeLine', {
                chart: {
                    type: 'funnel'
                },
                title: {
                    text: 'Leads Por Estado'
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
                    name: 'Leads registradas',
                    data: data.Estados
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



            /* Filtro Estado - Estado Detalle */
            Highcharts.chart('vendedores', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Lead Por Vendedor'
                },
                subtitle: {
                    text: 'Puedes ver más detalle, dandole click a una de las siguientes columnas: '
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
                        text: 'Leads registradas'
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
                            format: '({point.count})'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> <b> ({point.count})</b> del total<br/>'
                },

                series: [
                    {
                        name: "Leads registradas",
                        colorByPoint: true,
                        data: data.VendedorEstados
                    }
                ],
                drilldown: {
                    series: data.VendedorEstadosDetalle
                }
            });

            $("#modal-right").modal("hide");
        });
    });

    //Leads por día
    /*Highcharts.chart('container3', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Monthly Average Temperature'
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'Tokyo',
            data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'London',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });*/



})
