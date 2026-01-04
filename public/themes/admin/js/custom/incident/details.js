"use strict";

var KTIncidentDetail = function () {

    var statusSelect;
    var updateStatusUrl;
    var blockUI;

    var initStatusUpdate = function () {
        statusSelect = document.querySelector('#kt_incident_status_select');
        if (!statusSelect) {
            return;
        }

        updateStatusUrl = statusSelect.dataset.updateUrl;
        var $select = $(statusSelect);
        var isUpdating = false; // Flag to prevent infinite loops

        // Find the card container to block
        var cardContainer = statusSelect.closest('.card');
        if (cardContainer) {
            blockUI = new KTBlockUI(cardContainer, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary" role="status"></span> <span class="ms-2">Actualizando estado...</span></div>',
            });
        }

        $select.on('change', function () {
            // Prevent processing if we're already updating (to avoid loops)
            if (isUpdating) {
                return;
            }

            var newStatus = $(this).val();
            if (!newStatus) {
                return;
            }

            var originalValue = $select.data('original-value');
            
            // If the value hasn't changed, don't do anything
            if (newStatus === originalValue) {
                return;
            }

            // Set flag to prevent re-entry
            isUpdating = true;
            
            // Show loading indicator
            $select.prop('disabled', true);
            blockUI.block();

            $.ajax({
                url: updateStatusUrl,
                type: 'PATCH',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                data: JSON.stringify({
                    status: newStatus
                }),
                success: function (response) {
                    if (response.success) {
                        // Update the status badge
                        var statusBadge = document.querySelector('#kt_incident_status_badge');
                        if (statusBadge) {
                            var statusValue = response.data.status;
                            var statusLabel = response.data.status_label;
                            
                            // Update badge class based on status
                            var badgeClass = 'badge-light-secondary';
                            switch(statusValue) {
                                case 'reportado':
                                    badgeClass = 'badge-light-primary';
                                    break;
                                case 'en_revision':
                                    badgeClass = 'badge-light-warning';
                                    break;
                                case 'cerrado':
                                    badgeClass = 'badge-light-success';
                                    break;
                                case 'escalado':
                                    badgeClass = 'badge-light-danger';
                                    break;
                                case 'archivado':
                                    badgeClass = 'badge-light-secondary';
                                    break;
                            }
                            
                            statusBadge.className = 'badge badge-lg ' + badgeClass + ' d-block';
                            statusBadge.textContent = statusLabel;
                        }

                        // Update status in the main content area
                        var statusBadgeContent = document.querySelector('#kt_incident_status_badge_content');
                        if (statusBadgeContent) {
                            var badgeClassContent = 'badge-light-secondary';
                            switch(statusValue) {
                                case 'reportado':
                                    badgeClassContent = 'badge-light-primary';
                                    break;
                                case 'en_revision':
                                    badgeClassContent = 'badge-light-warning';
                                    break;
                                case 'cerrado':
                                    badgeClassContent = 'badge-light-success';
                                    break;
                                case 'escalado':
                                    badgeClassContent = 'badge-light-danger';
                                    break;
                                case 'archivado':
                                    badgeClassContent = 'badge-light-secondary';
                                    break;
                            }
                            statusBadgeContent.className = 'badge ' + badgeClassContent + ' fs-7 fw-bold';
                            statusBadgeContent.textContent = statusLabel;
                        }

                        // Update original value to the new value
                        $select.data('original-value', newStatus);

                        // Show success message
                        Swal.fire({
                            text: response.message || 'Estado actualizado correctamente',
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    } else {
                        // Revert select value without triggering change event
                        $select.val(originalValue);
                        if ($select.hasClass('select2-hidden-accessible')) {
                            $select.trigger('change.select2');
                        }
                        
                        Swal.fire({
                            text: response.message || 'Error al actualizar el estado',
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                },
                error: function (xhr) {
                    // Revert select value without triggering change event
                    $select.val(originalValue);
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.trigger('change.select2');
                    }
                    
                    var errorMessage = 'Error al actualizar el estado';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        text: errorMessage,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                },
                complete: function () {
                    $select.prop('disabled', false);
                    blockUI.release();
                    isUpdating = false; // Reset flag
                }
            });
        });

        // Store original value
        $(statusSelect).data('original-value', $(statusSelect).val());
    }

    return {
        init: function () {
            initStatusUpdate();
        }
    }
}();


KTUtil.onDOMContentLoaded(function () {
    KTIncidentDetail.init();
});

