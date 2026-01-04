@php
    if(! isset($page)) {
        $page = null;
    }
@endphp
<!--begin::Header-->
<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">
    <div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
        <div class="d-flex align-items-center d-lg-none ms-n3 me-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-color-gray-600 btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
        </div>
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
            <a>
                <span class="text-decoration-underline text-primary fs-1" style="font-weight: 800">EDUCARE</span>
            </a>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                <div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">

                    
                    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ in_array($page, ['']) ? 'here' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                        <span class="menu-link">
                            <span class="menu-title">Inicio</span>
                        </span>
                    </a>       
                    
                    <a href="{{ route('admin.child.manage') }}" class="menu-item {{ in_array($page, ['']) ? 'here' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                        <span class="menu-link">
                            <span class="menu-title">Gestión de Infantes</span>
                        </span>
                    </a>

                    <a href="{{ route('admin.attendance.report') }}" class="menu-item {{ in_array($page, ['']) ? 'here' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                        <span class="menu-link">
                            <span class="menu-title">Asistencia</span>
                        </span>
                    </a>

                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                         class="menu-item {{ in_array($page, ['']) ? 'here' : '' }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                        <span class="menu-link">
                            <span class="menu-title">Gestión de Centros Infantiles</span>
                            <span class="menu-arrow d-lg-none"></span>
                        </span>
                        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('admin.childcare_center.manage') }}"><span class="menu-title">Centros Infantiles</span></a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('admin.room.manage') }}"><span class="menu-title">Salas/Grupos</span></a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('admin.educator.manage') }}"><span class="menu-title">Educadores</span></a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.incident.manage') }}" class="menu-item {{ in_array($page, ['']) ? 'here' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                        <span class="menu-link">
                            <span class="menu-title">Incidentes</span>
                        </span>
                    </a>

                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                         class="menu-item {{ in_array($page, ['']) ? 'here' : '' }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                        <span class="menu-link">
                            <span class="menu-title">Preferencias</span>
                            <span class="menu-arrow d-lg-none"></span>
                        </span>
                        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                            <div class="menu-item">
                                <a href="{{ route('admin.settings.form') }}" class="menu-link"><span class="menu-title">Configuración General</span></a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('admin.user.list_admin') }}" class="menu-link"><span class="menu-title">Usuarios administradores</span></a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href=""><span class="menu-title">Logs de Auditoría</span></a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="app-navbar flex-shrink-0">
                <div class="app-navbar-item ms-3 ms-lg-5" id="kt_header_user_menu_toggle">
                    <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">

                        <img class="symbol symbol-circle symbol-35px symbol-md-45px" src="{{ asset('/themes/common/media/images/user_blank.png')  }}" alt="Usuario" />

                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">

                                <div class="symbol symbol-50px me-5">
                                    <div class="symbol-label bg-light-info">
                                        <span class="text-primary">{{ strtoupper(substr(Auth::guard('web')->user()->name, 0, 1)) }}</span>
                                    </div>
                                </div>

                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-start flex-column fs-5">
                                        <span>{{ Auth::guard('web')->user()->name }}</span>
                                        <div class="d-block badge badge-info fw-bold fs-8 px-2 py-1">{{ Auth::guard('web')->user()->roles->first()->display_name }}</div>
                                    </div>
                                    <a class="fw-semibold text-muted text-hover-primary fs-7 mt-2">{{ Auth::guard('web')->user()->email }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ route('auth.form_profile') }}" class="menu-link px-5">Mi Perfil</a>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link px-5">Salir</a>
                            <form id="logout-form" action="{{ route('auth.logout_post') }}" method="POST" style="display: none;">@csrf</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Header-->