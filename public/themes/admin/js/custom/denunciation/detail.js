"use strict";

var KTDenunciationDetail = function () {

    var modalEl;
    var modal;
    var submitButton;
    var cancelButton;
    var validator;
    var form;
    var blockUI;

    var initForm = function () {

        validator = FormValidation.formValidation(
            form,
            {
                // locale: 'es_ES',
                // localization: FormValidation.locales.es_ES,
                fields: {
                    denunciation_observations: {
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
                                        text: "Registro actualizado satisfactoriamente",
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

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();
            modal.hide();
        });
    }

    var _handleUpdateStatusModal = function () {

        $(document).on('click', '.kt_change_denunciation_status', function (e) {
            e.preventDefault();
            form.reset();
            validator.resetForm(true);
            var new_status = $(this).data('new-status');
            var new_status_label = $(this).data('new-status-label');
            $('input[name="denunciation_status"]').val(new_status);
            $('input[name="readonly_denunciation_status"]').val(new_status_label);
            modal.show();
        });

    }

    return {
        init: function () {
            modalEl = document.querySelector('#kt_modal_update_denunciation_status');
            if (!modalEl) { return; }
            modal = new bootstrap.Modal(modalEl);

            form = document.querySelector('#kt_form_update_status_denunciation');
            submitButton = document.getElementById('kt_button_update_status_submit');
            cancelButton = document.getElementById('kt_button_update_status_cancel');

            initForm();
            _handleUpdateStatusModal();
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTDenunciationDetail.init();
});
