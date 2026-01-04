var KTVaccinationTracking = function () {
    var detailContainer = document.getElementById('kt_vaccination_detail');
    var listContainer = document.getElementById('kt_vaccination_list');
    
    var clickViewDetail = function () {
        $(document).on('click', '.kt_btn_view_vaccine_detail', function (e) {
            e.preventDefault();
            var vaccineIndex = $(this).data('vaccine-index');
            
            if (typeof window.vaccinesData === 'undefined' || !window.vaccinesData[vaccineIndex]) {
                toastr.error('Datos de vacuna no encontrados');
                return;
            }
            
            var vaccineData = window.vaccinesData[vaccineIndex];
            
            // Renderizar el HTML del detalle usando el template
            var html = renderVaccineDetail(vaccineData);
            
            $(detailContainer).html(html);
            $(listContainer).addClass('d-none').removeClass('d-block');
            $(detailContainer).addClass('d-block').removeClass('d-none');
        });
    };
    
    var clickBackToList = function () {
        $(document).on('click', '.kt_btn_back_to_vaccines_list', function (e) {
            e.preventDefault();
            $(detailContainer).addClass('d-none').removeClass('d-block').html('');
            $(listContainer).addClass('d-block').removeClass('d-none');
        });
    };
    
    var renderVaccineDetail = function (vaccineData) {
        var vaccine = vaccineData.vaccine;
        var doses = vaccineData.doses;
        var progress = vaccineData.progress;
        
        var dosesHtml = doses.map(function(doseData) {
            var dose = doseData.dose;
            var status = doseData.status;
            var statusLabel = doseData.status_label;
            var statusColor = doseData.status_color;
            var childVaccination = doseData.child_vaccination;
            
            var badgeClass = 'badge-light-secondary';
            var iconBgClass = 'bg-secondary';
            
            switch(statusColor) {
                case 'success':
                    badgeClass = 'badge-light-success';
                    iconBgClass = 'bg-success';
                    break;
                case 'warning':
                    badgeClass = 'badge-light-warning';
                    iconBgClass = 'bg-warning';
                    break;
                case 'danger':
                    badgeClass = 'badge-light-danger';
                    iconBgClass = 'bg-danger';
                    break;
                case 'info':
                    badgeClass = 'badge-light-info';
                    iconBgClass = 'bg-info';
                    break;
                case 'primary':
                    badgeClass = 'badge-light-primary';
                    iconBgClass = 'bg-primary';
                    break;
            }
            
            var doseLabel = '';
            if (dose.dose_number === 1) {
                doseLabel = 'Primera dosis';
            } else if (dose.dose_number === 2) {
                doseLabel = 'Segunda dosis';
            } else if (dose.dose_number === 3) {
                doseLabel = 'Tercera dosis';
            } else {
                doseLabel = 'Dosis ' + dose.dose_number;
            }
            
            var vaccinationInfoHtml = '';
            if (childVaccination) {
                var notesHtml = childVaccination.notes ? '<span class="fw-semibold text-gray-600 fs-6 mb-8 d-block">' + childVaccination.notes + '</span>' : '';
                var dateApplied = new Date(childVaccination.date_applied + 'T00:00:00');
                var dateFormatted = dateApplied.toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });
                var appliedAtHtml = childVaccination.applied_at ? 
                    '<div class="border border-gray-300 border-dashed rounded min-w-150px py-2 px-4"><span class="fs-6 text-gray-700 fw-bold">' + childVaccination.applied_at + '</span><div class="fw-semibold text-gray-500">Lugar</div></div>' : '';
                
                vaccinationInfoHtml = '<div class="mb-6__">' +
                    notesHtml +
                    '<div class="d-flex flex-wrap gap-3">' +
                    '<div class="border border-gray-300 border-dashed rounded min-w-150px py-2 px-4">' +
                    '<span class="fs-6 text-gray-700 fw-bold">' + dateFormatted + '</span>' +
                    '<div class="fw-semibold text-gray-500">Fecha de aplicación</div>' +
                    '</div>' +
                    appliedAtHtml +
                    '</div>' +
                    '</div>';
            } else {
                if (dose.description) {
                    vaccinationInfoHtml = '<div class="mb-6"><span class="fw-semibold text-gray-600 fs-6 mb-8 d-block">' + dose.description + '</span></div>';
                }
            }
            
            return '<div class="card card-dashed h-md-100 mb-5">' +
                '<div class="card-body p-5 py-9">' +
                '<div class="row gx-9 h-100">' +
                '<div class="col-12">' +
                '<div class="d-flex flex-column h-100">' +
                '<div class="mb-7">' +
                '<div class="d-flex flex-stack mb-6">' +
                '<div class="flex-shrink-0 me-5">' +
                '<span class="text-gray-500 fs-7 fw-bold me-2 d-block lh-1 pb-1">' + doseLabel + '</span>' +
                '<span class="text-gray-800 fs-1 fw-bold">Dosis ' + dose.dose_number + '</span>' +
                '</div>' +
                '<span class="badge ' + badgeClass + ' flex-shrink-0 align-self-center py-3 px-4 fs-7">' + statusLabel + '</span>' +
                '</div>' +
                '<div class="d-flex align-items-center flex-wrap d-grid gap-2">' +
                '<div class="d-flex align-items-center me-5 me-xl-13">' +
                '<div class="symbol symbol-30px symbol-circle me-3">' +
                '<span class="symbol-label ' + iconBgClass + '">' +
                '<i class="ki-outline ki-calendar fs-5 text-white"></i>' +
                '</span>' +
                '</div>' +
                '<div class="m-0">' +
                '<span class="fw-semibold text-gray-500 d-block fs-8">Edad recomendada</span>' +
                '<span class="fw-bold text-gray-800 fs-7">' + dose.recommended_age + '</span>' +
                '</div>' +
                '</div>' +
                '<div class="d-flex align-items-center">' +
                '<div class="symbol symbol-30px symbol-circle me-3">' +
                '<span class="symbol-label ' + iconBgClass + '">' +
                '<i class="ki-outline ki-calendar-tick fs-5 text-white"></i>' +
                '</span>' +
                '</div>' +
                '<div class="m-0">' +
                '<span class="fw-semibold text-gray-500 d-block fs-8">Rango de edad</span>' +
                '<span class="fw-bold text-gray-800 fs-7">' + dose.age_range + '</span>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                vaccinationInfoHtml +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
        }).join('');
        
        var descriptionHtml = vaccine.description ? '<div class="mb-10"><p class="text-gray-700 fs-6">' + vaccine.description + '</p></div>' : '';
        
        return '<div class="d-flex flex-column">' +
            '<div class="mb-5">' +
            '<button type="button" class="btn btn-light kt_btn_back_to_vaccines_list">' +
            '<i class="ki-outline ki-arrow-left fs-2"></i> Volver al listado' +
            '</button>' +
            '</div>' +
            '<div class="card card-flush mb-6 mb-xl-9">' +
            '<div class="card-header mt-6">' +
            '<div class="card-title flex-column">' +
            '<h2 class="mb-1">Vacuna: ' + vaccine.name + '</h2>' +
            '<div class="fs-6 fw-semibold text-muted">Total ' + progress.total + ' ' + (progress.total === 1 ? 'dosis' : 'dosis') + '</div>' +
            '</div>' +
            '</div>' +
            '<div class="card-body p-9 pt-4">' +
            descriptionHtml +
            dosesHtml +
            '</div>' +
            '</div>' +
            '</div>';
    };
    
    return {
        init: function () {
            if (!detailContainer || !listContainer) {
                return;
            }
            clickViewDetail();
            clickBackToList();
        },
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTVaccinationTracking.init();
});

