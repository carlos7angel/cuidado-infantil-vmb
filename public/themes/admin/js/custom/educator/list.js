"use strict";

var KTEducatorsList = function () {

    var table = document.getElementById('kt_table_educators');
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
                data: {},
            },
            data: null,
            columns: [
                {data: 'id', name: "id"},
                {data: 'full_name', name: "first_name"},
                {data: 'email', name: "email"},
                {data: 'state', name: "state"},
                {data: 'childcare_centers', name: "childcare_centers"},
                {data: 'phone', name: "phone"},
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
                    orderable: false,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<span class="text-muted fw-semibold text-muted d-block fs-7">${data || '-'}</span>`;
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
                        return `<a href="${toEditUrl}" class="btn btn-sm btn-icon btn-secondary" title="Editar">
                                    <i class="ki-outline ki-pencil text-gray-600 fs-2"></i>
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
                if ($(this).is('select')) {
                    $(this).val('');
                } else {
                    $(this).val('');
                }
                datatable.column($(this).data('col-index')).search('', false, false);
            });

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
    KTEducatorsList.init();
});

