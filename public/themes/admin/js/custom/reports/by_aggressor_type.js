"use strict";

var KTReports1 = function () {

    var element;
    var root;
    var chart;
    var colors;
    var xAxis;
    var yAxis;
    var series;

    var start;
    var end;

    var dt;

    var primary = KTUtil.getCssVariableValue('--bs-primary');
    var lightPrimary = KTUtil.getCssVariableValue('--bs-light-primary');
    var success = KTUtil.getCssVariableValue('--bs-success');
    var lightSuccess = KTUtil.getCssVariableValue('--bs-light-success');
    var gray200 = KTUtil.getCssVariableValue('--bs-gray-200');
    var gray500 = KTUtil.getCssVariableValue('--bs-gray-500');

    var initRangeDate = function() {

        start = moment().subtract(29, "days");
        end = moment();

        function cb(start, end) {
            $("#kt_field_daterangepicker").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
        }

        $("#kt_field_daterangepicker").daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: "DD/MM/YYYY"
            },
            ranges: {
                "Hoy": [moment(), moment()],
                "Ayer": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Últimos 7 Dias": [moment().subtract(6, "days"), moment()],
                "Últimos 30 Dias": [moment().subtract(29, "days"), moment()],
                "Este mes": [moment().startOf("month"), moment().endOf("month")],
                "Pasado mes": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
                "Este año": [moment().startOf("year"), moment().endOf("year")]
            }
        }, function(start, end, label) {
            loadChart(start, end);
        }, cb);

        cb(start, end);
    }

    var initChart = function () {

        if (typeof am5 === "undefined") { return; }
        element = document.getElementById("kt_report_by_aggressor_type");
        if (!element) { return; }

        var init = function() {
            root = am5.Root.new(element);
            root.setThemes([am5themes_Animated.new(root)]);
            chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    //wheelX: "panX",
                    //wheelY: "zoomX",
                    layout: root.verticalLayout,
                })
            );
            colors = chart.get("colors");

            xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: "short_name",
                    renderer: am5xy.AxisRendererX.new(root, {
                        minGridDistance: 30,
                    }),
                    bullet: function (root, axis, dataItem) {
                        return am5xy.AxisBullet.new(root, {
                            location: 0.5,
                            sprite: am5.Picture.new(root, {
                                width: 24,
                                height: 24,
                                centerY: am5.p50,
                                centerX: am5.p50,
                                src: dataItem.dataContext.icon,
                            }),
                        });
                    },
                })
            );

            xAxis.get("renderer").labels.template.setAll({
                paddingTop: 20,
                fontWeight: "400",
                fontSize: 10,
                rotation: -25,
                fill: am5.color(KTUtil.getCssVariableValue('--bs-gray-500')),

            });

            xAxis.get("renderer").grid.template.setAll({
                disabled: true,
                strokeOpacity: 0
            });

            yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {}),
                    min: 0
                })
            );

            yAxis.get("renderer").grid.template.setAll({
                stroke: am5.color(KTUtil.getCssVariableValue('--bs-gray-300')),
                strokeWidth: 1,
                strokeOpacity: 1,
                strokeDasharray: [3]
            });

            yAxis.get("renderer").labels.template.setAll({
                fontWeight: "400",
                fontSize: 10,
                fill: am5.color(KTUtil.getCssVariableValue('--bs-gray-500'))
            });

            series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "total",
                    categoryXField: "short_name"
                })
            );

            series.bullets.push(function () {
                var label = am5.Label.new(root, {
                    centerX: am5.p50,
                    centerY: am5.p100,
                    dy: -10,
                    fontSize: 12,
                    fontWeight: "500",
                    fill: am5.color(KTUtil.getCssVariableValue('--bs-gray-800'))
                });

                label.adapters.add("text", function (text, target) {
                    const dataItem = target.dataItem;
                    return dataItem?.get("valueY")?.toString() ?? "";
                });

                return am5.Bullet.new(root, {
                    locationY: 0,
                    sprite: label
                });
            });

            series.columns.template.setAll({
                tooltipText: "{categoryX}: {valueY}",
                tooltipY: 0,
                strokeOpacity: 0,
                // templateField: "columnSettings",
            });

            series.columns.template.setAll({
                strokeOpacity: 0,
                cornerRadiusBR: 0,
                cornerRadiusTR: 6,
                cornerRadiusBL: 0,
                cornerRadiusTL: 6,
            });

            series.columns.template.adapters.add("tooltipText", function (text, target) {
                const data = target.dataItem?.dataContext;
                if (data) {
                    return `${data.name}: ${data.total}`;
                }
                return text;
            });

            series.appear();
            chart.appear(1000, 100);
        }

        am5.ready(function () {
            init();
        });
    }

    var loadChart = function(start = null, end = null) {

        var url_data = "";
        if(start != null && end != null) {
            var start_date = moment(start).format('YYYY-MM-DD');
            var end_date = moment(end).format('YYYY-MM-DD');
            url_data =  `?start=${start_date}&end=${end_date}`;
        }

        var container = document.getElementById("kt_report_by_aggressor_type")
        var url = container.dataset.url + url_data;

        am5.net.load(url).then(function(result) {
            var data = am5.JSONParser.parse(result.response);

            // RENDER CHART
            xAxis.data.setAll(data.data.report);
            series.data.setAll(data.data.report);

            // RENDER CARD
            renderCard(data.data.report, data.data.total);

            // RENDER TABLE
            renderTable(data.data.rows);

        }).catch(function(result) {
            console.log("Error loading " + result.xhr.responseURL);
        });
    }

    var renderTable = function (data) {

        let table_container_name = '#kt_report_by_aggressor_type_table';

        if ($.fn.DataTable.isDataTable(table_container_name)) {
            $(table_container_name).DataTable().clear().destroy();
        }

        $(table_container_name + ' tbody').empty();

        data.forEach((item, index) => {

            console.log(item, index);

            const violenceList = item.violation_types.map(v => `- ${v.name}`).join('<br>');
            let statusHTML = '';

            switch (item.status) {
                case 'NEW':
                    statusHTML = `<span class="badge badge-light-primary">Nuevo</span>`;
                    break;
                case 'IN_PROGRESS':
                    statusHTML = `<span class="badge badge-light-success">En Progreso</span>`;
                    break;
                case 'CLOSED':
                    statusHTML = `<span class="badge badge-light-info">Cerrado</span>`;
                    break;
                case 'ARCHIVED':
                    statusHTML = `<span class="badge badge-light-danger">Archivado</span>`;
                    break;
                default:
                    statusHTML = `<span class="badge badge-light-secondary">${item.status}</span>`;
                    break;
            }

            let nameAggressorHTML = '';
            if (item.aggressor_name) {
                nameAggressorHTML = `<span class="text-muted fw-semibold text-muted d-block fs-8">${item.aggressor_name}</span>`;
            }

            let nameVictimHTML = '';
            if (item.victim_name) {
                nameVictimHTML = `<span class="text-muted fw-semibold text-muted d-block fs-8">${item.victim_name}</span>`;
            }

            moment.locale('es');
            $(table_container_name + ' tbody').append(`
                <tr>
                    <td>
                        <span class="text-gray-900 fw-semibold fs-8">${index+1}</span>
                    </td>
                    <td>
                        <a href="#" class="text-primary fw-bold text-hover-primary text-decoration-underline fs-7">${item.code}</a>
                    </td>
                    <td>
                        <div class="text-gray-900 fw-semibold d-block mb-1 fs-7">${item.aggressor_type}</div>
                        ${nameAggressorHTML}
                    </td>
                    <td>
                        <div class="text-gray-900 fw-semibold d-block mb-1 fs-7">${item.victim_type}</div>
                        ${nameVictimHTML}
                        <span class="text-muted fw-semibold text-gray-400 d-block fs-8">${item.victim_gender} - ${item.victim_age_group}</span>
                    </td>
                    <td>
                        <div class="text-gray-900 fw-semibold d-block mb-1 fs-8">${violenceList}</div>
                    </td>
                    <td>
                        <span class="text-muted fw-semibold text-muted d-block fs-7">${moment(item.date_event, 'DD/MM/YYYY').format('D [de] MMMM [de] YYYY')}</span>
                    </td>
                    <td>
                        <div class="text-gray-900 fw-semibold d-block mb-1 fs-7">${item.state}</div>
                    </td>
                    <td>
                        <span class="badge badge-light-secondary">${statusHTML}</span>
                    </td>
                </tr>
            `);
        });

        dt = $(table_container_name).DataTable({
            // responsive: true,
            dom:
                "<'row mb-2'" +
                "<'col-sm-6 d-flex align-items-center justify-content-start dt-toolbar'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'f>" +
                ">" +
                "<'table-responsive'tr>" +
                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">",
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: "Reporte",
                },
                {
                    extend: 'pdfHtml5',
                    title: "Reporte",
                    orientation: 'landscape',
                },
            ],
            pageLength: 10,
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
        });

        $('a[data-kt-export="excel"]').on('click', function (e) {
            e.preventDefault();
            dt.button(0).trigger();
        });

        $('a[data-kt-export="pdf"]').on('click', function (e) {
            e.preventDefault();
            dt.button(1).trigger();
        });
    }

    var renderCard = function(data, total) {

        console.log(data, total);

        const container = document.getElementById('kt_report_by_aggressor_type_card');
        container.innerHTML = '';

        data.forEach(item => {
            if (total > 0) {
                const percentage = ((item.total / total) * 100).toFixed(0);

                container.innerHTML += `<div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-bold fs-6 me-2">${item.name}</div>
                                            <div class="d-flex align-items-center">
                                                <div class="text-gray-900 fw-bolder fs-6">
                                                    <span>${percentage}%</span> <span class="ps-2">(${item.total})</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed my-3"></div>`;
            }
        });

        const container_total = document.getElementById('kt_report_by_aggressor_type_total');
        container_total.innerHTML = total;
    }


    return {
        init: function () {
            initRangeDate();

            initChart();
            loadChart(start, end);
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTReports1.init();
});
