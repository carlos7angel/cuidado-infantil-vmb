"use strict";

var KTDenunciationsList = function () {

    var table = document.getElementById('kt_table_denunciations');
    var datatable;

    var initTable = function () {

        datatable = $(table).DataTable({
            //responsive: true,
            responsive: {details: {type: 'column'}},
            // responsivePriority: 1,
            // dom: `<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
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
                {data: 'code', name: "code"},
                {data: 'violation_types', name: "violationTypes"},
                {data: 'aggressor_type', name: "aggressor_type"},
                {data: 'victim_type', name: "victim_type"},
                {data: 'date_event', name: "date_event"},
                {data: null, responsivePriority: -1},
            ],

            // Order settings
            order: [
                [5, 'desc']
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
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        let toDetailUrl = "/admin/monitoreo/denuncias/" + full.id + "/detalle";
                        return `<a href="${toDetailUrl}" class="text-primary fw-bold text-hover-primary text-decoration-underline fs-7">${data}</a>`;
                    },
                },
                {
                    targets: 2,
                    orderable: true,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        let types= ``;
                        if (Array.isArray(data)) {
                            data.forEach((violation) => {
                                types += `<div class="text-gray-700 fs-8 fw-bold mb-1">- ${violation.name}</div>`;
                            });
                            return `<div class="d-flex align-items-center">
                                        <div class="ms-5">${types}</div>
                                    </div>`
                        }

                        return ``;
                    },
                },
                {
                    targets: 3,
                    orderable: true,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data, type, full, meta) {
                        let nameAggressorHTML = '';
                        if (full.aggressor_name) {
                            nameAggressorHTML = `<span class="text-muted fw-semibold text-muted d-block fs-8">${full.aggressor_name}</span>`;
                        }
                        return `<div class="text-gray-900 fw-semibold d-block mb-1 fs-7">${data}</div>${nameAggressorHTML}`;
                    },
                },
                {
                    targets: 4,
                    orderable: true,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data, type, full, meta) {
                        let nameVictimHTML = '';
                        if (full.victim_name) {
                            nameVictimHTML = `<span class="text-muted fw-semibold text-muted d-block fs-8">${full.victim_name}</span>`;
                        }
                        return `<div class="text-gray-900 fw-semibold d-block mb-1 fs-7">${data}</div>
                        ${nameVictimHTML}
                        <span class="text-muted fw-semibold text-gray-400 d-block fs-8">${full.victim_gender} - ${full.victim_age_group}</span>`;
                    },
                },
                {
                    targets: 5,
                    orderable: true,
                    searchable: true,
                    className: 'dt-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<span class="text-muted fw-semibold text-muted d-block fs-7">${data}</span>`;
                    },
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function (data, type, full, meta) {
                        var toEditUrl = "/admin/monitoreo/denuncias/" + full.id + "/editar";
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
                if (params[i]) {
                    params[i] += '|' + $(this).val();
                } else {
                    params[i] = $(this).val();
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
                $(this).val('');
                datatable.column($(this).data('col-index')).search('', false, false);
            });

            datatable.table().draw();
        });
    }

    var _initDatepicker = function () {

        $('.datepicker_flatpickr').flatpickr({
            dateFormat: "d/m/Y",
        });

    };

    return {
        init: function () {
            if (!table) {
                return;
            }
            initTable();

            _initDatepicker();
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTDenunciationsList.init();
});
