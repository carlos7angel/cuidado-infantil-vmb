"use strict";

var KTChildrenList = function () {

    var table = document.getElementById('kt_table_children');
    var datatable;
    var childcareCenterFilter = $('#kt_filter_childcare_center');

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
                data: function (d) {
                    // Add childcare center filter to AJAX request
                    if (childcareCenterFilter.length && childcareCenterFilter.val()) {
                        d.childcare_center_id = childcareCenterFilter.val();
                    }
                    return d;
                },
            },
            data: null,
            columns: [
                {data: 'id', name: "id"},
                {data: 'full_name', name: "child_name"},
                {data: 'childcare_center_name', name: "childcare_center_id"},
                {data: 'state', name: "state"},
                {data: 'enrollment_date', name: "enrollment_date"},
                {data: 'status', name: "status"},
                {data: null, responsivePriority: -1},
            ],

            // Order settings
            order: [
                [4, 'desc']
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
                        if (!full.child_id) {
                            return `<span class="text-gray-600 fs-7">${data || '-'}</span>`;
                        }

                        var toShowUrl = "/admin/infantes/" + full.child_id;
                        return `
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label fs-3 bg-light-primary text-primary">${full.initials || '??'}</div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="${toShowUrl}" class="text-gray-800 text-hover-primary mb-1 fw-bold">${data || '-'}</a>
                                    <span class="text-muted fs-7">${full.age_readable || '-'}</span>
                                </div>
                            </div>`;
                    },
                },
                {
                    targets: 2,
                    orderable: false,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        var centerName = data || '-';
                        var roomName = full.room_name || '-';
                        return `
                            <div class="d-flex flex-column">
                                <div class="text-gray-900 fw-semibold d-block fs-7">${centerName}</div>
                                <span class="text-muted fw-semibold text-muted d-block fs-7">${roomName}</span>
                            </div>`;
                    },
                },
                {
                    targets: 3,
                    orderable: false,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<span class="text-muted fw-semibold text-muted d-block fs-7">${data || '-'}</span>`;
                    },
                },
                {
                    targets: 4,
                    orderable: true,
                    searchable: false,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<span class="text-muted fw-semibold text-muted d-block fs-7">${data || '-'}</span>`;
                    },
                },
                {
                    targets: 5,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        var badgeClass = 'badge-light-secondary';
                        if (data === 'Activo') {
                            badgeClass = 'badge-light-success';
                        } else if (data === 'Inactivo') {
                            badgeClass = 'badge-light-warning';
                        } else if (data === 'Egresado' || data === 'Trasladado') {
                            badgeClass = 'badge-light-info';
                        } else if (data === 'Retirado') {
                            badgeClass = 'badge-light-danger';
                        }
                        return `<span class="badge ${badgeClass}">${data || '-'}</span>`;
                    },
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function (data, type, full, meta) {
                        if (!full.child_id) {
                            return '';
                        }
                        var toShowUrl = "/admin/infantes/" + full.child_id;
                        var toMonitoringUrl = "/admin/monitoreo/infante/" + full.child_id + "/resumen";
                        return `
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="${toShowUrl}" class="btn btn-sm btn-icon btn-light-primary" title="Ver Detalle">
                                    <i class="ki-outline ki-eye fs-2"></i>
                                </a>
                                <a href="${toMonitoringUrl}" class="btn btn-sm btn-icon btn-light-info" title="Seguimiento">
                                    <i class="ki-outline ki-chart-simple fs-2"></i>
                                </a>
                            </div>`;
                    },
                },
            ],
        });

        // Handle childcare center filter dropdown change
        childcareCenterFilter.on('change', function () {
            datatable.ajax.reload();
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

            // Reset childcare center filter
            childcareCenterFilter.val('').trigger('change');

            $('.datatable-input').each(function () {
                if ($(this).is('select')) {
                    $(this).val('');
                } else {
                    $(this).val('');
                }
                datatable.column($(this).data('col-index')).search('', false, false);
            });

            datatable.table().draw();
        });

        $('#kt_children_report_btn').on('click', function (e) {

            var button = this;

            const spinner = button.querySelector('.spinner-border');
            const icon = button.querySelector('i');
            const text = button.querySelector('span.ms-2');

            if (spinner && icon && text) {
                spinner.classList.remove('d-none');
                icon.classList.add('d-none');
                text.textContent = 'Generando...';
                button.disabled = true;

                setTimeout(function () {
                    if (button.disabled) {
                        spinner.classList.add('d-none');
                        icon.classList.remove('d-none');
                        text.textContent = 'Generar Reporte';
                        button.disabled = false;
                       
                    }
                }, 5000);
            }

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
    KTChildrenList.init();
});

