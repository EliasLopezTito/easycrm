$(document).ready(function() {
    var table = $('#clienteData').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar a Excel',
                className: 'btn btn-success'
            }
        ],
        language: {
            url: routeSpanish
        },
    });
    var table = $('#clienteDataSearch').DataTable({
        language: {
            url: routeSpanish
        },
    });
    $('#btnConsult').on('click', function() {
        var fechaInicio = $('#fechaInicio').val();
        var fechaFinal = $('#fechaFinal').val();
        if (fechaInicio && fechaFinal) {
            $.ajax({
                url: routeAjax,
                method: 'POST',
                data: {
                    fechaInicio: fechaInicio,
                    fechaFinal: fechaFinal,
                    _token: token
                },
                success: function(response) {
                    table.clear().rows.add(response.data).draw();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        } else {
            alert('Por favor, selecciona ambas fechas.');
        }
    });
    $("#btnConsultClient").on("click", function () {
        let numberSearch = $("#numberSearch").val();
        if (numberSearch) {
            $.ajax({
                url: routeAjax,
                method: 'POST',
                data: {
                    numberSearch: numberSearch,
                    _token: token
                },
                success: function (response) {
                    console.log("Datos recibidos:", response.data);
                    actualizarTabla(response.data);
                },
                error: function (xhr, status, error) {
                    console.error("Error en la petición AJAX:", error);
                }
            });
        } else {
            alert("Por favor, ingrese un número de DNI o celular.");
        }
    });

    function actualizarTabla(clientes) {
        let tableBody = $("#clienteDataSearch tbody");
        tableBody.empty();
        if (clientes.length > 0) {
            clientes.forEach(cliente => {
                let row = `
                    <tr>
                        <td>${cliente.nombre_carrera || "No disponible"}</td>
                        <td>${cliente.nombres || "No disponible"}</td>
                        <td>${cliente.apellidos || "No disponible"}</td>
                        <td>${cliente.dni || "No disponible"}</td>
                        <td>${cliente.celular || "No disponible"}</td>
                        <td>${cliente.whatsapp ? cliente.whatsapp : "No disponible"}</td>
                        <td>${cliente.email ? cliente.email : "No disponible"}</td>
                        <td>${cliente.fecha_nacimiento ? cliente.fecha_nacimiento : "No disponible"}</td>
                        <td>
                            <button class="btn btn-primary btn-sm seleccionar-cliente" data-id="${cliente.id}">
                                Seleccionar
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });
            $(".seleccionar-cliente").on("click", function () {
                let clientId = $(this).data("id");
                let url = routeEditClient.replace(":id", clientId);
                window.location.href = url;
            });
        } else {
            tableBody.append('<tr><td colspan="8" class="text-center">No se encontraron resultados</td></tr>');
        }
    }    
});