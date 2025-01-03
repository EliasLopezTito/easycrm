var OnSuccessMatriculadoCliente, OnFailureMatriculadoCliente;
$(function(){

    const $modal = $("#modalMatriculadoCliente"), $form = $("form#matriculadoCliente"), $id = $("#id"),
    $turno_id = $("#turno_id"), $horario_id = $("#horario_id"), $carrera_id = $("#carrera_id"), $modalidad_id = $("#modalidad_id");

    $modalidad_id.on("change", function(){
        $carrera_id.html("").append(`<option value="">${ "-- " + (MODALIDADES.CARRERA == $(this).val() ? "Carrera" : "Cuso") + " --"}</option>`);
        if($(this).val() != 0 && $(this).val() != ""){
            actionAjax("/horario/filtroCarrera/"+$(this).val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $carrera_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });

    $(document).on("change", "#turno_id, #carrera_id", function(){
        $horario_id.html("").append(`<option value="">-- Horario --</option>`);
        if($(this).val() != 0 && $turno_id.val() != ""){
            actionAjax("/horario/filtroHorario/"+$turno_id.val()+"/"+$carrera_id.val()+"/1", null, "GET", function(data){
                $.each(data, function (i, e) {
                    $horario_id.append(`<option value="${e.id}">${e.horario}</option>`);
                });
            });
        }
    });

    OnSuccessMatriculadoCliente = (data) => onSuccessForm(data, $form, $modal);
    OnFailureMatriculadoCliente = () => onFailureForm();
});
