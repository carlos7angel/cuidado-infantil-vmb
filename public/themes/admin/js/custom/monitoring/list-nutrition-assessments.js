var KTListNutritionAssessments = function () {
    var table = document.getElementById('kt_table_nutritional_assessments');
    var datatable;
    var detailContainer = document.getElementById('kt_monitoring_nutritional_assessments_detail');
    var listContainer = document.getElementById('kt_monitoring_nutritional_assessments_list');
    
    var initTable = function () {
       
        datatable = $(table).DataTable({
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
            datatable.button(0).trigger();
        });

        $('a[data-kt-export="pdf"]').on('click', function (e) {
            e.preventDefault();
            datatable.button(1).trigger();
        });

    };

    var clickDetail = function () {
        $(document).on('click', '.kt_btn_nutritional_assessment_detail', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            if (!url) {
                toastr.error('URL de evaluación no válida');
                return;
            }

            var blockUI = new KTBlockUI(listContainer);
            
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                beforeSend: function () {
                    blockUI.block();
                },
                success: function (response) {
                    if (!response.success || !response.render) {
                        toastr.warning('Hubo un problema al cargar la información.');
                        return;
                    }
                    
                    $(detailContainer).html(response.render);
                    $(listContainer).addClass('d-none').removeClass('d-block');
                    $(detailContainer).addClass('d-block').removeClass('d-none');
                },
                complete: function () {
                    blockUI.release();
                    blockUI.destroy();
                },
                error: function (xhr) {
                    var message = xhr.hasOwnProperty('responseJSON') && xhr.responseJSON.message 
                        ? xhr.responseJSON.message 
                        : "Ocurrió un problema al cargar el detalle";
                    toastr.error(message);
                }
            });
        });

        // Botón para volver al listado
        $(document).on('click', '.kt_btn_back_to_list', function (e) {
            e.preventDefault();
            $(detailContainer).addClass('d-none').removeClass('d-block').html('');
            $(listContainer).addClass('d-block').removeClass('d-none');
        });
    };
    
    return {
        init: function () {
            if (!table) {
                return;
            }
            initTable();
            clickDetail();
        },
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTListNutritionAssessments.init();
});
