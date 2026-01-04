"use strict";

var KTUserShow = function () {
    var userInfoFormValidator;
    var userPasswordFormValidator;

    // Initialize user info form validation
    var initUserInfoFormValidation = function () {
        var fields = {
            name: {
                validators: {
                    notEmpty: {
                        message: 'El nombre es obligatorio'
                    },
                    stringLength: {
                        min: 2,
                        max: 255,
                        message: 'El nombre debe tener entre 2 y 255 caracteres'
                    }
                }
            },
            active: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un estado'
                    }
                }
            }
        };

        // Add childcare_center_id validation if the select exists (user is childcare_admin)
        if (document.getElementById('edit_childcare_center_id')) {
            fields.childcare_center_id = {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un Centro de Cuidado Infantil'
                    }
                }
            };
        }

        userInfoFormValidator = FormValidation.formValidation(
            document.getElementById('kt_modal_update_user_info_form'),
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
    };

    // Initialize user password form validation
    var initUserPasswordFormValidation = function () {
        userPasswordFormValidator = FormValidation.formValidation(
            document.getElementById('kt_modal_update_user_password_form'),
            {
                fields: {
                    new_password: {
                        validators: {
                            notEmpty: {
                                message: 'La nueva contraseña es obligatoria'
                            },
                            stringLength: {
                                min: 8,
                                message: 'La nueva contraseña debe tener al menos 8 caracteres'
                            }
                        }
                    },
                    confirm_password: {
                        validators: {
                            notEmpty: {
                                message: 'La confirmación de contraseña es obligatoria'
                            },
                            identical: {
                                compare: function() {
                                    return document.getElementById('new_password').value;
                                },
                                message: 'La confirmación de contraseña no coincide'
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
    };

    // Handle user info form submission
    var handleUserInfoForm = function () {
        $('#kt_modal_update_user_info_form').on('submit', function (e) {
            e.preventDefault();

            var submitButton = $('#kt_button_update_user_info_submit');
            var form = $(this);

            // Validate form using FormValidation
            if (userInfoFormValidator) {
                userInfoFormValidator.validate().then(function (status) {
                    if (status === 'Valid') {
                        submitButton.prop('disabled', true);
                        submitButton.find('.indicator-label').hide();
                        submitButton.find('.indicator-progress').show();

                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                submitButton.prop('disabled', false);
                                submitButton.find('.indicator-label').show();
                                submitButton.find('.indicator-progress').hide();

                                if (response.success) {
                                    $('#kt_modal_update_user_info').modal('hide');
                                    toastr.success(response.message || 'Información del usuario actualizada exitosamente');

                                    // Reload page to show updated data
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    toastr.error(response.message || 'Error al actualizar la información del usuario');
                                }
                            },
                            error: function (xhr) {
                                submitButton.prop('disabled', false);
                                submitButton.find('.indicator-label').show();
                                submitButton.find('.indicator-progress').hide();

                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    // Handle validation errors
                                    var errors = xhr.responseJSON.errors;
                                    for (var field in errors) {
                                        toastr.error(errors[field][0]);
                                    }
                                } else {
                                    toastr.error('Error al actualizar la información del usuario');
                                }
                            }
                        });
                    }
                });
            }
        });
    };

    // Generate secure password
    var generateSecurePassword = function () {
        var length = 12;
        var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        var password = "";

        // Ensure at least one of each type
        password += "ABCDEFGHIJKLMNOPQRSTUVWXYZ"[Math.floor(Math.random() * 26)];
        password += "abcdefghijklmnopqrstuvwxyz"[Math.floor(Math.random() * 26)];
        password += "0123456789"[Math.floor(Math.random() * 10)];
        password += "!@#$%^&*"[Math.floor(Math.random() * 8)];

        // Fill the rest
        for (var i = 4; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }

        // Shuffle
        password = password.split('').sort(function(){return 0.5-Math.random()}).join('');

        return password;
    };

    // Handle password toggle visibility
    var handlePasswordToggle = function () {
        $('#toggle_password_btn').on('click', function () {
            var passwordInput = $('#new_password');
            var icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('ki-eye').addClass('ki-eye-slash');
                $(this).attr('title', 'Ocultar contraseña');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('ki-eye-slash').addClass('ki-eye');
                $(this).attr('title', 'Mostrar contraseña');
            }
        });
    };

    // Handle password generation
    var handlePasswordGeneration = function () {
        $('#generate_password_btn').on('click', function () {
            var password = generateSecurePassword();
            $('#new_password').val(password);
            $('#confirm_password').val(password);

            // Show password by default when generated
            $('#new_password').attr('type', 'text');
            $('#toggle_password_btn').find('i').removeClass('ki-eye').addClass('ki-eye-slash');
            $('#toggle_password_btn').attr('title', 'Ocultar contraseña');

            // Revalidate the form
            if (userPasswordFormValidator) {
                userPasswordFormValidator.revalidateField('new_password');
                userPasswordFormValidator.revalidateField('confirm_password');
            }

            toastr.success('Contraseña segura generada automáticamente');
        });
    };

    // Handle modal close - reset forms
    var handleModalClose = function () {
        $('#kt_modal_update_user_info, #kt_modal_update_user_password').on('hidden.bs.modal', function () {
            var modalId = $(this).attr('id');

            if (modalId === 'kt_modal_update_user_info') {
                // Reset user info form
                $('#kt_modal_update_user_info_form')[0].reset();
                if (userInfoFormValidator) {
                    userInfoFormValidator.resetForm();
                }
            } else if (modalId === 'kt_modal_update_user_password') {
                // Reset password form
                $('#kt_modal_update_user_password_form')[0].reset();
                if (userPasswordFormValidator) {
                    userPasswordFormValidator.resetForm();
                }

                // Reset password input to hidden state
                $('#new_password').attr('type', 'password');
                $('#toggle_password_btn').find('i').removeClass('ki-eye-slash').addClass('ki-eye');
                $('#toggle_password_btn').attr('title', 'Mostrar contraseña');

                // Reset password meter if exists
                if (typeof KTPasswordMeter !== 'undefined') {
                    KTPasswordMeter.init();
                }
            }
        });
    };

    // Handle user password form submission
    var handleUserPasswordForm = function () {
        $('#kt_modal_update_user_password_form').on('submit', function (e) {
            e.preventDefault();

            var submitButton = $('#kt_button_update_user_password_submit');
            var form = $(this);

            // Validate form using FormValidation
            if (userPasswordFormValidator) {
                userPasswordFormValidator.validate().then(function (status) {
                    if (status === 'Valid') {
                        submitButton.prop('disabled', true);
                        submitButton.find('.indicator-label').hide();
                        submitButton.find('.indicator-progress').show();

                        var formData = new FormData(form[0]);

                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                submitButton.prop('disabled', false);
                                submitButton.find('.indicator-label').show();
                                submitButton.find('.indicator-progress').hide();

                                if (response.success) {
                                    $('#kt_modal_update_user_password').modal('hide');
                                    toastr.success(response.message || 'Contraseña actualizada exitosamente');
                                } else {
                                    toastr.error(response.message || 'Error al actualizar la contraseña');
                                }
                            },
                            error: function (xhr) {
                                submitButton.prop('disabled', false);
                                submitButton.find('.indicator-label').show();
                                submitButton.find('.indicator-progress').hide();

                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    // Handle validation errors
                                    var errors = xhr.responseJSON.errors;
                                    for (var field in errors) {
                                        toastr.error(errors[field][0]);
                                    }
                                } else {
                                    toastr.error('Error al actualizar la contraseña');
                                }
                            }
                        });
                    }
                });
            }
        });
    };

    return {
        init: function () {
            initUserInfoFormValidation();
            initUserPasswordFormValidation();
            handleUserInfoForm();
            handleUserPasswordForm();
            handlePasswordToggle();
            handlePasswordGeneration();
            handleModalClose();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTUserShow.init();
});
