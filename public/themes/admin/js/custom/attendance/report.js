"use strict";

var KTAttendanceReport = function () {

    var start;
    var end;

    var initRangeDate = function() {
        // Calcular semana actual: lunes a domingo
        var now = moment();
        var dayOfWeek = now.day(); // 0 = domingo, 1 = lunes, ..., 6 = sábado
        
        // Si es domingo (0), retroceder 6 días para llegar al lunes
        // Si es otro día, retroceder (dayOfWeek - 1) días para llegar al lunes
        var daysToSubtract = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
        start = moment().subtract(daysToSubtract, 'days').startOf('day');
        end = moment(start).add(6, 'days').endOf('day');

        // Si hay fechas en los inputs hidden, usarlas
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');
        
        if (startDateInput && startDateInput.value) {
            start = moment(startDateInput.value, 'DD/MM/YYYY');
        }
        if (endDateInput && endDateInput.value) {
            end = moment(endDateInput.value, 'DD/MM/YYYY');
        }

        function cb(start, end) {
            $("#kt_field_daterangepicker").val(start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY"));
            // Actualizar los campos hidden
            if (startDateInput) {
                startDateInput.value = start.format('DD/MM/YYYY');
            }
            if (endDateInput) {
                endDateInput.value = end.format('DD/MM/YYYY');
            }
        }

        $("#kt_field_daterangepicker").daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                fromLabel: "Desde",
                toLabel: "Hasta",
                customRangeLabel: "Personalizado",
                weekLabel: "S",
                daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                firstDay: 1
            },
            ranges: {
                "Hoy": [moment(), moment()],
                "Ayer": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Últimos 7 Días": [moment().subtract(6, "days"), moment()],
                "Últimos 30 Días": [moment().subtract(29, "days"), moment()],
                "Este Mes": [moment().startOf("month"), moment().endOf("month")],
                "Mes Pasado": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
                "Este Año": [moment().startOf("year"), moment().endOf("year")]
            }
        }, function(dp_start, dp_end, dp_label) {
            start = dp_start;
            end = dp_end;
            cb(start, end);
        });

        cb(start, end);
    }

    var initStickyColumns = function () {
        // Ensure sticky columns work correctly on scroll
        var tableWrapper = document.querySelector('.attendance-report-table-wrapper');
        if (tableWrapper) {
            // Update sticky column positions on scroll
            tableWrapper.addEventListener('scroll', function() {
                // This ensures the sticky columns remain in place
            });
        }
    };

    var initFormSubmit = function () {
        // Manejar el botón de descarga
        $('#kt_attendance_download_btn').on('click', function(e) {
            e.preventDefault();
            
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            
            if (!startDate || !endDate) {
                Swal.fire({
                    text: 'Por favor seleccione un rango de fechas',
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                return false;
            }
            
            var downloadBtn = $(this);
            downloadBtn.attr('data-kt-indicator', 'on');
            downloadBtn.prop('disabled', true);
            
            // Construir URL con parámetros
            var form = $('#kt_attendance_report_form');
            var url = form.data('download-url') || '/admin/reporte-asistencia/descargar';
            var params = new URLSearchParams();
            
            params.append('start_date', startDate);
            params.append('end_date', endDate);
            
            var childcareCenterId = $('#childcare_center_id').val();
            if (childcareCenterId) {
                params.append('childcare_center_id', childcareCenterId);
            }
            
            var reportType = $('#report_type').val();
            if (reportType) {
                params.append('report_type', reportType);
            }
            
            // Redirigir a la URL de descarga
            window.location.href = url + '?' + params.toString();
            
            // El bloqueo se liberará automáticamente cuando se complete la descarga
            // pero por si acaso, lo liberamos después de un tiempo
            setTimeout(function() {
                downloadBtn.removeAttr('data-kt-indicator');
                downloadBtn.prop('disabled', false);
            }, 5000);
        });
        
        // Validar formulario antes de submit
        $('#kt_attendance_report_form').on('submit', function(e) {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            
            if (!startDate || !endDate) {
                e.preventDefault();
                Swal.fire({
                    text: 'Por favor seleccione un rango de fechas',
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                return false;
            }
        });
    };

    return {
        init: function () {
            initRangeDate();
            initStickyColumns();
            initFormSubmit();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTAttendanceReport.init();
});
