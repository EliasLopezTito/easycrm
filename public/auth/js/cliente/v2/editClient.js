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
    //Sede
    let selectedSede = $("#sede").val();
    function loadLocals(sedeId, selectedLocal = null) {
        let $locals = $("#locals");
        if (sedeId) {
            $.ajax({
                url: routeSedeFiltro.replace(':id', sedeId),
                type: "GET",
                success: function(response) {
                    $locals.empty().append('<option value="">Seleccionar</option>');
                    if (response.length > 0) {
                        $.each(response, function(index, local) {
                            let selected = (local.id == selectedLocal) ? "selected" : "";
                            $locals.append('<option value="' + local.id + '" ' + selected + '>' + local.name + '</option>');
                        });
                    } else {
                        $locals.append('<option value="">No hay distritos disponibles</option>');
                    }
                },
                error: function() {
                    alert("Hubo un error al cargar los distritos.");
                }
            });
        } else {
            $locals.empty().append('<option value="">Seleccionar</option>');
        }
    }
    if (selectedSede) {
        loadLocals(selectedSede, idLocal);
    }
    $("#sede").on("change", function() {
        loadLocals($(this).val());
    });
    $(document).on('input', '.decimal', function () {
        let value = $(this).val();
        value = value.replace(/[^0-9.]/g, '');
        value = value.replace(/(\..*)\./g, '$1');
        $(this).val(value);
    });
    $(document).on('blur', '.decimal', function () {
        let value = parseFloat($(this).val());
        if (!isNaN(value)) {
            let rounded = (Math.round(value * 20) / 20).toFixed(2);
            $(this).val(rounded);
        } else {
            $(this).val('');
        }
    });
});