"use strict";

var KTFormsRoom = function () {

    var submitButton;
    var validator;
    var form;

    var _handleForm = function () {
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    childcare_center_id: {
                        validators: {
                            notEmpty: {
                                message: 'El centro de cuidado infantil es obligatorio'
                            }
                        }
                    },
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'El nombre es obligatorio'
                            }
                        }
                    },
                    capacity: {
                        validators: {
                            notEmpty: {
                                message: 'La capacidad es obligatoria'
                            },
                            integer: {
                                message: 'La capacidad debe ser un número entero'
                            },
                            greaterThan: {
                                min: 1,
                                message: 'La capacidad debe ser mayor a 0'
                            }
                        }
                    },
                    is_active: {
                        validators: {
                            notEmpty: {
                                message: 'El estado es obligatorio'
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
            form = document.querySelector('#kt_room_form');
            if (!form) {
                return;
            }
            submitButton = document.getElementById('kt_room_submit');
            if (!submitButton) {
                return;
            }
            _handleForm();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTFormsRoom.init();
});

