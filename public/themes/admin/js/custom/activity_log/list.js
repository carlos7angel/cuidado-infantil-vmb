"use strict";

var KTActivityLogsList = function () {
    var table = document.getElementById('kt_table_activity_logs');
    var datatable;

    var initDateRange = function () {
        var start = moment().subtract(6, 'days').startOf('day');
        var end = moment().endOf('day');

        function cb(startDate, endDate) {
            $("#kt_field_daterangepicker_logs").val(startDate.format("DD/MM/YYYY") + " - " + endDate.format("DD/MM/YYYY"));
            $("#start_date_logs").val(startDate.format('DD/MM/YYYY'));
            $("#end_date_logs").val(endDate.format('DD/MM/YYYY'));
        }

        $("#kt_field_daterangepicker_logs").daterangepicker({
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
        }, function (dpStart, dpEnd) {
            start = dpStart;
            end = dpEnd;
            cb(start, end);
        });

        cb(start, end);
    };

    var initTable = function () {
        datatable = $(table).DataTable({
            responsive: { details: { type: 'column' } },
            searchDelay: 500,
            processing: true,
            serverSide: true,
            filter: true,
            sortable: true,
            pagingType: 'full_numbers',
            pagination: true,
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            layout: { scroll: false, footer: false },
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "No existen registros.",
                sInfo: "Mostrando _START_ al _END_ de _TOTAL_ registros",
                sInfoEmpty: "Mostrando 0 registros",
                sInfoFiltered: "(filtrado de _MAX_ registros)",
                sInfoPostFix: "",
                sSearch: "Buscar: ",
                sUrl: "",
                sInfoThousands: ",",
                sLoadingRecords: "Cargando...",
                oAria: {
                    sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                    sSortDescending: ": Activar para ordenar la columna de manera descendente"
                }
            },
            ajax: {
                url: table.dataset.url,
                type: 'POST',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: function (d) {
                    var startDate = $('#start_date_logs').val();
                    var endDate = $('#end_date_logs').val();
                    if (startDate) {
                        d.start_date = startDate;
                    }
                    if (endDate) {
                        d.end_date = endDate;
                    }
                    return d;
                }
            },
            data: null,
            columns: [
                { data: 'id', name: 'id' },
                { data: 'log_name', name: 'log_name' },
                { data: 'description', name: 'description' },
                { data: 'user_name', name: 'user_name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'ip_address', name: 'ip_address' },
                { data: null, responsivePriority: -1 }
            ],
            order: [
                [4, 'desc']
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    className: 'dtr-control',
                    render: function () {
                        return '';
                    }
                },
                {
                    targets: 1,
                    orderable: true,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data) {
                        return '<span class="text-gray-900 fw-bold fs-7">' + (data || '-') + '</span>';
                    }
                },
                {
                    targets: 2,
                    orderable: true,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data) {
                        return '<span class="text-gray-700 fs-7">' + (data || '-') + '</span>';
                    }
                },
                {
                    targets: 3,
                    orderable: true,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data) {
                        return '<span class="text-gray-700 fs-7">' + (data || '-') + '</span>';
                    }
                },
                {
                    targets: 4,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data) {
                        return '<span class="text-muted fw-semibold fs-7">' + (data || '-') + '</span>';
                    }
                },
                {
                    targets: 5,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data) {
                        return '<span class="text-muted fw-semibold fs-7">' + (data || '-') + '</span>';
                    }
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function () {
                        return '<button type="button" class="btn btn-sm btn-icon btn-secondary btn-view-details" title="Ver detalles">' +
                            '<i class="ki-outline ki-magnifier text-gray-600 fs-2"></i>' +
                            '</button>';
                    }
                }
            ]
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

            var search = $("input[name='dt_search_input']").val();
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

            $('#start_date_logs').val('');
            $('#end_date_logs').val('');
            $('#kt_field_daterangepicker_logs').val('');

            datatable.table().draw();
        });

        $('#kt_table_activity_logs tbody').on('click', '.btn-view-details', function () {
            var row = $(this).closest('tr');
            var data = datatable.row(row).data();
            if (!data) {
                return;
            }

            $('#activity_log_type').text(data.log_name || '-');
            $('#activity_log_description').text(data.description || '-');
            $('#activity_log_user').text(data.user_name || '-');
            $('#activity_log_date').text(data.created_at || '-');
            $('#activity_log_ip').text(data.ip_address || '-');
            $('#activity_log_agent').text(data.user_agent || '-');

            var propertiesElement = $('#activity_log_properties');
            var properties = data.properties || {};
            var formatted;
            try {
                formatted = JSON.stringify(properties, null, 2);
            } catch (e) {
                formatted = '';
            }
            propertiesElement.text(formatted);

            var modalEl = document.getElementById('kt_modal_activity_log_detail');
            if (!modalEl) {
                return;
            }
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    };

    return {
        init: function () {
            if (!table) {
                return;
            }
            initDateRange();
            initTable();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTActivityLogsList.init();
});

