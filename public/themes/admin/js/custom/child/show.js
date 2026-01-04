var KTChildShow = function () {
    var modalEl = null;
    var modal = null;
    var modalContent = null;

    var initFamilyMemberModal = function () {
        modalEl = document.getElementById('kt_modal_family_member');
        modalContent = document.getElementById('kt_modal_family_member_content');
        
        if (!modalEl || !modalContent) {
            return;
        }

        modal = new bootstrap.Modal(modalEl);
    };

    var clickViewMember = function () {
        $(document).on('click', '.view-member-btn', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var memberData = JSON.parse($(this).attr('data-member'));
            showMemberDetails(memberData);
        });
    };

    var clickMemberRow = function () {
        $(document).on('click', '.member-row', function (e) {
            if (!$(e.target).closest('.view-member-btn').length) {
                var btn = $(this).find('.view-member-btn');
                if (btn.length) {
                    e.preventDefault();
                    var memberData = JSON.parse(btn.attr('data-member'));
                    showMemberDetails(memberData);
                }
            }
        });
    };

    var showMemberDetails = function(member) {
        if (!modal || !modalContent) {
            return;
        }

        const formatCurrency = function(value) {
            if (!value) return '-';
            return 'Bs. ' + parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        };

        modalContent.innerHTML = `
            <div class="m-0 pb-5">
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Nombre(s):</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.first_name || '-'}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Apellido(s):</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.last_name || '-'}</div>
                    </div>
                </div>
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Fecha de Nacimiento:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.birth_date || '-'}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Edad:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.age ? member.age + ' años' : '-'}</div>
                    </div>
                </div>
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Género:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.gender || '-'}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Parentesco:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.kinship || '-'}</div>
                    </div>
                </div>
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Estado Civil:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.marital_status || '-'}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Nivel de Educación:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.education_level || '-'}</div>
                    </div>
                </div>
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Profesión:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.profession || '-'}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Teléfono:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.phone || '-'}</div>
                    </div>
                </div>
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Tiene Ingresos:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.has_income ? 'Sí' : 'No'}</div>
                    </div>
                </div>
                ${member.has_income ? `
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Lugar de Trabajo:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.workplace || '-'}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Tipo de Ingreso:</div>
                        <div class="fw-bold fs-6 text-gray-800">${member.income_type || '-'}</div>
                    </div>
                </div>
                <div class="row g-5 mb-8">
                    <div class="col-sm-6">
                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Ingreso Total:</div>
                        <div class="fw-bold fs-6 text-gray-800">${formatCurrency(member.total_income)}</div>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        
        modal.show();
    };
    
    return {
        init: function () {
            initFamilyMemberModal();
            clickViewMember();
            clickMemberRow();
        },
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTChildShow.init();
});

