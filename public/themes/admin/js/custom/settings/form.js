"use strict";

var KTFormsSettings = function () {

    var submitButton;
    var validator;
    var form;

    var _handleForm = function () {
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    servidor_municipio: {
                        validators: {
                            stringLength: {
                                max: 255,
                                message: 'El municipio no puede exceder 255 caracteres'
                            }
                        }
                    },
                    servidor_departamento: {
                        validators: {
                            stringLength: {
                                max: 255,
                                message: 'El departamento no puede exceder 255 caracteres'
                            }
                        }
                    },
                    organizacion_nombre: {
                        validators: {
                            stringLength: {
                                max: 255,
                                message: 'El nombre de la organización no puede exceder 255 caracteres'
                            }
                        }
                    },
                    servidor_estado: {
                        validators: {
                            stringLength: {
                                max: 255,
                                message: 'El estado del servidor no puede exceder 255 caracteres'
                            }
                        }
                    },
                    version_api: {
                        validators: {
                            stringLength: {
                                max: 50,
                                message: 'La versión de la API no puede exceder 50 caracteres'
                            }
                        }
                    },
                    sistema_nombre: {
                        validators: {
                            stringLength: {
                                max: 255,
                                message: 'El nombre del sistema no puede exceder 255 caracteres'
                            }
                        }
                    }
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
                                // Block UI if needed
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        text: response.message || "Configuraciones guardadas satisfactoriamente.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Aceptar",
                                        allowOutsideClick: false,
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function(result){
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    toastr.error(response.message || "Ocurrió un problema", "Error");
                                }
                            },
                            complete: function (response) {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                            },
                            error: function (response) {
                                var errorMessage = "Ocurrió un problema";
                                if (response.responseJSON && response.responseJSON.message) {
                                    errorMessage = response.responseJSON.message;
                                } else if (response.responseJSON && response.responseJSON.errors) {
                                    var errors = response.responseJSON.errors;
                                    var firstError = Object.keys(errors)[0];
                                    errorMessage = errors[firstError][0];
                                }
                                toastr.error(errorMessage, "Error");
                            }
                        });
                    } else {
                        toastr.warning("Complete el formulario correctamente", "Advertencia");
                    }
                });
            }
        });

        // Revalidate select2 fields on change
        document.querySelectorAll('select').forEach(function (select) {
            $(select).on('change.select2', function () {
                const fieldName = select.getAttribute('name');
                if (fieldName && validator) {
                    validator.revalidateField(fieldName);
                }
            });
        });
    }

    return {
        init: function () {
            form = document.querySelector('#kt_settings_form');
            if (!form) {
                return;
            }
            submitButton = document.getElementById('kt_settings_submit');
            if (!submitButton) {
                return;
            }
            _handleForm();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTFormsSettings.init();
});

