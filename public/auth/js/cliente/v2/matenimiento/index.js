$(document).on("shown.bs.modal", "#modalMantenimientoCliente", function () {
    $("#registroCliente .form-input").off("keypress").on("keypress", function (event) {
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
        { id: 15, nombre: "Lima" },
        { id: 1, nombre: "Ancón" },
        { id: 2, nombre: "Ate Vitarte" },
        { id: 3, nombre: "Barranco" },
        { id: 4, nombre: "Breña" },
        { id: 5, nombre: "Carabayllo" },
        { id: 6, nombre: "Chaclacayo" },
        { id: 7, nombre: "Chorrillos" },
        { id: 8, nombre: "Cieneguilla" },
        { id: 9, nombre: "Comas" },
        { id: 10, nombre: "El Agustino" },
        { id: 11, nombre: "Independencia" },
        { id: 12, nombre: "Jesús María" },
        { id: 13, nombre: "La Molina" },
        { id: 14, nombre: "La Victoria" },
        { id: 16, nombre: "Lince" },
        { id: 17, nombre: "Los Olivos" },
        { id: 18, nombre: "Lurigancho" },
        { id: 19, nombre: "Lurin" },
        { id: 20, nombre: "Magdalena del Mar" },
        { id: 21, nombre: "Miraflores" },
        { id: 22, nombre: "Pachacamac" },
        { id: 23, nombre: "Pucusana" },
        { id: 24, nombre: "Pueblo Libre" },
        { id: 25, nombre: "Puente Piedra" },
        { id: 26, nombre: "Rímac" },
        { id: 27, nombre: "San Borja" },
        { id: 28, nombre: "San Isidro" },
        { id: 29, nombre: "San Juan de Lurigancho" },
        { id: 30, nombre: "San Juan de Miraflores" },
        { id: 31, nombre: "San Luis" },
        { id: 32, nombre: "San Martín de Porres" },
        { id: 33, nombre: "San Miguel" },
        { id: 34, nombre: "Santa Anita" },
        { id: 35, nombre: "Santiago de Surco" },
        { id: 36, nombre: "Surquillo" },
        { id: 37, nombre: "Villa El Salvador" },
        { id: 38, nombre: "Villa María del Triunfo" }
    ];    

    let $distritoSelect = $("#distrito_id");
    $distritoSelect.empty(); // Limpia opciones anteriores
    distritos.forEach(d => {
        $distritoSelect.append(new Option(d.nombre, d.id));
    });

    // Agregar opciones manualmente en carrera
    let carreras = [
        { id: 71, nombre: "OTROS" },
        { id: 1, nombre: "ENFERMERIA" },
        { id: 2, nombre: "FARMACIA" },
        { id: 3, nombre: "FISIOTERAPIA" },
        { id: 4, nombre: "LABORATORIO CLINICO" },
        { id: 5, nombre: "PROTESIS DENTAL" },
        { id:44, nombre: "SEMI PRESENCIAL ENFERMERIA" },
        { id:45, nombre: "SEMI PRESENCIAL FARMACIA" },
        { id:46, nombre: "SEMI PRESENCIAL FISIOTERAPIA" },
    ];

    let $carreraSelect = $("#carrera_id");
    $carreraSelect.empty(); // Limpia opciones anteriores
    carreras.forEach(c => {
        $carreraSelect.append(new Option(c.nombre, c.id));
    });

    $("#validationIdentidad").change(function() {
        $(".name, .paternal-surname, .maternal-surname, .date").prop("readonly", false).val("");
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
                            $(".paternal-surname").val(response.data.apellido_paterno);
                            $(".maternal-surname").val(response.data.apellido_materno);
                            let fechaNacimiento = new Date(response.data.fecha_nacimiento);
                            let fechaFormateada = fechaNacimiento.toISOString().split("T")[0];
                            $(".date").val(fechaFormateada);
                            $(".direccion").val(response.data.direccion_completa);
                            $(".name, .paternal-surname, .maternal-surname, .date").attr("readonly", true);
                        } else {
                            $(".name, .paternal-surname, .maternal-surname, .date").attr("readonly", false);
                            $(".name, .paternal-surname, .maternal-surname, .date").val("");
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
