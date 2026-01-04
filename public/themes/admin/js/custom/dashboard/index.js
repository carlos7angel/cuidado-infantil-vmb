"use strict";

var KTDashboard = function () {

    var element;
    var root;
    var chart;
    var colors;
    var xAxis;
    var yAxis;
    var series;

    var initChart = function () {

        if (typeof am5 === "undefined") { return; }
        element = document.getElementById("kt_report_by_created_date_chart");
        if (!element) { return; }

        var init = function() {
            root = am5.Root.new(element);
            root.setThemes([am5themes_Animated.new(root)]);
            chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    // layout: root.verticalLayout,
                })
            );

            xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: "date",
                    renderer: am5xy.AxisRendererX.new(root, {

                    })
                })
            );

            xAxis.get("renderer").grid.template.setAll({
                strokeOpacity: 0,
                visible: false
            });

            yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {})
                })
            );

            series = chart.series.push(
                am5xy.LineSeries.new(root, {
                    name: "Registros",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "value",
                    categoryXField: "date",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "{categoryX}: {valueY}"
                    })
                })
            );

            series.strokes.template.setAll({
                strokeWidth: 2
            });

            series.bullets.push(function (root, series, dataItem) {
                return am5.Bullet.new(root, {
                    sprite: am5.Circle.new(root, {
                        radius: 5,
                        fill: am5.color(0x5c6bc0),
                        stroke: root.interfaceColors.get("background"),
                        strokeWidth: 2
                    })
                });
            });

            series.appear(1000);
            chart.appear(1000, 100);
        }

        am5.ready(function () {
            init();
        });
    }

    var renderCards = function(totals) {
        document.getElementById('kt_card_total_denunciations_all').innerText = totals.all;
    }

    var loadReport = function() {

        var container = document.getElementById("kt_report_by_created_date_chart")
        var url = container.dataset.url;

        am5.net.load(url).then(function(result) {
            var data = am5.JSONParser.parse(result.response);

            // RENDER CHART
            xAxis.data.setAll(data.data.report);
            series.data.setAll(data.data.report);

            // RENDER CARDS
            renderCards(data.data.totals);

        }).catch(function(result) {
            console.log("Error loading " + result.xhr.responseURL);
        });
    }


    return {
        init: function () {
            initChart();
            loadReport()
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTDashboard.init();
});
