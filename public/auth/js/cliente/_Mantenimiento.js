var OnSuccessRegistroCliente, OnFailureRegistroCliente;
$(function(){

    const $modal = $("#modalMantenimientoCliente"), $form = $("form#registroCliente"),
          $modalidad_id = $("#modalidad_id"), $carrera_id = $form.find("#carrera_id"), $provincia_id = $form.find("#provincia_id"),  $distrito_id = $form.find("#distrito_id");

    $provincia_id.change(function(){
        $distrito_id.html("");
        if($(this).val() != 0){
            actionAjax("/distrito/filtroDistrito/"+$(this).val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $distrito_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });

    $modalidad_id.change(function(){
        $carrera_id.html("").append(`<option value="">-- Seleccione --</option>`);
        if($(this).val() != 0){
            actionAjax("/curso/filtroCurso/"+$(this).val(), null, "GET", function(data){
                $.each(data, function (i, e) {
                    $carrera_id.append(`<option value="${e.id}">${e.name}</option>`);
                });
            });
        }
    });

    OnSuccessRegistroCliente = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroCliente = () => onFailureForm();
});
