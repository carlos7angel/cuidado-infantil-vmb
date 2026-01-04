@extends('vendor@template::admin.layouts.master', ['page' => 'index'])

@section('breadcrumbs')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-hover-secondary">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">Usuarios Administradores</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            USUARIOS ADMINISTRADORES
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">{{ $page_title }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
        
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="card mb-7 border-0 shadow-none">
            <div class="card-body p-0">
                <div class="d-flex align-items-center">
                    <div class="position-relative w-md-400px me-md-2">
                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-sm form-control-solid ps-10" name="dt_search_input" value="" placeholder="Buscar por nombre, correo o rol" />
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="submit" id="kt_search" class="btn btn-secondary btn-sm fs-8 me-2">Buscar</button>
                        <button type="button" id="kt_reset" class="btn btn-light-secondary btn-sm fs-8 me-5">Limpiar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap flex-stack pb-7">
            <div class="d-flex flex-wrap align-items-center my-1">
                <h3 class="fw-bold me-5 my-1">Todos los Usuarios Administradores
                    <span class="text-gray-500 fs-6 ms-2"></span>
                </h3>
            </div>
            <div class="d-flex flex-wrap my-1">
                <a href="javascript:void(0)" class="btn btn-primary" id="kt_add_user"><i class="ki-outline ki-file-added fs-3 me-1"></i>Nuevo Registro</a>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_admin_users"
                       data-url="{{ route('admin.user.list_admin_json_dt') }}" aria-describedby="table" style="width: 100%;">
                    <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="text-start min-w-300px">Usuario</th>
                        <th class="text-center min-w-200px">Rol</th>
                        <th class="text-center min-w-150px">Estado</th>
                        <th class="text-center min-w-150px">Fecha de Registro</th>
                        <th class="text-end min-w-70px">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@section('styles')
    <link href="{{ asset('themes/common/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script>
        var userShowUrl = '{{ route("admin.user.show", ":id") }}';
    </script>
    <script src="{{ asset('themes/common/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('themes/admin/js/custom/users/list.js') }}"></script>
@endsection

@section('modals')
<div class="modal fade" id="kt_modal_new_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bold">Nuevo Usuario Administrador</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y__ my-7 px-5 px-lg-10 pt-0 pb-15">
                <form id="kt_form_new_user" class="form" action="{{ route('admin.user.store') }}"
                      method="post" autocomplete="off">
                    @csrf
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll"
                         data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                         data-kt-scroll-dependencies="#kt_modal_add_user_header"
                         data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Nombre:</label>
                            <input type="text" name="name" class="form-control mb-3 mb-lg-0" placeholder="Ingrese el nombre completo" />
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Correo Electrónico:</label>
                            <input type="email" name="email" class="form-control mb-3 mb-lg-0" placeholder="usuario@dominio.com"/>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Contraseña Generada:</label>
                            <div class="input-group">
                                <input type="text" id="generated_password" class="form-control" readonly />
                                <button type="button" class="btn btn-outline-secondary" id="copy_password" title="Copiar contraseña">
                                    <i class="ki-duotone ki-copy fs-2"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="regenerate_password" title="Generar nueva contraseña">
                                    <i class="ki-duotone ki-arrows-circle fs-2"></i>
                                </button>
                            </div>
                            <div class="form-text">Se generará una contraseña segura automáticamente</div>
                            <!-- Hidden field to send password to backend -->
                            <input type="hidden" name="password" id="password" />
                        </div>

                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-5">Rol:</label>
                            <div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input me-3" name="user_role" type="radio"
                                           value="municipal_admin" id="kt_modal_update_role_option_municipal" checked />
                                    <label class="form-check-label" for="kt_modal_update_role_option_municipal">
                                        <div class="fw-bold text-gray-800">Usuario administrador Municipio</div>
                                        <div class="text-gray-600">
                                            Usuario administrador a nivel de Municipio con permisos de acceso a todo el Sistema
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class='separator separator-dashed my-5'></div>
                            <div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input me-3" name="user_role" type="radio"
                                           value="childcare_admin" id="kt_modal_update_role_option_childcare" />
                                    <label class="form-check-label" for="kt_modal_update_role_option_childcare">
                                        <div class="fw-bold text-gray-800">Usuario administrador CCI</div>
                                        <div class="text-gray-600">
                                            Usuario administrador a nivel de un Centro de Cuidado Infantil con permisos de acceso solo a información de los niños y niñas del Centro de Cuidado Infantil
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class='separator separator-dashed my-5'></div>
                        </div>

                        <div id="kt_childcare_center_select" class="d-none">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Centro de Cuidado Infantil:</label>
                                <span class="text-gray-600">Seleccione el Centro de Cuidado Infantil al que pertenece el usuario (Solo para usuarios con rol de administrador CCI)</span>
                                <select class="form-select" data-control="select2" data-hide-search="false"
                                        data-placeholder="Seleccionar" name="childcare_center_id">
                                    <option value=""></option>
                                    @foreach($childcare_centers as $center)
                                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="send_email" id="send_email_checkbox" />
                                <label class="form-check-label fw-semibold fs-6" for="send_email_checkbox">
                                    Enviar correo electrónico con los datos de acceso
                                </label>
                            </div>
                            <div class="form-text">Se enviará un correo al usuario con su nombre de usuario y contraseña</div>
                        </div>

                    </div>
                    <div class="text-center pt-10">
                        <button type="reset" id="kt_button_new_user_cancel" class="btn btn-light me-3">
                            Cancelar
                        </button>
                        <button type="submit" id="kt_button_new_user_submit" class="btn btn-primary">
                            <span class="indicator-label">Crear Usuario</span>
                            <span class="indicator-progress">Creando usuario...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
