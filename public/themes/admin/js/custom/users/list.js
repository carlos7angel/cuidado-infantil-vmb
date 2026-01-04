"use strict";

var KTUsersList = function () {

    var table = document.getElementById('kt_table_admin_users');
    var datatable;
    var userFormValidator;

    var initTable = function () {
        datatable = $(table).DataTable({
            responsive: {details: {type: 'column'}},
            searchDelay: 500,
            processing: true,
            serverSide: true,
            filter: true,
            sortable: true,
            pagingType: 'full_numbers',
            pagination: true,
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            layout: {scroll: false, footer: false,},
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
            ajax: {
                url: table.dataset.url,
                type: 'POST',
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: function (d) {
                    // Add search value from input
                    var searchInput = $('input[name="dt_search_input"]').val();
                    if (searchInput) {
                        d.search = {
                            value: searchInput
                        };
                    } else {
                        d.search = {
                            value: ''
                        };
                    }
                },
            },
            data: null,
            columns: [
                {data: null, name: "id"},
                {data: 'user_info', name: "user_info"},
                {data: 'role_display', name: "role_display"},
                {data: 'status', name: "status"},
                {data: 'created_at', name: "created_at"},
                {data: null, responsivePriority: -1},
            ],

            // Order settings
            order: [
                [4, 'desc']
            ],

            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    className: 'dtr-control',
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    },
                },
                {
                    targets: 1,
                    orderable: false,
                    searchable: true,
                    className: 'text-start pe-0',
                    render: function (data, type, full, meta) {
                        if (type === 'display') {
                            return `
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-circle symbol-40px me-3">
                                        <img src="${data.avatar}" alt="Avatar" class="symbol-label" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold mb-1 fs-7">${data.name}</span>
                                        <span class="text-muted fw-semibold d-block fs-8">${data.email}</span>
                                    </div>
                                </div>
                            `;
                        }
                        return data.name + ' ' + data.email;
                    }
                },
                {
                    targets: 2,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<div class="text-gray-900 fw-semibold d-block mb-1 fs-7">${data || '-'}</div>`;
                    },
                },
                {
                    targets: 3,
                    orderable: true,
                    searchable: true,
                    className: 'text-center pe-0',
                    render: function (data, type, full, meta) {
                        var badgeClass = data === 'Activo' ? 'badge-success' : 'badge-danger';
                        return `<span class="badge ${badgeClass} fs-8">${data}</span>`;
                    },
                },
                {
                    targets: 4,
                    orderable: true,
                    searchable: true,
                    className: 'dt-center pe-0',
                    render: function (data, type, full, meta) {
                        return `<span class="text-muted fw-semibold text-muted d-block fs-7">${data || '-'}</span>`;
                    },
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function (data, type, full, meta) {
                        var toShowUrl = userShowUrl.replace(':id', full.id);
                        return `<a href="${toShowUrl}" class="btn btn-sm btn-icon btn-secondary" title="Ver Detalle">
                                    <i class="ki-outline ki-eye text-gray-600 fs-2"></i>
                                </a>`;
                    },
                },
            ],
        });
    }

    $('#kt_search').on('click', function (e) {
        e.preventDefault();

        // Set search value and reload
        let search = $("input[name='dt_search_input']").val();
        datatable.search(search ? search : '', false, false);
        datatable.ajax.reload();
    });

    $('#kt_reset').on('click', function (e) {
        e.preventDefault();

        // Clear search input
        $("input[name='dt_search_input']").val('');

        // Clear DataTable search
        datatable.search('', false, false);

        // Clear any datatable-input fields (if they exist)
        $('.datatable-input').each(function () {
            if ($(this).is('select')) {
                $(this).val('');
            } else {
                $(this).val('');
            }
            if ($(this).data('col-index') !== undefined) {
                datatable.column($(this).data('col-index')).search('', false, false);
            }
        });

        // Reload table data
        datatable.ajax.reload();
    });

    // Handle Enter key in search input
    $('input[name="dt_search_input"]').on('keypress', function (e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            let search = $(this).val();
            datatable.search(search ? search : '', false, false);
            datatable.ajax.reload();
        }
    });

    var initUserModal = function () {
        // Initialize form validation
        initUserFormValidation();

        // Generate initial password
        generatePassword();

        // Handle role change
        $('input[name="user_role"]').on('change', function () {
            var selectedRole = $(this).val();
            if (selectedRole === 'childcare_admin') {
                $('#kt_childcare_center_select').removeClass('d-none').addClass('d-block');
                $('#kt_form_new_user select[name="childcare_center_id"]').prop('disabled', false);
                // Enable validation for childcare_center_id
                if (userFormValidator) {
                    userFormValidator.enableValidator('childcare_center_id');
                }
            } else {
                $('#kt_childcare_center_select').removeClass('d-block').addClass('d-none');
                // Clear and disable the childcare center selection for municipal_admin
                $('#kt_form_new_user select[name="childcare_center_id"]').val('').prop('disabled', true).trigger('change.select2');
                // Disable validation for childcare_center_id
                if (userFormValidator) {
                    userFormValidator.disableValidator('childcare_center_id');
                }
            }
        });

        // Handle copy password
        $('#copy_password').on('click', function () {
            var password = $('#generated_password').val();
            navigator.clipboard.writeText(password).then(function() {
                toastr.success('Contraseña copiada al portapapeles');
            });
        });

        // Handle regenerate password
        $('#regenerate_password').on('click', function () {
            generatePassword();
        });

        // Handle modal show
        $('#kt_add_user').on('click', function () {
            // Reset form
            $('#kt_form_new_user')[0].reset();
            // Generate new password
            generatePassword();
            // Reset role selection and hide/disable childcare center select
            $('#kt_form_new_user input[name="user_role"][value="municipal_admin"]').prop('checked', true);
            $('#kt_childcare_center_select').removeClass('d-block').addClass('d-none');
            $('#kt_form_new_user select[name="childcare_center_id"]').prop('disabled', true).val('').trigger('change.select2');
            // Disable validation for childcare_center_id (default municipal_admin role)
            if (userFormValidator) {
                userFormValidator.disableValidator('childcare_center_id');
            }
            // Show modal
            $('#kt_modal_new_user').modal('show');
        });

        // Handle modal close
        $('#kt_modal_new_user').on('hidden.bs.modal', function () {
            $('#kt_form_new_user')[0].reset();
        });

        // Handle form submission
        $('#kt_form_new_user').on('submit', function (e) {
            e.preventDefault();

            var submitButton = $('#kt_button_new_user_submit');
            var form = $(this);

            // Validate form using FormValidation
            if (userFormValidator) {
                userFormValidator.validate().then(function (status) {
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
                                if (response.success) {
                                    $('#kt_modal_new_user').modal('hide');

                                    // Show password in alert
                                    Swal.fire({
                                        title: 'Usuario creado exitosamente',
                                        html: `
                                            <p>El usuario ha sido creado con la siguiente información:</p>
                                            <strong>Contraseña:</strong> <code>${response.password}</code><br>
                                            <small class="text-muted">Guarde esta contraseña en un lugar seguro</small>
                                        `,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar',
                                        allowOutsideClick: false
                                    }).then(function() {
                                        // Reload table
                                        datatable.table().draw();
                                    });
                                } else {
                                    toastr.error(response.message || 'Error al crear el usuario');
                                }
                            },
                            error: function (xhr) {
                                var errorMessage = 'Error al crear el usuario';
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    var errors = xhr.responseJSON.errors;
                                    var firstError = Object.keys(errors)[0];
                                    errorMessage = errors[firstError][0];
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                toastr.error(errorMessage);
                            },
                            complete: function () {
                                submitButton.prop('disabled', false);
                                submitButton.find('.indicator-label').show();
                                submitButton.find('.indicator-progress').hide();
                            }
                        });
                    } else {
                        toastr.warning('Complete correctamente todos los campos requeridos', 'Validación');
                    }
                });
            }
        });
    }

    var generatePassword = function () {
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

        $('#generated_password').val(password);
        $('#password').val(password); // Update hidden field for form submission
    }

    var initUserFormValidation = function () {
        userFormValidator = FormValidation.formValidation(
            document.getElementById('kt_form_new_user'),
            {
                fields: {
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
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'El correo electrónico es obligatorio'
                            },
                            emailAddress: {
                                message: 'Ingrese un correo electrónico válido'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'La contraseña es obligatoria'
                            },
                            stringLength: {
                                min: 8,
                                message: 'La contraseña debe tener al menos 8 caracteres'
                            }
                        }
                    },
                    user_role: {
                        validators: {
                            notEmpty: {
                                message: 'Debe seleccionar un rol'
                            }
                        }
                    },
                    childcare_center_id: {
                        validators: {
                            notEmpty: {
                                message: 'Debe seleccionar un Centro de Cuidado Infantil',
                                enabled: false // Initially disabled for municipal_admin
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
    }


    return {
        init: function () {
            if (!table) {
                return;
            }
            initTable();
            initUserModal();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTUsersList.init();
});

