"use strict";

var KTIncidentsList = function () {

    var table = document.getElementById('kt_table_incidents');
    var datatable;

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
                    // Add room filter to AJAX request
                    var roomFilter = $("select[name='kt_search_room']").val();
                    if (roomFilter) {
                        d.kt_search_room = roomFilter;
                    }
                    return d;
                },
            },
            data: null,
            columns: [
                {data: 'id', name: "id"},
                {data: 'code', name: "code"},
                {data: 'child_name', name: "child_name"},
                {data: 'childcare_center_name', name: "childcare_center_name"},
                {data: 'type', name: "type"},
                {data: 'severity', name: "severity"},
                {data: 'status', name: "status"},
                {data: 'incident_date', name: "incident_date"},
                {data: null, responsivePriority: -1},
            ],

            // Order settings
            order: [
                [7, 'desc']
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
                        return `<span class="text-gray-900 fw-bold text-hover-primary fs-7">${data || '-'}</span>`;
                    },
                },
                {
                    targets: 2,
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
                                    <div class="symbol-label fs-3 bg-light-primary text-primary">${full.child_initials || '??'}</div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="${toShowUrl}" class="text-gray-800 text-hover-primary mb-1 fw-bold">${data || '-'}</a>
                                    <span class="text-muted fs-7">${full.child_age_readable || '-'}</span>
                                </div>
                            </div>`;
                    },
                },
                {
                    targets: 3,
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
                    targets: 4,
                    orderable: true,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data, type, full, meta) {
                        return `<span class="text-gray-900 fw-semibold d-block mb-1 fs-7">${data || '-'}</span>`;
                    },
                },
                {
                    targets: 5,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        var severityValue = full.severity_value;
                        var severityColor = full.severity_color;
                        var badgeClass = 'badge-light-secondary';
                        var colorClass = 'text-secondary';
                        
                        if (severityColor) {
                            if (severityColor === '#4CAF50') {
                                badgeClass = 'badge-light-success';
                                colorClass = 'text-success';
                            } else if (severityColor === '#FF9800') {
                                badgeClass = 'badge-light-warning';
                                colorClass = 'text-warning';
                            } else if (severityColor === '#F44336') {
                                badgeClass = 'badge-light-danger';
                                colorClass = 'text-danger';
                            } else if (severityColor === '#9C27B0') {
                                badgeClass = 'badge-light-info';
                                colorClass = 'text-info';
                            }
                        }
                        
                        return `<span class="badge ${badgeClass} fs-7 fw-bold">${data || '-'}</span>`;
                    },
                },
                {
                    targets: 6,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        var statusValue = full.status_value;
                        var badgeClass = 'badge-light-secondary';
                        
                        if (statusValue) {
                            switch(statusValue) {
                                case 'reportado':
                                    badgeClass = 'badge-light-primary';
                                    break;
                                case 'en_revision':
                                    badgeClass = 'badge-light-warning';
                                    break;
                                case 'cerrado':
                                    badgeClass = 'badge-light-success';
                                    break;
                                case 'escalado':
                                    badgeClass = 'badge-light-danger';
                                    break;
                                case 'archivado':
                                    badgeClass = 'badge-light-secondary';
                                    break;
                            }
                        }
                        
                        return `<span class="badge ${badgeClass} fs-7 fw-bold">${data || '-'}</span>`;
                    },
                },
                {
                    targets: 7,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
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
                        var toDetailUrl = "/admin/incidentes/" + full.id + "/detalle";
                        return `<a href="${toDetailUrl}" class="btn btn-sm btn-icon btn-secondary" title="Ver detalle">
                                    <i class="ki-outline ki-magnifier text-gray-600 fs-2"></i>
                                </a>`;
                    },
                },
            ],
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

            $("input[name='dt_search_input']").val('')

            $('.datatable-input').each(function () {
                // Reset both inputs and selects
                if ($(this).is('select')) {
                    $(this).val('').trigger('change');
                    datatable.column($(this).data('col-index')).search('', false, false);
                } else {
                    $(this).val('');
                    datatable.column($(this).data('col-index')).search('', false, false);
                }
            });

            // Reset select2 dropdowns that are not datatable-input
            $('select[name="kt_search_room"]').val('').trigger('change');

            datatable.table().draw();
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
    KTIncidentsList.init();
});
