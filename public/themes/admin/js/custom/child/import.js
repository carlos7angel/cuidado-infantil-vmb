"use strict";

var KTImportChildren = function () {
    var previewData = [];

    var handlePreview = function () {
        var form = document.getElementById('kt_import_form');
        var previewBtn = document.getElementById('kt_preview_btn');
        var previewSection = document.getElementById('kt_preview_section');
        var tableBody = document.getElementById('kt_preview_table_body');
        var resultsSection = document.getElementById('kt_results_section');
        
        // Handle Childcare Center Change
        $('#childcare_center_id').on('select2:select', function (e) {
            var centerId = e.params.data.id;
            var roomSelect = $('#room_id');
            
            // Clear current options
            roomSelect.empty().trigger("change");
            
            if (centerId) {
                // Load rooms
                $.ajax({
                    url: '/admin/infantes/importar/salas/' + centerId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var newOption = new Option('', '', false, false);
                            roomSelect.append(newOption);
                            
                            response.data.forEach(function(room) {
                                var newOption = new Option(room.text, room.id, false, false);
                                roomSelect.append(newOption);
                            });
                            
                            roomSelect.trigger('change');
                        }
                    },
                    error: function(error) {
                        console.error('Error loading rooms:', error);
                    }
                });
            }
        });

        previewBtn.addEventListener('click', function (e) {
            e.preventDefault();

            var fileInput = form.querySelector('input[name="file"]');
            if (fileInput.files.length === 0) {
                Swal.fire({
                    text: "Por favor selecciona un archivo Excel.",
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                return;
            }

            var formData = new FormData(form);

            previewBtn.setAttribute('data-kt-indicator', 'on');
            previewBtn.disabled = true;

            // Hide previous results
            previewSection.classList.add('d-none');
            resultsSection.classList.add('d-none');

            axios.post('/admin/infantes/importar/preview', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function (response) {
                if (response.data.success) {
                    previewData = response.data.data;
                    renderPreviewTable(previewData);
                    previewSection.classList.remove('d-none');
                } else {
                    Swal.fire({
                        text: response.data.message || "Error al procesar el archivo.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            })
            .catch(function (error) {
                Swal.fire({
                    text: error.response?.data?.message || "Ocurrió un error en la solicitud.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            })
            .finally(function () {
                previewBtn.removeAttribute('data-kt-indicator');
                previewBtn.disabled = false;
            });
        });
    }

    var renderPreviewTable = function (data) {
        var tableBody = document.getElementById('kt_preview_table_body');
        tableBody.innerHTML = '';

        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">No se encontraron datos válidos en el archivo.</td></tr>';
            return;
        }

        data.forEach(function (row, index) {
            var tr = document.createElement('tr');
            
            // Status badge logic
            var statusBadge = '';
            if (row.is_valid) {
                statusBadge = '<span class="badge badge-light-success">Válido</span>';
            } else {
                statusBadge = '<span class="badge badge-light-danger">Inválido</span>';
                if (row.errors && row.errors.length > 0) {
                     statusBadge += '<br><small class="text-danger">' + row.errors.join(', ') + '</small>';
                }
            }

            var genderDisplay = row.genero;
            if (row.genero === 'masculino') genderDisplay = 'Masculino';
            if (row.genero === 'femenino') genderDisplay = 'Femenino';
            if (row.genero === 'no_especificado') genderDisplay = 'No Esp.';

            var insuranceDisplay = row.tiene_seguro ? 'Sí' : 'No';
            if (row.detalle_seguro) {
                insuranceDisplay += '<br><span class="text-muted fs-8">' + row.detalle_seguro + '</span>';
            }

            var location = [row.departamento, row.ciudad, row.municipio].filter(Boolean).join(', ');

            tr.innerHTML = `
                <td>${row.nombres || '-'}</td>
                <td>
                    <div class="d-flex flex-column">
                        <span>${row.apellido_paterno || '-'}</span>
                        <span class="text-muted fs-8">${row.apellido_materno || '-'}</span>
                    </div>
                </td>
                <td>${genderDisplay || '-'}</td>
                <td>${row.fecha_nacimiento || '-'}</td>
                <td>${row.direccion || '-'}</td>
                <td>${location || '-'}</td>
                <td>${insuranceDisplay}</td>
                <td>${row.peso || '-'}</td>
                <td>${row.talla || '-'}</td>
                <td>${statusBadge}</td>
            `;
            tableBody.appendChild(tr);
        });
    }

    var handleImport = function () {
        var importBtn = document.getElementById('kt_import_btn');
        var resultsSection = document.getElementById('kt_results_section');
        var resultsBody = document.getElementById('kt_results_body');
        var form = document.getElementById('kt_import_form');

        importBtn.addEventListener('click', function (e) {
            e.preventDefault();

            if (previewData.length === 0) {
                return;
            }

            var centerId = form.querySelector('select[name="childcare_center_id"]').value;
            var roomId = form.querySelector('select[name="room_id"]').value;

            Swal.fire({
                text: "¿Estás seguro de importar estos datos?",
                icon: "info",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Sí, importar",
                cancelButtonText: "No, cancelar",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    
                    // Show blockUI
                    var target = document.querySelector("#kt_app_content");
                    var blockUI = new KTBlockUI(target, {
                        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Importando datos...</div>',
                    });
                    blockUI.block();

                    axios.post('/admin/infantes/importar/store', { 
                        data: previewData,
                        childcare_center_id: centerId,
                        room_id: roomId
                    })
                    .then(function (response) {
                        if (response.data.success) {
                            resultsSection.classList.remove('d-none');
                            var details = response.data.details;
                            
                            var successCount = details.imported_count;
                            var failCount = details.failed_count;
                            var failures = details.failures;

                            var html = `
                                <div class="alert alert-success d-flex align-items-center p-5 mb-5">
                                    <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-success">Importación Finalizada</h4>
                                        <span>Se importaron correctamente <strong>${successCount}</strong> registros. Fallaron <strong>${failCount}</strong>.</span>
                                    </div>
                                </div>
                            `;

                            if (failures.length > 0) {
                                html += `
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Nombre</th>
                                                    <th class="min-w-140px">Error</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                `;
                                failures.forEach(function(fail) {
                                    html += `
                                        <tr>
                                            <td class="text-dark fw-bold text-hover-primary fs-6">${fail.row.nombres || ''} ${fail.row.apellido_paterno || ''}</td>
                                            <td class="text-danger">${fail.error}</td>
                                        </tr>
                                    `;
                                });
                                html += `</tbody></table></div>`;
                            }

                            resultsBody.innerHTML = html;
                            
                            // Hide preview section after import
                            document.getElementById('kt_preview_section').classList.add('d-none');
                            // Clear file input
                            document.getElementById('kt_import_form').reset();
                            previewData = [];

                            Swal.fire({
                                text: "Proceso completado.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        Swal.fire({
                            text: error.response?.data?.message || "Error al importar.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    })
                    .finally(function () {
                        blockUI.release();
                        blockUI.destroy();
                    });
                }
            });
        });
    }

    return {
        init: function () {
            handlePreview();
            handleImport();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTImportChildren.init();
});
