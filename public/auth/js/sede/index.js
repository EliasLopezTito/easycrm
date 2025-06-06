var $dataTableSede, $dataTable;
$(function(){

    const $table = $("#tableSede");

    $dataTableSede = $table.DataTable({
        "stripeClasses": ['odd-row', 'even-row'],
        "lengthChange": true,
        "lengthMenu": [[50,100,200,500,-1],[50,100,200,500,"Todo"]],
        "info": false,
        "buttons": [],
        "ajax": {
            url: "/sede/list_all"
        },
        "columns": [
            { title: "ID", data: "id", className: "text-center" },
            { title: "Sede ", data: "name"},
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
        const id = $dataTableSede.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    $table.on("click", ".btn-delete", function () {
        const id = $dataTableSede.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append('_token', $("input[name=_token]").val());
        formData.append('id', id);
        confirmAjax(`/sede/delete`, formData, "POST", null, null, function () {
            $dataTableSede.ajax.reload(null, false);
        });
    });

    $("#modalRegistrarSede").on("click", function () {
        invocarModalView();
    });

    function invocarModalView(id) {
        invocarModal(`/sede/partialView/${id ? id : 0}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTableSede.ajax.reload(null, false);
        });
    }
});
