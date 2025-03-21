$(document).ready(function () {
    $("#notificationsFollowUp .dropdown-toggle").on("click", function () {
        $.get(urlBringNotifications, function (response) {
            if (response.data) {
                let notifications = response.data;
                let count = notifications.length;
                $("#counNotificacion").text(count);
                let $list = $(".inner-content-div").empty();
    
                notifications.forEach(v => {
                    const isRejected = v.boxTracking == 2;
                    const color = isRejected ? "text-danger" : "text-warning";
                    const icon = isRejected ? "fa-ban" : "fa-exclamation-triangle";
                    const text = isRejected ? "Matrícula rechazada. " : "Hay observaciones pendientes. ";
                    let url = urlSeeObservation.replace(':id', v.idNotification);
                    let advisorInfo = roleProfile == 1 ? `Asesora: ${v.lastNameAdvisor} ${v.nameAdvisor}, ` : "";
                    let html = `<li><a href="${url}" target="_blank" class="card-detail-notification"><i class="fa ${icon} ${color}"></i> ${v.dniClient} - ${text} ${advisorInfo}Por favor, revisa la información.</a></li>`;
                    $list.append(html);
                });
            }
        });
    });
});