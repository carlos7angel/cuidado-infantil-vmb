"use strict";

// Class definition
var KTUsersUpdatePassword = function () {

    // Elements
    const element = document.getElementById('kt_modal_update_password');
    const form = element.querySelector('#kt_modal_update_password_form');
    const modal = new bootstrap.Modal(element);
    const passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));
    const blockUI = new KTBlockUI(document.querySelector('#kt_wrapper_modal_content_update_password'));
    // Update Password Modal
    var initUpdatePassword = () => {

        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'El campo el obligatorio'
                            },
                            stringLength: {
                                mi: 6,
                                max: 50,
                                message: 'La contraseña debe tener mínimo 6 caracteres'
                            },
                            callback: {
                                message: 'Introduzca una contraseña válida',
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm_password': {
                        validators: {
                            notEmpty: {
                                message: 'El campo el obligatorio'
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'La contraseña no coincide'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }
                    }),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        form.querySelector('input[name="password"]').addEventListener('input', function() {
            if (this.value.length > 0) {
                validator.updateFieldStatus('password', 'NotValidated');
            }
        });

        // Open Modal
        const openButton = document.getElementById('kt_handle_modal_update_password');
        openButton.addEventListener('click', e => {
            e.preventDefault();
            modal.show();
        });

        // Close button handler
        const closeButton = element.querySelector('[data-kt-users-modal-action="close"]');
        closeButton.addEventListener('click', e => {
            e.preventDefault();
            form.reset(); // Reset form
            modal.hide(); // Hide modal
        });

        // Cancel button handler
        const cancelButton = element.querySelector('[data-kt-users-modal-action="cancel"]');
        cancelButton.addEventListener('click', e => {
            e.preventDefault();
            form.reset(); // Reset form
            modal.hide(); // Hide modal
        });

        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
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
                                blockUI.block();
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        text: "Actualización satisfactoria.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Aceptar",
                                        allowOutsideClick: false,
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function(result){
                                        if (result.isConfirmed) {
                                            modal.hide();
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    toastr.error(response.message, "Ocurrio un problema");
                                }
                            },
                            complete: function (response) {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                blockUI.release();
                                blockUI.destroy();
                            },
                            error: function (response) {
                                toastr.error(response.hasOwnProperty('responseJSON') ? response.responseJSON.message : "", "Ocurrió un problema");
                            }
                        });

                    }
                });
            }
        });
    }

    var validatePassword = function() {
        return  (passwordMeter.getScore() > 75);
    }

    return {
        init: function () {

            initUpdatePassword();
        }
    };
}();


var KTUsersUpdateUsername = function () {

    // Elements
    const element = document.getElementById('kt_modal_update_username');
    const form = element.querySelector('#kt_modal_update_username_form');
    const modal = new bootstrap.Modal(element);
    const blockUI = new KTBlockUI(document.querySelector('#kt_wrapper_modal_content_update_username'));

    var initUpdateUsername = () => {

        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    username: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Open Modal
        const openButton = document.getElementById('kt_handle_modal_update_username');
        openButton.addEventListener('click', e => {
            e.preventDefault();
            form.reset();
            modal.show();
        });

        // Close button handler
        const closeButton = element.querySelector('[data-kt-users-modal-action="close"]');
        closeButton.addEventListener('click', e => {
            e.preventDefault();
            form.reset(); // Reset form
            modal.hide(); // Hide modal
        });

        // Cancel button handler
        const cancelButton = element.querySelector('[data-kt-users-modal-action="cancel"]');
        cancelButton.addEventListener('click', e => {
            e.preventDefault();
            form.reset(); // Reset form
            modal.hide(); // Hide modal
        });

        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
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
                                blockUI.block();
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        text: "Actualización satisfactoria.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Aceptar",
                                        allowOutsideClick: false,
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function(result){
                                        if (result.isConfirmed) {
                                            modal.hide();
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    toastr.error(response.message, "Ocurrio un problema");
                                }
                            },
                            complete: function (response) {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                blockUI.release();
                                blockUI.destroy();
                            },
                            error: function (response) {
                                toastr.error(response.hasOwnProperty('responseJSON') ? response.responseJSON.message : "", "Ocurrió un problema");
                            }
                        });

                    }
                });
            }
        });
    }

    return {
        init: function () {
            initUpdateUsername();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdatePassword.init();
    KTUsersUpdateUsername.init();
});
