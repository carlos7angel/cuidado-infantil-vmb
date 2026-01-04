"use strict";

var KTFormsChildcareCenter = function () {

    var submitButton;
    var validator;
    var form;

    var _initDatepicker = function () {
        $('.datepicker_flatpickr').flatpickr({
            dateFormat: "d/m/Y",
        });
    };

    var _handleForm = function () {
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'El nombre es obligatorio'
                            }
                        }
                    },
                    state: {
                        validators: {
                            notEmpty: {
                                message: 'El departamento es obligatorio'
                            }
                        }
                    },
                    municipality: {
                        validators: {
                            notEmpty: {
                                message: 'El municipio es obligatorio'
                            }
                        }
                    },
                    email: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'Ingrese un correo electrónico válido',
                            }
                        }
                    },
                    contact_email: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'Ingrese un correo electrónico válido',
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
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        text: response.message || "Registro guardado satisfactoriamente.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Aceptar",
                                        allowOutsideClick: false,
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function(result){
                                        if (result.isConfirmed && response.redirect) {
                                            window.location = response.redirect;
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
            form = document.querySelector('#kt_childcare_center_form');
            if (!form) {
                return;
            }
            submitButton = document.getElementById('kt_childcare_center_submit');
            if (!submitButton) {
                return;
            }
            _initDatepicker();
            _handleForm();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTFormsChildcareCenter.init();
});

