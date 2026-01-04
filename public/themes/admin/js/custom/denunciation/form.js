"use strict";

var KTFormsDenunciation = function () {

    var submitButton;
    var cancelButton;
    var validator;
    var form;
    var typificationRepeater;
    var linksRepeater;

    var _initDatepicker = function () {

        $('.datepicker_flatpickr').flatpickr({
            dateFormat: "d/m/Y",
        });

    };

    var _handleForm = function () {

        validator = FormValidation.formValidation(
            form,
            {
                // locale: 'es_ES',
                // localization: FormValidation.locales.es_ES,
                fields: {
                    aggressor_type: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    aggressor_subtype: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    aggressor_name: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    aggressor_organization: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    aggressor_job_title: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    violation_typification_options: {
                        validators: {
                            callback: {
                                message: 'Ingrese al menos un tipo, todos los campos son obligatorios',
                                callback: function () {
                                    const rows = $('#kt_violation_typification_options').find('[data-repeater-item]');
                                    if (rows.length === 0) return false;

                                    let allValid = true;

                                    rows.each(function () {
                                        const val1 = $(this).find('[data-kt-violation_type_category-add]').val();
                                        const val2 = $(this).find('[data-kt-violation_type-add]').val();
                                        if (!val1 || !val2) {
                                            allValid = false;
                                            return false; // break
                                        }
                                    });

                                    return allValid;
                                }
                            }
                        }
                    },
                    victim_name: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    victim_organization: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    victim_type: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    victim_gender: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    victim_age_group: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    date_event: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    state: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    description_event: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    circumstance_event: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    circumstance_event_other: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    report_status: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    report_sub_status: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    action_response_state: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    action_unprotect_state: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    action_journalistic_unions: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    action_organization_society: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    source_information: {
                        validators: {
                            notEmpty: {
                                message: 'El campo es obligatorio'
                            }
                        }
                    },
                    source_information_detail: {
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

        $(document).on('change', '[data-kt-violation_type_category-add]', function () {
            validator.revalidateField('violation_typification_options');
        });

        $(document).on('change', '[data-kt-violation_type-add]', function () {
            validator.revalidateField('violation_typification_options');
        });
    }

    const validateAggressorType = function (val) {
        if (val === 'Actor estatal') {
            $('#kt_box_aggressor_subtype').removeClass('d-none').addClass('d-block');
            validator.enableValidator('aggressor_subtype');
        } else {
            $('#kt_box_aggressor_subtype').addClass('d-none').removeClass('d-block');
            validator.disableValidator('aggressor_subtype');
        }
    }

    const validateAggressorIdentified = function (val) {
        if (! val) {
            $('#kt_box_aggressor_identified').removeClass('d-none').addClass('d-block');
            validator.enableValidator('aggressor_name');
            validator.enableValidator('aggressor_organization');
            validator.enableValidator('aggressor_job_title');
        } else {
            $('#kt_box_aggressor_identified').addClass('d-none').removeClass('d-block');
            validator.disableValidator('aggressor_name');
            validator.disableValidator('aggressor_organization');
            validator.disableValidator('aggressor_job_title');
        }
    }

    const validateVictimIdentified = function (val) {
        if (! val) {
            $('#kt_box_victim_identified').removeClass('d-none').addClass('d-block');
            validator.enableValidator('victim_name');
            validator.enableValidator('victim_organization');
        } else {
            $('#kt_box_victim_identified').addClass('d-none').removeClass('d-block');
            validator.disableValidator('victim_name');
            validator.disableValidator('victim_organization');
        }
    }

    const validateCircumstanceEvent = function (val) {
        if (val === 'Otro') {
            $('#kt_box_circumstance_event_other').removeClass('d-none').addClass('d-block');
            validator.enableValidator('circumstance_event_other');
        } else {
            $('#kt_box_circumstance_event_other').addClass('d-none').removeClass('d-block');
            validator.disableValidator('circumstance_event_other');
        }
    }

    const validateSourceInformation = function (val) {
        if (
            val === 'Organización de la Sociedad Civil' ||
            val === 'Gremio periodístico' ||
            val === 'Publicación /nota de prensa'
        ) {
            $('#kt_box_source_information_detail').removeClass('d-none').addClass('d-block');
            validator.enableValidator('source_information_detail');
        } else {
            $('#kt_box_source_information_detail').addClass('d-none').removeClass('d-block');
            validator.disableValidator('source_information_detail');
        }
    }

    const validateReportStatus = function (val) {
        if (val === 'Agresión denunciada formalmente') {
            $('#kt_box_report_sub_status').removeClass('d-none').addClass('d-block');
            validator.enableValidator('report_sub_status');
        } else {
            $('#kt_box_report_sub_status').addClass('d-none').removeClass('d-block');
            validator.disableValidator('report_sub_status');
        }
    }

    const _handleSettings = function () {
        $(document).on('change', 'select[name="aggressor_type"]', function (e) { validateAggressorType($(this).val()); });
        $(document).on('change', 'input[name="aggressor_identified"]', function (e) { validateAggressorIdentified($(this).is(':checked')); });
        $(document).on('change', 'input[name="victim_identified"]', function (e) { validateVictimIdentified($(this).is(':checked')); });
        $(document).on('change', 'select[name="circumstance_event"]', function (e) { validateCircumstanceEvent($(this).val()); });
        $(document).on('change', 'select[name="source_information"]', function (e) { validateSourceInformation($(this).val()); });
        $(document).on('change', 'select[name="report_status"]', function (e) { validateReportStatus($(this).val()); });
    };

    const _initHandleSettings = function () {
        validateAggressorType($('select[name="aggressor_type"]').val());
        validateCircumstanceEvent($('select[name="circumstance_event"]').val());
        validateSourceInformation($('select[name="source_information"]').val());
        const aggressor_identified_val = $('input[name="aggressor_identified"]').val();
        validateAggressorIdentified((aggressor_identified_val === '0' || aggressor_identified_val === ''));
        const victim_identified_val = $('input[name="victim_identified"]').val();
        validateVictimIdentified(victim_identified_val === '1');
        validateReportStatus($('select[name="report_status"]').val());
    }

    const _handleTypificationDropdown = function () {

        $(document).on('change', 'select[data-kt-violation_type_category-add="violation_type_category_option"]', function (e) {

            const repeater_item = $(this).closest('[data-repeater-item]');
            const select_violation_type = repeater_item.find('[data-kt-violation_type-add="violation_type_option"]');
            const category_id = parseInt($(this).val());

            select_violation_type.empty().append('<option value=""></option>');

            const selected_category = VIOLATION_DATA.find(cat => cat.id === category_id);
            if (selected_category && selected_category.items) {
                selected_category.items.forEach(item => {
                    select_violation_type.append(
                        $('<option></option>').val(item.id).text(item.name)
                    );
                });
            }

            if (select_violation_type.hasClass('select2-hidden-accessible')) {
                select_violation_type.trigger('change.select2');
            }
        });

    }

    const _initFormRepeater = () => {

        typificationRepeater = $('#kt_violation_typification_options').repeater({
            initEmpty: false,

            defaultValues: {

            },

            show: function () {
                $(this).slideDown();

                // Init select2 on new repeated items
                // initConditionsSelect2();
                const $row = $(this);
                let $dropdown_1 = $row.find('select[data-kt-violation_type_category-add="violation_type_category_option"]');
                let $dropdown_2 = $row.find('select[data-kt-violation_type-add="violation_type_option"]');

                $dropdown_1.select2({
                    placeholder: $dropdown_1.data('placeholder') || 'Selecciona una opción',
                    minimumResultsForSearch: $dropdown_1.data('hide-search') ? Infinity : 0,
                });
                $dropdown_2.select2({
                    placeholder: $dropdown_2.data('placeholder') || 'Selecciona una opción',
                    minimumResultsForSearch: $dropdown_2.data('hide-search') ? Infinity : 0,
                });
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });


    }

    const _initTypificationDropdowns = function () {

        $(document).find('#kt_violation_typification_options select.form-select').each(function () {
            const $select = $(this);
            if (!$select.hasClass("select2-hidden-accessible")) {
                $select.select2({
                    placeholder: $select.data('placeholder') || 'Selecciona una opción',
                    minimumResultsForSearch: $select.data('hide-search') ? Infinity : 0,
                    // dropdownParent: $select.closest('[data-repeater-item]').length
                    //     ? $select.closest('[data-repeater-item]')
                    //     : $(document.body)
                });
            }
        });

        $(document).find('#kt_violation_typification_options select[data-kt-violation_type_category-add="violation_type_category_option"]').each(function () {

            const repeater_item = $(this).closest('[data-repeater-item]');
            const select_violation_type = repeater_item.find('[data-kt-violation_type-add="violation_type_option"]');
            const category_id = parseInt($(this).val());

            select_violation_type.empty().append('<option value=""></option>');

            const selected_category = VIOLATION_DATA.find(cat => cat.id === category_id);
            if (selected_category && selected_category.items) {
                selected_category.items.forEach(item => {
                    select_violation_type.append(
                        $('<option></option>').val(item.id).text(item.name)
                    );
                });
            }

            const preset_type_value = select_violation_type.data('id');
            if (preset_type_value) {
                select_violation_type.val(preset_type_value);
            }

            if (select_violation_type.hasClass('select2-hidden-accessible')) {
                select_violation_type.trigger('change.select2');
            }

        });

    }

    const _initLinksFormRepeater = () => {
        linksRepeater = $('#kt_links_options').repeater({
            initEmpty: false,
            defaultValues: {},
            show: function () {$(this).slideDown();},
            hide: function (deleteElement) {$(this).slideUp(deleteElement);}
        });
    }

    const _initLinks = () => {
        var defaultUrls = document.querySelector('input[name="links"]');
        if (defaultUrls) {
            var links =JSON.parse(defaultUrls.value);
            var links_list = [];
            if(Array.isArray(links)) {
                links.forEach(elem => {
                    links_list.push({
                        'links_value': elem,
                    });
                });
                linksRepeater.setList(links_list);
            }
        }
    }

    // const initConditionsSelect2 = () => {
    //     // Tnit new repeating condition types
    //     const allConditionTypes = document.querySelectorAll('[data-kt-violation_type_category-add="violation_type_category_option"]');
    //     allConditionTypes.forEach(type => {
    //         if ($(type).hasClass("select2-hidden-accessible")) {
    //             return;
    //         } else {
    //             $(type).select2({
    //                 minimumResultsForSearch: -1
    //             });
    //         }
    //     });
    // }

    return {
        init: function () {
            form = document.querySelector('#kt_denunciation_form');
            submitButton = document.getElementById('kt_denunciation_submit');
            // cancelButton = document.getElementById('kt_denunciation_cancel');
            _initDatepicker();
            _handleForm();
            _initFormRepeater();
            _handleSettings();
            _initHandleSettings();
            _initTypificationDropdowns();
            _handleTypificationDropdown();
            _initLinksFormRepeater();
            _initLinks();
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTFormsDenunciation.init();
});
