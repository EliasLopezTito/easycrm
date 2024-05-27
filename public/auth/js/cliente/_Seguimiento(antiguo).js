var OnSuccessRegistroSeguimiento, OnFailureRegistroSeguimiento;
$(function(){

    const $form = $("form#registroSeguimiento"), $contentHistory = $("#content-history"), $userAction = $(".user-action"), $carrera_id = $(".user.content-card #carrera_id"), $turno_id = $("#turno_id"),
    $provincia_id = $("#provincia_id"), $distrito_id = $("#distrito_id"),  $direccion = $("#direccion"), $horario_id = $("#horario_id"), $userInfo = $(".user-info"), $sede_id = $("#sede_id"), $local_id = $("#local_id"),$sede_adicional_id = $("#sede_adicional_id"),$local_adicional_id = $("#local_adicional_id"),
    $estado_id = $("#estado_id"),  $estado_detalle_id = $("#estado_detalle_id"), $information = $(".information");

    const $nombres = $("#nombres"), $apellidos = $("#apellidos"), $id = $("#id"), $dni = $("#dni"), $fecha_nacimiento = $("#fecha_nacimiento"),
    $celular = $("#celular"), $whatsapp = $("#whatsapp"), $email = $("#email");

    $("input.decimal").inputmask("decimal",
    { min: 0, rightAlign: true, groupSeparator: ".", removeMaskOnSubmit: true, digits: 2, autoGroup: true });

    $provincia_id.change(function(){
        $distrito_id.html("");
        if($(this).val() != 0){
            actionAjax("/distrito/filtroDistrito/"+$(this).val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $distrito_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
                updateDatosContacto();
            });
        }
    });

    

    $estado_id.change(function(){
        $estado_detalle_id.html("").append(`<option value="">-- Seleccione --</option>`);
        if($(this).val() != 0){
            actionAjax("/estado/filtroEstado/"+$(this).val(), null, "GET", function(data){

                if([ESTADOS.PERDIDO, ESTADOS.OTROS].includes(parseInt($estado_id.val()))){
                    $("#proximaAccion").addClass("hidden").find("input, select").val("").prop("required", false);
                    if(!$("#datosAdicionales").hasClass("hidden")) $("#datosAdicionales").addClass("hidden").find("input, select").val("").prop("required", false);
                }else{
                    if(![ESTADOS.CIERRE, ESTADOS.REINGRESO].includes(parseInt($estado_id.val()))){
                        $("#proximaAccion").removeClass("hidden").find("input, select").val("").prop("required", true);
                        if(!$("#datosAdicionales").hasClass("hidden")) $("#datosAdicionales").addClass("hidden").find("input, select").val("").prop("required", false);
                    }
                }

                $.each(data, function (i, e) {
                    $estado_detalle_id.append(`<option value="${e.id}">${e.name}</option>`);
                });

            });
        }
    });

    $(document).on("change", "#turno_id, .user.content-card #carrera_id", function(){
        $horario_id.html("").append(`<option value="">-- Horario --</option>`);
        if($(this).val() != 0 && $turno_id.val() != ""){
            actionAjax("/horario/filtroHorario/"+$turno_id.val()+"/"+$carrera_id.val()+"/1", null, "GET", function(data){
                $.each(data, function (i, e) {
                    $horario_id.append(`<option value="${e.id}">${e.horario}</option>`);
                });
            });
        }
    });

    $(document).on("change", "#sede_id", function(){
        $local_id.html("").append(`<option value="">-- Local --</option>`);
        if($(this).val() != 0 && $sede_id.val() != ""){
            actionAjax("/local/filtroLocal/"+$sede_id.val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $local_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });

    

    $(document).on("change", "#presencial_turno_id, .user.content-card #carrera_id", function(){
        $("#presencial_horario_id").html("").append(`<option value="">-- Horario --</option>`);
        if($(this).val() != 0 && $("#presencial_turno_id").val() != ""){
            actionAjax("/horario/filtroHorario/"+$("#presencial_turno_id").val()+"/"+$carrera_id.val()+"/2", null, "GET", function(data){
                $.each(data, function (i, e) {
                    $("#presencial_horario_id").append(`<option value="${e.id}">${e.horario}</option>`);
                });
            });
        }
    });

    $(document).on("change", "#estado_id", function(){
        if(![ESTADOS.PERDIDO, ESTADOS.OTROS].includes(parseInt($estado_id.val()))){
            if([ESTADOS.CIERRE, ESTADOS.REINGRESO].includes(parseInt($estado_id.val()))){
                $("#datosAdicionales").removeClass("hidden").find("input, select").val("").prop("required", true);
                $("#proximaAccion").addClass("hidden").find("input, select").val("").prop("required", false);
                $(".direccion_matricula").removeClass("hidden").find("input#direccion").val("").prop("required", true);
            }else{
                $("#proximaAccion").removeClass("hidden").find("input, select").val("").prop("required", true);
                $("#datosAdicionales").addClass("hidden").find("input, select").val("").prop("required", false);
                $(".direccion_matricula").addClass("hidden").find("input#direccion").val("").prop("required", false);
            }
        }
    });

    $(document).on("change", "#modalidad_adicional_id", function(){
        $("#carrera_adicional_id").html("").append(`<option value="">-- Carrera o Curso --</option>`);
        if($(this).val() != 0){
            actionAjax("/curso/filtroCurso/"+$(this).val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $("#carrera_adicional_id").append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });

    $(document).on("change", "#sede_adicional_id", function(){
        $local_adicional_id.html("").append(`<option value="">-- Local

         --</option>`);
        if($(this).val() != 0 && $sede_adicional_id.val() != ""){
            actionAjax("/local/filtroLocal/"+$sede_adicional_id.val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $local_adicional_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });

    $(document).on("change", "#turno_adicional_id, #carrera_adicional_id", function(){
        $("#horario_adicional_id").html("").append(`<option value="">-- Horario --</option>`);
        if($(this).val() != 0 && $("#turno_adicional_id").val() != ""){
            actionAjax("/horario/filtroHorario/"+$("#turno_adicional_id").val()+"/"+$("#carrera_adicional_id").val()+"/1", null, "GET", function(data){
                $.each(data, function (i, e) {
                    $("#horario_adicional_id").append(`<option value="${e.id}">${e.horario}</option>`);
                });
            });
        }
    });



    $(document).on("change", "#carrera_adicional_id", function(){
        const $this = $(this);
        $("#presencial_adicional_sede_id").html("").append(`<option value="">-- Sede --</option>`);
        $("#presencial_adicional_turno_id").html("").append(`<option value="">-- Turno --</option>`);
        $("#presencial_adicional_horario_id").html("").append(`<option value="">-- Horario --</option>`);
        if($this.val() != 0 && $this.val() != ""){
            actionAjax("/cliente/search_course/"+parseInt($this.val()), null, "GET", function(data){
                if(data.data != null && data.data.semipresencial){
                    $("#datosSemiPresencialAdicional").removeClass("hidden").find("select").prop("required", true);
                    $.each(data.PresencialSedes, function (i, e) {
                        $("#presencial_adicional_sede_id").append(`<option value="${e.id}">${e.name}</option>`);
                    });
                    $.each(data.turnos, function (i, e) {
                        $("#presencial_adicional_turno_id").append(`<option value="${e.id}">${e.name}</option>`);
                    });
                }else{
                    $("#datosSemiPresencialAdicional").addClass("hidden").find("select").prop("required", false);
                }
            });
        }else{
            $("#datosSemiPresencialAdicional").addClass("hidden");
        }
    });


    $(document).on("change", "#presencial_adicional_turno_id", function(){
        $("#presencial_adicional_horario_id").html("").append(`<option value="">-- Horario --</option>`);
        if($(this).val() != 0 && $("#presencial_turno_id").val() != ""){
            actionAjax("/horario/filtroHorario/"+$("#presencial_adicional_turno_id").val()+"/"+$("#carrera_adicional_id").val()+"/2", null, "GET", function(data){
                $.each(data, function (i, e) {
                    $("#presencial_adicional_horario_id").append(`<option value="${e.id}">${e.horario}</option>`);
                });
            });
        }
    });

    /*$sede_id.change(function(){
        $local_id.html("").append(`<option value="">-- Seleccione --</option>`);
        if($(this).val() != 0){
            actionAjax("/local/filtroLocal/"+$(this).val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $local_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });*/

    function updateDatosContacto(){

        $("button[type=submit]").prop("disabled",true);

        var formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", $id.val());
        formData.append("nombres", $nombres.val());
        formData.append("apellidos", $apellidos.val());
        formData.append("fecha_nacimiento", $fecha_nacimiento.val());
        formData.append("dni", $dni.val());
        formData.append("celular", $celular.val());
        formData.append("whatsapp", $whatsapp.val());
        formData.append("email", $email.val());
        formData.append("carrera_id", $carrera_id.val());
        formData.append("provincia_id", $provincia_id.val());
        formData.append("distrito_id", $distrito_id.val());
        formData.append("direccion", $direccion.val());
        formData.append("direccion", $direccion.val());
        formData.append("estado_id", $estado_id.val());
        formData.append("estado_detalle_id", $estado_detalle_id.val());

        $("#carrera_hidden_id").val($carrera_id.val());

        actionAjax("/cliente/search_course/"+parseInt($carrera_id.val()), null, "GET", function(data){
            if(data.data != null && data.data.semipresencial && !$("#datosAdicionales").hasClass("hidden")){
                $("#datosSemiPresencial").removeClass("hidden").find("select").prop("required", true);
            }else{
                $("#datosSemiPresencial").addClass("hidden").find("select").prop("required", false);
            }
        });

        actionAjax("/cliente/updateDatosContacto",formData, "POST", function(data){
            $form.find("span[data-valmsg-for]").text("");
            if(data.Success){
                $("#whatsapp_link").attr("href", "javascript:sendMessage("+$whatsapp.val()+")");
                $("#gmail").attr("href", "mailto:"+$email.val());
                toastr.success(data.Message ? data.Message : "Guardado Correctamente", data.Title ? data.Title : "Éxito");
            }else{
                toastr.error(data.Message ? data.Message : "Algo Salio mal", data.Title ? data.Title : "Error");
                if (data.Errors) {
                    $.each(data.Errors,
                        function (i, item) {
                            if($form != null) {
                                if ($form.find("span[data-valmsg-for=" + i + "]").length !== 0)
                                    $form.find("span[data-valmsg-for=" + i + "]").text(item[0]);
                            }
                    });
                }
            }

            $("button[type=submit]").prop("disabled", false);
        });
    }

    $(document).on("change", ".user.content-card #carrera_id, .information #distrito_id", function(){
        updateDatosContacto();
    });

    $information.find("input:enabled").change(function(){
        updateDatosContacto();
    });

    refreshFilterSeguimiento();

    function refreshFilterSeguimiento() {

        var formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", $id.val());

        localStorage.setItem("cliente_id", $id.val());

        $contentHistory.html(""); $("#content-history-adicional").html("");

        actionAjax("/cliente/list_filter_seguimiento", formData,"POST", function(data){
            if(data.data != null && data.data.length > 0){
                var html = '';
                var n = data.data.length == 1 ? 1 : data.data.length;
                $.each(data.data, function(i, v){
                    let estado_id = v.estado_id;
                    let date = moment.utc(v.created_at).format('LL').split('de');

                    /*----SE AGREGO LA CONDICIONAL SI EXISTE LOCAL_ID PARA LAS MATRICULAS PASADAS-----*/
                    if(v.clientes.local_id != null){
                        html += '<div class="item">'+
                        '<div class="number-image">'+
                            '<div><span>'+(n--)+'</span></div>'+
                                '</div><div class="info-details">'+
                                    '<div>'+ ((v.estado_detalle_id == ESTADOS_DETALLE.MATRICULADO && usuarioLoggin.profile_id == PERFILES.ADMINISTRADOR) ? '<button type="button" class="btn btn-primary btn-xs btn-edit pull-right"><i class="fa fa-pencil"></i></button>' : '' ) +
                                        '<p class="info-details-title">'+ v.clientes.users.name + ' realizó ' +v.acciones.name+ ', el ' + date[0] + ' de ' + date[1] + ' a las ' + moment.utc(v.created_at).format('h:mm a') + ' | Estado: ' + v.estados.name + ' | ' +
                                        ((v.estado_detalle_id == ESTADOS_DETALLE.MATRICULADO) ?  '<br> Matriculado en ' + v.clientes.modalidades.name  +  ' de ' + v.clientes.carreras.name+ ', en la sede: ' + v.clientes.sedes.name + ', en el local: ' + v.clientes.locales.name + ', en el turno: ' + v.clientes.turnos.name + ' y en el horario de: ' + v.clientes.horarios.horario + ' | N° Operación: ' + v.clientes.nro_operacion
                                        + ' | Monto: S/ ' + v.clientes.monto + ' | Código alumno: ' + v.clientes.codigo_alumno  + '.' : (v.estado_detalle_id == ESTADOS_DETALLE.REINGRESO) ? ' Semestre de Reingreso: ' + v.clientes.semestre_inicio.name + ' | Ciclo de Reingreso: ' + v.clientes.ciclo_inicio.name + ' | Cursos jalados: ' + (v.clientes.cursos_jalados != null && v.clientes.cursos_jalados == 1 ? 'Si' : 'No') : '' ) + '</p>'+
                                        '<p>' + (v.comentario != null ? v.comentario : "-") +'</p>'+
                                    '</div>'+
                            '</div>'+
                        '</div>';
                    }else{
                        html += '<div class="item">'+
                        '<div class="number-image">'+
                            '<div><span>'+(n--)+'</span></div>'+
                                '</div><div class="info-details">'+
                                    '<div>'+ ((v.estado_detalle_id == ESTADOS_DETALLE.MATRICULADO && usuarioLoggin.profile_id == PERFILES.ADMINISTRADOR) ? '<button type="button" class="btn btn-primary btn-xs btn-edit pull-right"><i class="fa fa-pencil"></i></button>' : '' ) +
                                        '<p class="info-details-title">'+ v.clientes.users.name + ' realizó ' +v.acciones.name+ ', el ' + date[0] + ' de ' + date[1] + ' a las ' + moment.utc(v.created_at).format('h:mm a') + ' | Estado: ' + v.estados.name + ' | ' +
                                        ((v.estado_detalle_id == ESTADOS_DETALLE.MATRICULADO) ?  '<br> Matriculado en ' + v.clientes.modalidades.name  +  ' de ' + v.clientes.carreras.name+ ', en la sede: ' + v.clientes.sedes.name  + ', en el turno: ' + v.clientes.turnos.name + ' y en el horario de: ' + v.clientes.horarios.horario + ' | N° Operación: ' + v.clientes.nro_operacion
                                        + ' | Monto: S/ ' + v.clientes.monto + ' | Código alumno: ' + v.clientes.codigo_alumno  + '.' : (v.estado_detalle_id == ESTADOS_DETALLE.REINGRESO) ? ' Semestre de Reingreso: ' + v.clientes.semestre_inicio.name + ' | Ciclo de Reingreso: ' + v.clientes.ciclo_inicio.name + ' | Cursos jalados: ' + (v.clientes.cursos_jalados != null && v.clientes.cursos_jalados == 1 ? 'Si' : 'No') : '' ) + '</p>'+
                                        '<p>' + (v.comentario != null ? v.comentario : "-") +'</p>'+
                                    '</div>'+
                            '</div>'+
                        '</div>';
                    }
                    /*-----------------------------------------------------------------------------*/
                    
                    $userInfo.find("div.progress-line").each(function(i, v){
                        switch(i+=1){
                            case 1: if([ESTADOS.NUEVO].includes(estado_id)) $(v).addClass("active"); break;
                            case 2: if([ESTADOS.SEGUIMIENTO, ESTADOS.OPORTUNUDAD, ESTADOS.CIERRE, ESTADOS.REINGRESO].includes(estado_id)) $(v).addClass("active"); break;
                            case 3: if([ESTADOS.OPORTUNUDAD, ESTADOS.CIERRE, ESTADOS.REINGRESO].includes(estado_id)) $(v).addClass("active"); break;
                            case 4: if([ESTADOS.CIERRE, ESTADOS.REINGRESO].includes(estado_id)) $(v).addClass("active"); break;
                        }
                    });
                });
                $contentHistory.append(html);
                $userAction.find("input, select, textarea").val("");
                $("#proximaAccion").removeClass("hidden").find("input, select").val("").prop("required", true);
                $("#datosAdicionales").addClass("hidden").find("input, select").val("").prop("required", false);
                if(data.data != null && (data.data[0].clientes.estado_id == ESTADOS.CIERRE ||
                    data.data[0].clientes.estado_id == ESTADOS.REINGRESO ||
                    ([PERFILES.VENDEDOR,PERFILES.CALL].includes(usuarioLoggin.profile_id) && data.data[0].clientes.estado_id == ESTADOS.OTROS)))
                    $(".content-actions-client").html("");
                    $direccion.val(data.data[0].clientes.direccion);
            }else
                $contentHistory.html("<p>No existe historial registrada actualmente.</p>");
        });

        actionAjax("/cliente/list_filter_seguimiento_adicional", formData,"POST", function(data){
            if(data.data != null && data.data.length > 0){
                var html = '';
                var n = data.data.length == 1 ? 1 : data.data.length;
                $.each(data.data, function(i, v){

                    /*----SE AGREGO LA CONDICIONAL SI EXISTE LOCAL_ID PARA LAS MATRICULAS PASADAS-----*/
                    if(v.locales != null){
                        html += '<div class="item">'+
                            '<div class="number-image">'+
                            '<div><span>'+(n--)+'</span></div>'+
                            '</div><div class="info-details"><div><p class="info-details-title">Nueva matricula: ' + v.modalidades.name  +  ' de ' + v.carreras.name+ ', en la sede: ' + v.sedes.name + ', en el local: ' + v.locales.name +', en el turno: '+ v.turnos.name + ' y en el horario de: ' + v.horarios.horario + ' | N° Operación: ' + v.nro_operacion_adicional+ ' | Monto: S/ ' + v.monto_adicional + '. </p></div>'+
                            '</div>'+
                            '</div>'+
                            '</div>';
                    }else{
                        html += '<div class="item">'+
                            '<div class="number-image">'+
                            '<div><span>'+(n--)+'</span></div>'+
                            '</div><div class="info-details"><div><p class="info-details-title">Nueva matricula: ' + v.modalidades.name  +  ' de ' + v.carreras.name+ ', en la sede: ' + v.sedes.name + ', en el turno: '+ v.turnos.name + ' y en el horario de: ' + v.horarios.horario + ' | N° Operación: ' + v.nro_operacion_adicional+ ' | Monto: S/ ' + v.monto_adicional + '. </p></div>'+
                            '</div>'+
                            '</div>'+
                            '</div>';
                    }
                });
                $("#content-history-adicional").append(html);
            }else
                $("#content-history-adicional").html("<p>Aún no tienes nuevas oportunidades.</p>");
        });


    }

    $(document).on("click", ".btn-edit", function(){
        invocarModal("/cliente/partialViewMatriculado/"+$id.val(), function($modal){
            if ($modal.attr("data-reload") === "true") refreshFilterSeguimiento();
        });
    });

    $(".cursosAdicionales button[type=button]").on("click", function(){
        $("#datosAdicionales").hasClass("hidden") ? $("#datosAdicionales").removeClass("hidden").find("input, select").val("").prop("required", true) : $("#datosAdicionales").addClass("hidden").find("input, select").val("").prop("required", false);
    });

    OnSuccessRegistroSeguimiento = (data) => onSuccessForm(data, $form, null, function(data){
        if(data.Success){ refreshFilterSeguimiento(); }
        else toastr.error("Algo Salio mal", "No se pudo actualizar el historial", "Error");
    });

    OnFailureRegistroSeguimiento = () => onFailureForm();
});

function sendMessage(phone){
    window.open("https://api.whatsapp.com/send?phone=+51"+phone+"&text=Hola,%20somos%20del%20equipo%20Loayza%20y%20queremos%20ofrecerte...","_blank");
}
