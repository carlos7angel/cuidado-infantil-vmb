"use strict";

var KTFormsDocument = function () {

    var submitButton;
    var validator;
    var form;

    var _initFileUploader = function () {

        var inputFile = $('#kt_file_report');
        if (!inputFile.length) {
            return;
        }
        inputFile.fileuploader({
            limit: inputFile.data("maxfiles"),
            fileMaxSize: inputFile.data("maxsize"),
            addMore: true,
            //extensions: ['jpg', pdf', 'text/plain', 'audio/*'],
            changeInput: '<div class="fileuploader-input">' +
                '<div class="fileuploader-input-inner">' +
                '<div>${captions.feedback} ${captions.or} <span>${captions.button}</span></div>' +
                '</div>' +
                '</div>',
            theme: 'dropin',
            extensions: inputFile.data("accept").split(","),
            captions: {
                button: function (options) {
                    return 'Examinar';
                },
                feedback: function (options) {
                    return 'Seleccionar ' + (options.limit == 1 ? 'el archivo' : 'los archivos') + ' a subir.';
                },
                feedback2: function (options) {
                    return options.length + ' ' + (options.length > 1 ? 'archivos fueron seleccionados' : 'archivo fue seleccionado.');
                },
                errors: {
                    filesLimit: function (options) {
                        return 'Sólo ${limit} ' + (options.limit == 1 ? 'archivo' : 'archivos') + ' pueden ser cargados.'
                    },
                    filesType: 'Sólo se pueden cargar archivos ${extensions}.',
                    fileSize: '${name} es demasiado grande. Elija un archivo de hasta ${fileMaxSize}MB.',
                    filesSizeAll: 'Los archivos elegidos son demasiado grandes. Seleccione archivos de hasta ${maxSize} MB.',
                    fileName: 'Ya existe un archivo con el mismo nombre ${name}.',
                    remoteFile: 'No se permiten archivos remotos.',
                    folderUpload: 'No se permiten carpetas.',
                },
                removeConfirmation: '¿Esta seguro de remover el archivo?',
            },
            files: getDefaultFiles()

        });

    };

    var getDefaultFiles = function () {

        var files = [];
        var defaultFile = document.querySelector('input[name="file_report_data"]');
        if (defaultFile) {
            files.push({
                name: defaultFile.dataset.name,
                size: defaultFile.dataset.size,
                type: defaultFile.dataset.mimetype,
                file: defaultFile.dataset.path,
            });
        }
        return files;
    }

    var _handleForm = function () {

        validator = FormValidation.formValidation(
            form,
            {
                // locale: 'es_ES',
                // localization: FormValidation.locales.es_ES,
                fields: {
                    document_year: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    document_quarter: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    // file_report: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: 'El campo es obligatorio'
                    //         }
                    //     }
                    // },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    }),
                }
            }
        );

        submitButton.addEventListener('click', function (e) {

            e.preventDefault();
            if (validator) {
                validator.validate().then(function (status) {

                    if (status === 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');
                        submitButton.disabled = true;

                        var formData = new FormData($(form)[0]);
                        $.ajax({
                            type: 'POST',
                            url: form.action,
                            contentType: false,
                            processData: false,
                            data: formData,
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function (response) {

                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        text: "Registro guardado satisfactoriamente.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Aceptar",
                                        allowOutsideClick: false,
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function(result){
                                        if (result.isConfirmed) {
                                            window.location = response.redirect;
                                        }
                                    });
                                } else {
                                    toastr.error(response.message, "Ocurrio un problema");
                                }
                            },
                            complete: function (response) {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                            },
                            error: function (response) {
                                toastr.error(response.hasOwnProperty('responseJSON') ? response.responseJSON.message : "", "Ocurrió un problema");
                            }
                        });
                    } else {
                        toastr.warning("Complete el formulario", "Advertencia");
                    }
                });
            }
        });


        document.querySelectorAll('select').forEach(function (select) {
            $(select).on('change.select2', function () {
                const fieldName = select.getAttribute('name');
                if (fieldName) { validator.revalidateField(fieldName); }
            });
        });
    }

    const validateQuarterField = function (val) {
        if (val === 'ANUAL') {
            $('#kt_box_quarter').addClass('d-none').removeClass('d-block');
            validator.disableValidator('document_quarter');
        } else {
            $('#kt_box_quarter').removeClass('d-none').addClass('d-block');
            validator.enableValidator('document_quarter');
        }
    }

    const _handleQuarterField = function () {
        $(document).on('change', 'select[name="document_quarter"]', function (e) { validateQuarterField($(this).val()); });
    };

    const _initHandleQuarterField = function () {
        validateQuarterField($('input[name="document_type"]').val());
    }

    return {
        init: function () {
            form = document.querySelector('#kt_document_form');
            submitButton = document.getElementById('kt_document_submit');
            _initFileUploader();
            _handleForm();
            _handleQuarterField();
            _initHandleQuarterField();
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTFormsDocument.init();
});
