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
            <a href="{{ route('admin.user.list_admin') }}" class="text-white text-hover-secondary">Usuarios Administradores</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">{{ $user->name }}</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            DETALLE DEL USUARIO
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">{{ $page_title }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
        <a href="{{ route('admin.user.list_admin') }}" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"></i>
            Volver al Listado
        </a>
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="d-flex flex-column flex-xl-row flex-row-fluid gap-10">

            <div class="d-flex flex-column justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px w-xxl-350px">

                <div class="card border border-dashed border-gray-300 mb-7">
                    <div class="card-body">
                        <div class="d-flex flex-center flex-column py-5">
                            <div class="symbol symbol-75px symbol-circle mb-7">
                                <img src="{{ asset('themes/common/media/images/blank-user.jpg') }}" alt="Usuario" />
                            </div>
                            <a class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ $user->name }}</a>
                            <div class="mb-9">
                                <span class="text-muted">Rol:</span> <div class="badge badge-lg badge-light-primary d-inline">{{ $user->roles->first()->display_name }}</div>
                            </div>

                            <div class="py-5__ fs-6 text-center w-100">
                                <div class="fw-bold mt-5">Estado:</div>
                                <div>
                                    @if($user->active)
                                        <div class="badge badge-lg badge-success d-inline">Activo</div>
                                    @else
                                        <div class="badge badge-lg badge-danger d-inline">Inactivo</div>
                                    @endif
                                </div>
                                <div class="fw-bold mt-5">Correo:</div>
                                <div class="text-gray-600">{{ $user->email }}</div>
                                <div class="fw-bold mt-5">Alta:</div>
                                <div class="text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('d/m/Y H:i A') }}</div>
                                @if($user->last_login_at)
                                    <div class="fw-bold mt-5">Último Acceso:</div>
                                    <div class="text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login_at)->format('d/m/Y H:i A') }}</div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="w-100">

                <div class="flex-column">


                    <div class="d-flex justify-content-end mb-5 gap-4">
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_user_info">
                            <i class="ki-outline ki-pencil fs-2"></i>
                            Editar Información
                        </a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#kt_modal_update_user_password">
                            <i class="ki-outline ki-lock fs-2"></i>
                            Cambiar Contraseña
                        </a>
                    </div>

                    <div class="card d-flex flex-row-fluid mb-10">

                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Información del Usuario</h2>
                            </div>
                        </div>

                        <div class="card-body p-12 w-100">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session" aria-describedby="table">
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                    <tr>
                                        <td class="w-200px">Nombre completo</td>
                                        <td>{{ $user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Correo electrónico</td>
                                        <td>{{ $user->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Rol</td>
                                        <td>{{ $user->roles->first()->display_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Estado</td>
                                        <td>
                                            @if($user->active)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Centro de Cuidado</td>
                                        <td>{{ $user->childcareCenter->name ?? 'No asignado' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fecha de registro</td>
                                        <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i A') : 'N/A' }}</td>
                                    </tr>
                                    @if($user->last_login_at)
                                    <tr>
                                        <td>Último acceso</td>
                                        <td>{{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i A') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>Intentos de login</td>
                                        <td>{{ $user->login_attempt ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tipo de usuario</td>
                                        <td>{{ $user->is_client ? 'Cliente' : 'Administrador' }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- Edit User Info Modal -->
<div class="modal fade" id="kt_modal_update_user_info" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Editar Información del Usuario</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form id="kt_modal_update_user_info_form" class="form" method="POST" action="{{ route('admin.user.update_info', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Nombre Completo:</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" />
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Estado:</label>
                        <div class="d-flex">
                            <div class="form-check form-check-custom form-check-solid me-5">
                                <input class="form-check-input" type="radio" name="active" value="1" id="edit_active" {{ $user->active ? 'checked' : '' }} />
                                <label class="form-check-label" for="edit_active">
                                    Activo
                                </label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="radio" name="active" value="0" id="edit_inactive" {{ !$user->active ? 'checked' : '' }} />
                                <label class="form-check-label" for="edit_inactive">
                                    Inactivo
                                </label>
                            </div>
                        </div>
                    </div>

                    @if($user->roles->first()->name === 'childcare_admin')
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Centro de Cuidado Infantil:</label>
                        <select class="form-select" data-control="select2" data-hide-search="false"
                                data-placeholder="Seleccionar" name="childcare_center_id" id="edit_childcare_center_id">
                            <option value=""></option>
                            @foreach($childcare_centers ?? [] as $center)
                                <option value="{{ $center->id }}" {{ $user->childcare_center_id == $center->id ? 'selected' : '' }}>
                                    {{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" id="kt_button_update_user_info_submit" class="btn btn-primary">
                            <span class="indicator-label">Actualizar Información</span>
                            <span class="indicator-progress">Actualizando...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="kt_modal_update_user_password" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Cambiar Contraseña</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form id="kt_modal_update_user_password_form" class="form" method="POST" action="{{ route('admin.user.update_password', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info d-flex align-items-center p-5 mb-7">
                        <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                        <div class="d-flex flex-column">
                            <h5 class="mb-1">Reset de Contraseña</h5>
                            <span>Como administrador, puede generar una nueva contraseña segura para este usuario.</span>
                        </div>
                    </div>

                    <div class="fv-row mb-7" data-kt-password-meter="true">
                        <label class="required fw-semibold fs-6 mb-2">Nueva Contraseña:</label>
                        <div class="input-group">
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Nueva contraseña segura" />
                            <button type="button" class="btn btn-outline-secondary" id="toggle_password_btn" title="Mostrar/Ocultar contraseña">
                                <i class="ki-outline ki-eye fs-2"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="generate_password_btn" title="Generar contraseña segura">
                                <i class="ki-outline ki-arrows-circle fs-2"></i>
                            </button>
                        </div>
                        <div class="form-text">La contraseña debe tener al menos 8 caracteres. Use el botón para generar una automáticamente.</div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Confirmar Nueva Contraseña:</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirme la nueva contraseña" />
                    </div>

                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" id="kt_button_update_user_password_submit" class="btn btn-primary">
                            <span class="indicator-label">Cambiar Contraseña</span>
                            <span class="indicator-progress">Cambiando...
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

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('themes/admin/js/custom/users/show.js') }}"></script>
@endsection

