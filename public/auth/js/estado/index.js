var $dataTableEstado, $dataTable;
$(function(){

    const $table = $("#tableEstado");

    $dataTableEstado = $table.DataTable({
        "stripeClasses": ['odd-row', 'even-row'],
        "lengthChange": true,
        "lengthMenu": [[50,100,200,500,-1],[50,100,200,500,"Todo"]],
        "info": false,
        "buttons": [],
        "ajax": {
            url: "/estado/list_all"
        },
        "columns": [
            { title: "ID", data: "id", className: "text-center" },
            { title: "Estado Principal", data: "name"},
            { title: "Estado Detalle", data: "estado_detalles", render: function(data){
                if(data != null && data.length > 0){ return data.map(function (e) {return e.name; }); }
                return "";
            } },
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn btn-primary btn-xs btn-detail' data-toggle='tooltip' title='Establecer Detalle'><i class='fa fa-eye'></i></button>",
                "orderable": false,
                "searchable": false,
                "width": "26px"
            },
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn btn-secondary btn-xs btn-update' data-toggle='tooltip' title='Actualizar'><i class='fa fa-pencil'></i></button>",
                "orderable": false,
                "searchable": false,
                "width": "26px"
            },
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn btn-danger btn-xs btn-delete' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash'></i></button>",
                "orderable": false,
                "searchable": false,
                "width": "26px"
            }
        ]
    });

    $table.on("click", ".btn-detail", function () {
        const id = $dataTableEstado.row($(this).parents("tr")).data().id;
        invocarModal(`/estado/detail/partialView/${id ? id : 0}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTableEstado.ajax.reload(null, false);
        });
    });


    $table.on("click", ".btn-update", function () {
        const id = $dataTableEstado.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    $table.on("click", ".btn-delete", function () {
        const id = $dataTableEstado.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append('_token', $("input[name=_token]").val());
        formData.append('id', id);
        confirmAjax(`/estado/delete`, formData, "POST", null, null, function () {
            $dataTableEstado.ajax.reload(null, false);
        });
    });

    $("#modalRegistrarUsuario").on("click", function () {
        invocarModalView();
    });

    function invocarModalView(id) {
        invocarModal(`/estado/partialView/${id ? id : 0}/0`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTableEstado.ajax.reload(null, false);
        });
    }
});
