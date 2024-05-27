$(function(){

    const $table = $("#tableUsuario"), $estado_usuario = $("#estado_usuario");

    const $dataTable = $table.DataTable({
        "stripeClasses": ['odd-row', 'even-row'],
        "lengthChange": true,
        "lengthMenu": [[10,25,100,200,500,-1],[10,25,100,200,500,"Todo"]],
        "info": false,
        "buttons": [],
        "ajax": {
            url: "/usuario/list_all",
            data: function(s){
                if($estado_usuario.val() != ""){ s.estado_usuario = $estado_usuario.val(); }
            }
        },
        "columns": [
            { title: "ID", data: "id", className: "text-center" },
            { title: "Nombres", data: "name", className: "text-center" },
            { title: "Apellidos", data: "last_name", className: "text-center" },
            { title: "Email", data: "email", className: "text-center" },
            { title: "Perfil", data: "profiles.name", className: "text-center" },
            { title: "Estado", data: "activo", className: "text-center", render: function(data){
                if(data) return "Activo";
                return "Inactivo";
            } },
            { title: "Recibe Lead", data: "recibe_lead", className: "text-center", render: function(data){
                    if(data) return "Si";
                    return "No";
            } },
            { title: "Turno", data: "turnos.name", className: "text-center" },
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
        ],
        "rowCallback": function (row, data, index) {
            if(!data.activo){
                $("td", row).css({
                    "background-color": "#f09e64",
                    "color": "#fff"
                });
            }
        }
    });

    $estado_usuario.on("change", function(){
        $dataTable.ajax.reload();
    })

    $table.on("click", ".btn-update", function () {
        const id = $dataTable.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    $table.on("click", ".btn-delete", function () {
        const id = $dataTable.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append('_token', $("input[name=_token]").val());
        formData.append('id', id);
        confirmAjax(`/usuario/delete`, formData, "POST", null, null, function () {
            $dataTable.ajax.reload(null, false);
        });
    });

    $("#modalRegistrarUsuario").on("click", function () {
        invocarModalView();
    });

    function invocarModalView(id) {
        invocarModal(`/usuario/partialView/${id ? id : 0}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTable.ajax.reload(null, false);
        });
    }
});
