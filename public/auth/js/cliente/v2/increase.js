$("#seeFromImng").click(function (event) {
    event.preventDefault();
    let clientId = $("#id").val();
    $.ajax({
        url: getUrl,
        type: "POST",
        data: { idClient: clientId },
        headers: {
            "X-CSRF-TOKEN": csrfToken
        },
        success: function (response) {
            if (response.success && response.data) {
                let data = response.data;
                $("#schoolNameUpdate").val(data.school_name ?? "");
                $("#completionDateUpdate").val(data.completion_date ?? "");
            } else {
                $("#schoolNameUpdate").val("");
                $("#completionDateUpdate").val("");
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudieron obtener los datos. Inténtalo de nuevo.",
                confirmButtonText: "Aceptar"
            });
        }
    });
    $("#formInpuImg").stop(true, true).slideToggle(300);
});
$("#increaseImgs").click(function (event) {
    event.preventDefault();
    let formData = new FormData();
    let dniFront = $("#dniFrontUpdate")[0].files[0];
    let dniRear = $("#dniRearUpdate")[0].files[0];
    let izyPay = $("#izyPayUpdate")[0].files[0];
    let vaucher = $("#vaucherUpdate")[0].files[0];
    let clientId = $("#id").val();
    let schoolName = $("#schoolNameUpdate").val();
    let completionDate = $("#completionDateUpdate").val();
    if (dniFront) formData.append("dniFront", dniFront);
    if (dniRear) formData.append("dniRear", dniRear);
    if (izyPay) formData.append("izyPay", izyPay);
    if (vaucher) formData.append("vaucher", vaucher);
    formData.append("idClient", clientId);
    formData.append("schoolNameUpdate", schoolName);
    formData.append("completionDateUpdate", completionDate);
    $.ajax({
        url: uploadUrl,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            "X-CSRF-TOKEN": csrfToken
        },
        beforeSend: function () {
            $("#increaseImgs").prop("disabled", true).text("Subiendo...");
        },
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: response.message,
                confirmButtonText: "Aceptar"
            }).then(() => {
                location.reload();
            });
        },
        error: function (xhr, status, error) {
            let errorMessage = "Error al subir las imágenes.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: "error",
                title: "Error",
                text: errorMessage,
                confirmButtonText: "Aceptar"
            });
        }
    });
});