var OnSuccessRegistroSede, OnFailureRegistroSede;
$(function(){

    const $modal = $("#modalMantenimientoSede"), $form = $("form#registroSede");

    OnSuccessRegistroSede = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroSede = () => onFailureForm();
});
