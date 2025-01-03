var $dataTableHistorial, $dataTable;

function consultarHistorial(){
    $('#btn_mostrar').attr('mostrar', '')
    $dataTableHistorial.ajax.reload();
}
function clickExcelHistorial() {
    $(".dt-buttons .buttons-excel").click();
}

$(function () {
    const $table = $("#tableHistorial");

    $dataTableHistorial = $table.DataTable({
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: !0,
        lengthMenu: [
            [10, 20, 50, 100, 200, -1],
            [10, 20, 50, 100, 200, "Todo"],
        ],

        "ajax": {
            url: "/reportehistorial/list_all",
            data: function(s){
                // Enviar las fechas desde y hasta al backend
                s.desde = $('#desde').val();
                s.hasta = $('#hasta').val();
            }
        },
        "columns": [
            { title: "N°", data: "contador", className: "text-center" },  
            { title: "Cliente ID", data: "cliente_id", className: "text-center" },  
            { 
                title: "DNI", 
                data: "cliente.dni", 
                className: "text-center", 
                defaultContent: "N/A" 
            },
            { 
                title: "Nombres", 
                data: "cliente.nombres", 
                className: "text-center", 
                defaultContent: "N/A" 
            },
            { 
                title: "Apellidos", 
                data: "cliente.apellidos", 
                className: "text-center", 
                defaultContent: "N/A" 
            },
            { 
                title: "Celular", 
                data: "cliente.celular", 
                className: "text-center", 
                defaultContent: "N/A" 
            },
            { 
                title: "Fecha de creación", 
                data: "cliente.created_at", 
                className: "text-center", 
                defaultContent: "N/A" 
            },
            { 
                title: "Ultimo Contacto", 
                data: "cliente.ultimo_contacto", 
                className: "text-center", 
                defaultContent: "N/A" 
            },
            { 
                title: "Historial de reasignaciones", 
                data: "historial",
                className: "text-left",
                render: function(data) {
                    let content = '';
                    data.forEach((item) => {
                        if (item.tipo === 'Registro') {
                            content += `<p>- ${item.usuario === 'Usuario eliminado' ? item.usuario : item.usuario + ` registró a ${item.vendedor} este cliente`} el ${item.fecha}</p>`;
                        } else {
                            content += `<p>- ${item.usuario} reasignó a ${item.vendedor} el ${item.fecha}</p>`;
                        }
                    });
                    return content;
                }
            },
        ]
    });
});
