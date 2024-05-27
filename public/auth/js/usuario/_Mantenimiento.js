var OnSuccessRegistroUsuario, OnFailureRegistroUsuario;
$(function(){

    const $modal = $("#modalMantenimientoUsuario"), $form = $("form#registroUsuario");

    OnSuccessRegistroUsuario = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroUsuario = () => onFailureForm();
});
