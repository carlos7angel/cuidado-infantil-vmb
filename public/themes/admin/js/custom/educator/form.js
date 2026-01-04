"use strict";

var KTFormsEducator = function () {

    var submitButton;
    var validator;
    var form;

    var _initDatepicker = function () {
        $('.datepicker_flatpickr').flatpickr({
            dateFormat: "d/m/Y",
        });
    };

    var _handleForm = function () {
        // Build fields object dynamically based on form fields
        var fields = {
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: 'El nombre es obligatorio'
                            }
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: 'El apellido es obligatorio'
                            }
                        }
                    },
                    gender: {
                        validators: {
                            notEmpty: {
                                message: 'El género es obligatorio'
                            }
                        }
                    },
                    birth: {
                        validators: {
                            notEmpty: {
                                message: 'La fecha de nacimiento es obligatoria'
                            }
                        }
                    },
                    childcare_center_ids: {
                        validators: {
                            callback: {
                                message: 'Debe seleccionar al menos un centro de cuidado infantil',
                                callback: function (value, validator, $field) {
                                    var checked = $('input[name="childcare_center_ids[]"]:checked').length;
                                    if (checked < 1) {
                                        return false;
                                    } else {
                                        // Update hidden field value
                                        var selectedIds = [];
                                        $('input[name="childcare_center_ids[]"]:checked').each(function() {
                                            selectedIds.push($(this).val());
                                        });
                                        $('#childcare_center_ids_validator').val(selectedIds.join(','));
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                };

        // Only add email validation if email field exists (creation mode)
        if (form.querySelector('input[name="email"]')) {
            fields.email = {
                validators: {
                    regexp: {
                        regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                        message: 'Ingrese un correo electrónico válido',
                    },
                    notEmpty: {
                        message: 'El correo electrónico es obligatorio'
                    }
                }
            };
        }

        validator = FormValidation.formValidation(
            form,
            {
                fields: fields,
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

        // Validate childcare centers on checkbox change
        $('.childcare-center-checkbox').on('change', function () {
            var selectedIds = [];
            $('input[name="childcare_center_ids[]"]:checked').each(function() {
                selectedIds.push($(this).val());
            });
            $('#childcare_center_ids_validator').val(selectedIds.join(','));
            validator.revalidateField('childcare_center_ids');
        });

        // Initialize hidden field value on page load
        var initialChecked = $('input[name="childcare_center_ids[]"]:checked').length;
        if (initialChecked >= 1) {
            var selectedIds = [];
            $('input[name="childcare_center_ids[]"]:checked').each(function() {
                selectedIds.push($(this).val());
            });
            $('#childcare_center_ids_validator').val(selectedIds.join(','));
        }

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
                                    var message = response.message || "Registro guardado satisfactoriamente.";
                                    
                                    // If password is provided (new educator), show it prominently
                                    if (response.password) {
                                        message = "Educador creado exitosamente.<br><br>" +
                                                 "<strong>Contraseña generada:</strong> <code style='font-size: 16px; padding: 5px 10px; background: #f5f5f5; border-radius: 4px;'>" + response.password + "</code><br><br>" +
                                                 "Por favor, guarde esta contraseña y compártala con el educador.";
                                        
                                        Swal.fire({
                                            html: message,
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
                                        Swal.fire({
                                            text: message,
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
                                    }
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
            form = document.querySelector('#kt_educator_form');
            if (!form) {
                return;
            }
            submitButton = document.getElementById('kt_educator_submit');
            if (!submitButton) {
                return;
            }
            _initDatepicker();
            _handleForm();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTFormsEducator.init();
});

