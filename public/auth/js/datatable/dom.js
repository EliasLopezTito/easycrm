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
                    console.log(response.data);
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
});