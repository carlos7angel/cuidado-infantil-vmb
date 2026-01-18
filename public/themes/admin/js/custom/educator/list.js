"use strict";

var KTEducatorsList = function () {

    var table = document.getElementById('kt_table_educators');
    var datatable;
    const blockUI = new KTBlockUI(document.querySelector('#kt_app_wrapper'));

    var initTable = function () {

        datatable = $(table).DataTable({
            responsive: {details: {type: 'column'}},
            searchDelay: 500,
            processing: true,
            serverSide: true,
            filter: true,
            sortable: true,
            pagingType: 'full_numbers',
            pagination: true,
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            layout: {scroll: false, footer: false,},
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "No existen registros.",
                "sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar: ",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            ajax: {
                url: table.dataset.url,
                type: 'POST',
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {},
            },
            data: null,
            columns: [
                {data: 'id', name: "id"},
                {data: 'full_name', name: "first_name"},
                {data: 'email', name: "email"},
                {data: 'state', name: "state"},
                {data: 'childcare_centers', name: "childcare_centers"},
                {data: 'status', name: "status"},
                {data: 'created_at', name: "created_at"},
                {data: null, responsivePriority: -1},
            ],

            // Order settings
            order: [
                [6, 'desc']
            ],

            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    className: 'dtr-control',
                    render: function (data, type, full, meta) {
                        return ``;
                    },
                },
                {
                    targets: 1,
                    orderable: true,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data, type, full, meta) {
                        let toEditUrl = "/admin/educadores/editar/" + full.id;
                        return `<a href="${toEditUrl}" class="text-primary fw-bold text-hover-primary text-decoration-underline fs-7">${data || '-'}</a>`;
                    },
                },
                {
                    targets: 2,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<div class="text-gray-900 fw-semibold d-block mb-1 fs-7">${data || '-'}</div>`;
                    },
                },
                {
                    targets: 3,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<div class="text-gray-900 fw-semibold d-block fs-7">${data || '-'}</div>`;
                    },
                },
                {
                    targets: 4,
                    orderable: false,
                    searchable: false,
                    className: 'text-start pe-0',
                    render: function (data, type, full, meta) {
                        return `<div class="text-gray-700 fw-semibold d-block fs-7">${data || '-'}</div>`;
                    },
                },
                {
                    targets: 5,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        var badgeClass = data === 'Activo' ? 'badge-success' : 'badge-danger';
                        return `<span class="badge ${badgeClass} fs-8">${data}</span>`;
                    },
                },
                {
                    targets: 6,
                    orderable: true,
                    searchable: true,
                    className: 'dt-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<span class="text-muted fw-semibold text-muted d-block fs-7">${data || '-'}</span>`;
                    },
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function (data, type, full, meta) {
                        var toEditUrl = "/admin/educadores/editar/" + full.id;
                        var toggleUrl = "/admin/educadores/" + full.id + "/toggle-estado";
                        var isActive = full.status === 'Activo';
                        var toggleTitle = isActive ? 'Desactivar cuenta' : 'Activar cuenta';
                        var toggleBtnClass = isActive ? 'btn-danger' : 'btn-success';

                        return `
                            <a href="${toEditUrl}" class="btn btn-sm btn-icon btn-secondary me-1" title="Editar">
                                <i class="ki-outline ki-pencil text-gray-600 fs-2"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-icon ${toggleBtnClass} js-toggle-educator-status" 
                                    data-url="${toggleUrl}" data-id="${full.id}" data-status="${full.status}" 
                                    title="${toggleTitle}">
                                <i class="ki-outline ki-switch text-white fs-2"></i>
                            </button>
                        `;
                    },
                },
            ],
        });

        $(table).on('click', '.js-toggle-educator-status', function (e) {
            e.preventDefault();

            var button = $(this);
            var url = button.data('url');
            var currentStatus = button.data('status');
            var isActive = currentStatus === 'Activo';

            var confirmText = isActive
                ? '¿Está seguro de desactivar la cuenta de este educador? No podrá iniciar sesión en la app.'
                : '¿Está seguro de activar la cuenta de este educador? Podrá iniciar sesión en la app.';

            Swal.fire({
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light'
                }
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                button.prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function (response) {
                        blockUI.block();
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                text: response.message || 'Estado de la cuenta actualizado correctamente',
                                icon: 'success',
                                buttonsStyling: false,
                                confirmButtonText: 'Aceptar',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            }).then(function () {
                                datatable.ajax.reload(null, false);
                            });
                        } else {
                            Swal.fire({
                                text: response.message || 'No se pudo actualizar el estado de la cuenta',
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'Aceptar',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        var message = 'Ocurrió un error al actualizar el estado de la cuenta';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            text: message,
                            icon: 'error',
                            buttonsStyling: false,
                            confirmButtonText: 'Aceptar',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    },
                    complete: function () {
                        button.prop('disabled', false);
                        blockUI.release();
                    }
                });
            });
        });

        $('#kt_search').on('click', function (e) {
            e.preventDefault();
            var params = {};
            $('.datatable-input').each(function () {
                var i = $(this).data('col-index');
                var value = $(this).val();
                if (params[i]) {
                    params[i] += '|' + value;
                } else {
                    params[i] = value;
                }
            });
            $.each(params, function (i, val) {
                datatable.column(i).search(val ? val : '', false, false);
            });

            let search = $("input[name='dt_search_input']").val();
            datatable.search(search ? search : '', false, false);

            datatable.table().draw();
        });

        $('#kt_reset').on('click', function (e) {
            e.preventDefault();

            $("input[name='dt_search_input']").val('');
            datatable.search('', false, false);

            $('.datatable-input').each(function () {
                if ($(this).is('select')) {
                    $(this).val('').trigger('change');
                } else {
                    $(this).val('');
                }
                datatable.column($(this).data('col-index')).search('', false, false);
            });

            datatable.table().draw();
        });

        // Handle report button loader
        $('#kt_educator_report_btn').on('click', function (e) {
            e.preventDefault();

            var button = $(this);
            var reportUrl = button.data('url');

            if (!reportUrl) {
                console.error('Report URL not found');
                return;
            }

            // Disable button immediately
            button.prop('disabled', true);

            // Add loading class to show visual feedback
            button.addClass('loading');

            // Change button text to show loading
            var originalHtml = button.html();
            button.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Generando...');

            // Navigate to report URL after a brief delay
            setTimeout(function() {
                window.location.href = reportUrl;
            }, 800);

            // Fallback: re-enable button after 10 seconds if something goes wrong
            setTimeout(function() {
                button.prop('disabled', false);
                button.removeClass('loading');
                button.html(originalHtml);
            }, 10000);
        });
    }

    return {
        init: function () {
            if (!table) {
                return;
            }
            initTable();
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTEducatorsList.init();
});

