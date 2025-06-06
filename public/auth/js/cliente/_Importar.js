$(function(){

    const $modal = $("#modalImportarCliente"), $import_perfil_id = $("#import_perfil_id"),
          $import_archivo_id = $("#import_archivo_id");

          $(".modal-footer > button").click(function(){

            const $archivo = document.getElementById("import_archivo_id");

            const formData = new FormData();
            formData.append('_token', $("input[name=_token]").val());
            formData.append("import_perfil_id", $import_perfil_id.val());
            formData.append("import_archivo_id", $archivo.files[0]);

            confirmAjax("/cliente/importExcel", formData, "POST", null, null, function(data){
                if(data.Success){
                    swal("Bien!", "Proceso realizado Correctamente", "success");
                    $modal.modal('hide');
                    $modal.on('hidden.bs.modal', function(){$(".filterSearch").click(); });
                }else{
                    $import_archivo_id.val("");
                    if(data.Errors){
                        var html = "<ul class='content-ul'>";
                            $.each(data.Errors, function (i, v){
                                html += "<li> Error en la fila "+ v.key + ": " + v.Message + "<ul>";
                                    $.each(v.error, function (i2, v2){
                                        html +=  "<li class='mtb-5'>" + v2.error + "</li>";
                                    });
                                html += "</ul></li>";
                            });
                        html += "</ul>"

                        swal({ title: "Error al importar", text: html,html: true,type: "error"});
                    }else{
                        swal("Error al importar", data.Error, "error");
                    }
                }
            }, true);
        });
});
