$(document).ready(function() {
    let selectedProvince = $("#provinces").val();
    function loadDistricts(provinceId, selectedDistrict = null) {
        let $districts = $("#districts");
        if (provinceId) {
            $.ajax({
                url: routeFiltro.replace(':id', provinceId),
                type: "GET",
                success: function(response) {
                    $districts.empty().append('<option value="">Seleccionar</option>');
                    if (response.length > 0) {
                        $.each(response, function(index, district) {
                            let selected = (district.id == selectedDistrict) ? "selected" : "";
                            $districts.append('<option value="' + district.id + '" ' + selected + '>' + district.name + '</option>');
                        });
                    } else {
                        $districts.append('<option value="">No hay distritos disponibles</option>');
                    }
                },
                error: function() {
                    alert("Hubo un error al cargar los distritos.");
                }
            });
        } else {
            $districts.empty().append('<option value="">Seleccionar</option>');
        }
    }
    if (selectedProvince) {
        loadDistricts(selectedProvince, idDistrito);
    }
    $("#provinces").on("change", function() {
        loadDistricts($(this).val());
    });
});