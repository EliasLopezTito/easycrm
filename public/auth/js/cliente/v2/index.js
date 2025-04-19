$(document).ready(function() {
    let valoresProvincia = [];
    $("#provincia_id option").each(function () {
        let valor = $(this).val();
        let texto = $(this).text().trim().toLowerCase().normalize("NFD");
        valoresProvincia.push({ value: valor, text: texto });
    });
    let valoresDistritos = [];
    $("#distrito_id option").each(function () {
        let valor = $(this).val();
        let texto = $(this).text().trim().toLowerCase().normalize("NFD");
        valoresDistritos.push({ value: valor, text: texto });
    });
    $("#estado_detalle_id").change(function () {
        let valor = $(this).val();
        if (valor == "8") {
            $("#mainContainer").fadeIn();
            $(".containerModalidad").fadeIn();
            buscarDni(dni);
            $("#tipo_do option[value='3']").remove();
        } else {
            $(".containerModalidad").fadeOut();
            $("#mainContainer").fadeOut();
        }
    });
    $("#modalidad_pago").change(function () {
        let valor = $(this).val();
        let containerImg = $("#containerImg");
        if (valor == "2") {
            containerImg.fadeIn();
        } else {
            containerImg.fadeOut();
        }
    });
    $("#modalidad_pago_adcional").change(function () {
        let valor = $(this).val();
        let containerImgAdicional = $("#containerImgAdditional");
        if (valor == "2") {
            containerImgAdicional.fadeIn();
        } else {
            containerImgAdicional.fadeOut();
        }
    });
    $("#fullPayment").change(function () {
        let valor = $(this).val();
        let containerAdditional = $("#containerAdditional");
        if (valor == "0") {
            containerAdditional.fadeIn();
        } else {
            containerAdditional.fadeOut();
        }
    });
    $("#tipo_do").change(function() {
        var seleccionado = $(this).val();
        if (seleccionado == "3") {
            $("#nombres").attr("readonly", false);
            $("#apellidos").attr("readonly", false);
            $("#fecha_nacimiento").attr("readonly", false);
    }
    });
    $("body").append(`
        <div id="loadingScreen" style="
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            font-size: 24px;
            text-align: center;
            padding-top: 20%;
            z-index: 9999;
        ">
            Cargando...
        </div>
    `);

    $("#seacrhReniec").click(function() {
        let dni = $("#dni").val();
        if (dni.length >= 8) {
            $("#loadingScreen").fadeIn();

            $.ajax({
                url: `https://my.apidev.pro/api/dni/${dni}?api_token=3fcaa8c48f59ff6ee58afff70a360af5fdcc214f512128165cdc050da28ee770`,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#nombres").val(response.data.nombres);
                        $("#apellidos").val(response.data.apellido_paterno+" "+ response.data.apellido_materno);
                        $("#apellidoPaterno").val(response.data.apellido_paterno);
                        $("#apellidoMaterno").val(response.data.apellido_materno);
                        let fechaNacimiento = new Date(response.data.fecha_nacimiento);
                        let fechaFormateada = fechaNacimiento.toISOString().split("T")[0]; 
                        $("#fecha_nacimiento").val(fechaFormateada);
                        let provincia = response.data.provincia.trim().toLowerCase();
                        let coincidenciaProvincia = valoresProvincia.find(item => item.text === provincia);
                        let valorSeleccionadoProvincia = coincidenciaProvincia && coincidenciaProvincia.value 
                            ? coincidenciaProvincia.value 
                            : $("#provincia_id option:first").val();
                        $('#provincia_id').val(valorSeleccionadoProvincia).change(); 
                        let distrito = response.data.distrito.trim().toLowerCase();
                        let coincidenciaDistrito = valoresDistritos.find(item => item.text === distrito);
                        let valorSeleccionado = coincidenciaDistrito && coincidenciaDistrito.value 
                            ? coincidenciaDistrito.value 
                            : $("#distrito_id option:first").val();
                        setTimeout(function() {
                            $('#distrito_id').val(valorSeleccionado).change();
                        }, 500); 
                        $("#direccion").val(response.data.direccion_completa);
                        //deactivate
                        $("#nombres").attr("readonly", true);
                        $("#apellidos").attr("readonly", true);
                        $("#apellidoPaterno").attr("readonly", true);
                        $("#apellidoMaterno").attr("readonly", true);
                        $("#fecha_nacimiento").attr("readonly", true);
                        let textoSeleccionado = $("#provincia_id option:selected").text();
                        //Actualizar el name superior
                        $(".name-client").text(response.data.nombres + " " + response.data.apellido_paterno + " " + response.data.apellido_materno);
                        //
                        toastr.info("Guardando datos en 1 segundos...");
                        setTimeout(function () {
                            $form = $("form#registroSeguimiento")
                            var formData = new FormData();
                            formData.append("_token", $("input[name=_token]").val());
                            formData.append("id", $("#id").val());
                            formData.append("nombres", $("#nombres").val());
                            formData.append("apellidos", $("#apellidos").val());
                            formData.append("apellido_paterno", $("#apellidoPaterno").val());
                            formData.append("apellido_materno", $("#apellidoMaterno").val());
                            formData.append("fecha_nacimiento", $("#fecha_nacimiento").val());
                            formData.append("dni", $("#dni").val());
                            formData.append("celular", $('#celular').val());
                            formData.append("whatsapp", $('#whatsapp').val());
                            formData.append("email", $('#email').val());
                            formData.append("provincia_id", $('#provincia_id').val());
                            formData.append("distrito_id", $('#distrito_id').val());
                            formData.append("direccion", $('#direccion').val());
                            actionAjax("/cliente/updateDatosCliente", formData, "POST", function (data) {
                                $form.find("span[data-valmsg-for]").text("");
                                if (data.Success) {
                                    toastr.success(data.Message ? data.Message : "Guardado Correctamente", data.Title ? data.Title : "Éxito");
                                } else {
                                    toastr.error(data.Message ? data.Message : "Algo Salió mal", data.Title ? data.Title : "Error");
                                    if (data.Errors) {
                                        $.each(data.Errors, function (i, item) {
                                            if ($form != null) {
                                                if ($form.find("span[data-valmsg-for=" + i + "]").length !== 0)
                                                    $form.find("span[data-valmsg-for=" + i + "]").text(item[0]);
                                            }
                                        });
                                    }
                                }
                                $("button[type=submit]").prop("disabled", false);
                            });
                        }, 1000);
                    }else{
                        $("#nombres").val("");
                        $("#apellido_paterno").val("");
                        $("#apellido_materno").val("");
                        $("#fecha_nacimiento").val("");
                        $("#nombres").attr("readonly", false);
                        $("#apellidos").attr("readonly", false);
                        $("#fecha_nacimiento").attr("readonly", false);
                    }
                },
                error: function() {
                    alert("Error al consultar el DNI. Intente nuevamente.");
                },
                complete: function() {
                    $("#loadingScreen").fadeOut();
                }
            });
        } else {
            alert("Ingrese un DNI válido (mínimo 8 dígitos).");
        }
    });

    $("#dni").on("input", function() {
        let tipoDocumento = $("#tipo_do").val();
        let dni = $(this).val().trim();
        if (tipoDocumento === "1" && dni.length === 8 && /^\d+$/.test(dni)) { 
            buscarDni(dni);
        }
    });


    $("#dni").on("keypress", function(event) {
        if (event.which === 13) {
            let tipoDocumento = $("#tipo_do").val();
            let dni = $(this).val().trim();
            if (tipoDocumento === "1" && dni.length === 8 && /^\d+$/.test(dni)) {
                buscarDni(dni);
            }
        }
    });

    function buscarDni(dni) {
        $.ajax({
            url: `https://my.apidev.pro/api/dni/${dni}?api_token=3fcaa8c48f59ff6ee58afff70a360af5fdcc214f512128165cdc050da28ee770`,
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                console.log("Cargando...");
            },
            success: function(response) {
                if (response.success) {
                    $("#nombres").val(response.data.nombres);
                    $("#apellidoPaterno").val(response.data.apellido_paterno);
                    $("#apellidos").val(response.data.apellido_paterno+" "+response.data.apellido_materno);
                    $("#apellidoMaterno").val(response.data.apellido_materno);
                    let fechaNacimiento = new Date(response.data.fecha_nacimiento);
                    let fechaFormateada = fechaNacimiento.toISOString().split("T")[0]; 
                    $("#fecha_nacimiento").val(fechaFormateada);
                    let provincia = response.data.provincia.trim().toLowerCase();
                    let coincidenciaProvincia = valoresProvincia.find(item => item.text === provincia);
                    let valorSeleccionadoProvincia = coincidenciaProvincia && coincidenciaProvincia.value 
                        ? coincidenciaProvincia.value 
                        : $("#provincia_id option:first").val();
                    $('#provincia_id').val(valorSeleccionadoProvincia).change(); 
                    let distrito = response.data.distrito.trim().toLowerCase();
                    let coincidenciaDistrito = valoresDistritos.find(item => item.text === distrito);
                    let valorSeleccionado = coincidenciaDistrito && coincidenciaDistrito.value 
                        ? coincidenciaDistrito.value 
                        : $("#distrito_id option:first").val();
                    setTimeout(function() {
                        $('#distrito_id').val(valorSeleccionado).change();
                    }, 500); 
                    $("#direccion").val(response.data.direccion_completa);
                    //deactivate
                    $("#nombres").attr("readonly", true);
                    $("#apellidos").attr("readonly", true);
                    $("#apellidoPaterno").attr("readonly", true);
                    $("#apellidoMaterno").attr("readonly", true);
                    $("#fecha_nacimiento").attr("readonly", true);
                    //Actualizar el name superior
                    $(".name-client").text(response.data.nombres + " " + response.data.apellido_paterno + " " + response.data.apellido_materno);
                    //
                }else{
                    $("#nombres").val("");
                    $("#apellidos").val("");
                    $("#apellidoPaterno").val("");
                    $("#apellidoMaterno").val("");
                    $("#fecha_nacimiento").val("");
                    $("#nombres").attr("readonly", false);
                    $("#apellidos").attr("readonly", false);
                    $("#fecha_nacimiento").attr("readonly", false);
                }
            },
            error: function() {
                alert("Error al consultar el DNI.");
            }
        });
    }

});