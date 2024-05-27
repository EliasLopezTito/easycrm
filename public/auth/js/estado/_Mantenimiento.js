var OnSuccessRegistroEstado, OnFailureRegistroEstado;
$(function(){

    const $modal = $("#modalMantenimientoEstado"), $form = $("form#registroEstado");

    $("input[name=tipo]").change(function(){
        if(parseInt($(this).val()) != 0){
            $(".filter").removeClass("hidden");
            $("#estado_id").prop("required", true);
         }else{
            $(".filter").addClass("hidden");
            $("#estado_id").prop("required", false);
         }
    });

    $modal.on("hide.bs.modal", function(){
       if(typeof $dataTable != 'undefined') $dataTable.ajax.reload(null, false);
    });

    OnSuccessRegistroEstado = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroEstado = () => onFailureForm();
});
