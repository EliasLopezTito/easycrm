$(function() {

    const $table = $("#tableEstadoDetalle"), $modalMantenimientoEstadoDetalle = $("#modalMantenimientoEstadoDetalle");

    $dataTable = $table.DataTable({
        "stripeClasses": ['odd-row', 'even-row'],
        "lengthChange": true,
        "lengthMenu": [[50,100,200,500,-1],[50,100,200,500,"Todo"]],
        "info": false,
        "buttons": [],
        "ajax": {
            url: "/estado/detail/list_all/"+$("#estado_id").val()
        },
        "columns": [
            { title: "ID", data: "id", className: "text-center" },
            { title: "Estado", data: "name"},
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

    $table.on("click", ".btn-update", function () {
        const id = $dataTable.row($(this).parents("tr")).data().id;
         invocarModal(`/estado/partialView/${id ? id : 0}/1`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTableEstado.ajax.reload(null, false);
        });
    });

    $table.on("click", ".btn-delete", function () {
        const id = $dataTable.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append('_token', $("input[name=_token]").val());
        formData.append('id', id);
        confirmAjax(`/estado/detail/delete`, formData, "POST", null, null, function () {
            $dataTable.ajax.reload(null, false);
            $dataTableEstado.ajax.reload(null, false);
        });
    });

});
