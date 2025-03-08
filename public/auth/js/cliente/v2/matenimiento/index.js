$(document).on("shown.bs.modal", "#modalMantenimientoCliente", function () {
    $(".form-input").off("keypress").on("keypress", function (event) {
        if (event.which === 13) {
            event.preventDefault();
            let inputs = $(".form-input");
            let index = inputs.index(this);
            if (index < inputs.length - 1) {
                inputs.eq(index + 1).focus();
            }
        }
    });

    $("#celular").off("input").on("input", function () {
        $("#whatsapp").val($(this).val());
    });

    $("#modalidad_id").val(1).trigger("change");
    $("#fuente_id").val(10).trigger("change");
    $("#enterado_id").val(1).trigger("change");

    // Seleccionar provincia y agregar manualmente los distritos
    $("#provincia_id").val(1).trigger("change");

    let distritos = [
        { id: 15, nombre: "Lima" }
    ];

    let $distritoSelect = $("#distrito_id");
    $distritoSelect.empty(); // Limpia opciones anteriores
    distritos.forEach(d => {
        $distritoSelect.append(new Option(d.nombre, d.id));
    });

    // Agregar opciones manualmente en carrera
    let carreras = [
        { id: 70, nombre: "Otros" }
    ];

    let $carreraSelect = $("#carrera_id");
    $carreraSelect.empty(); // Limpia opciones anteriores
    carreras.forEach(c => {
        $carreraSelect.append(new Option(c.nombre, c.id));
    });

    $(".searchDni").off("input").on("input", function () {
        let validationIdentidad = $('#validationIdentidad').val();
        if(validationIdentidad == 1){
            let dni = $(this).val();
            if (dni.length === 8) {
                $.ajax({
                    url: `https://my.apidev.pro/api/dni/${dni}?api_token=3fcaa8c48f59ff6ee58afff70a360af5fdcc214f512128165cdc050da28ee770`,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(".name").val(response.data.nombres);
                            $(".last-name").val(response.data.apellido_paterno + " " + response.data.apellido_materno);
                            let fechaNacimiento = new Date(response.data.fecha_nacimiento);
                            let fechaFormateada = fechaNacimiento.toISOString().split("T")[0];
                            $(".date").val(fechaFormateada);
                            $(".direccion").val(response.data.direccion_completa);
                            $(".name, .last-name, .date").attr("readonly", true);
                        } else {
                            $(".name, .last-name, .date").attr("readonly", false);
                        }
                    },
                    error: function () {
                        alert("Error al consultar el DNI. Intente nuevamente.");
                    }
                });
            }
        }
    });
});
