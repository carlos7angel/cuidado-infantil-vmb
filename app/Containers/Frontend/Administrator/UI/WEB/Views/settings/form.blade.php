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
            <a class="text-white text-hover-secondary">Ajustes de Configuración</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            INICIO
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Dashboard</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="row gy-5 gx-xl-10">
            <div class="col-12 mb-7">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column ">
                        
                        <form id="kt_settings_form" class="form" action="{{ route('admin.settings.store') }}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="row mb-7">
                                <div class="col-md-9 offset-md-3">
                                    <h2>Configuración General</h2>
                                </div>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Input group - Sistema Nombre-->
                            <div class="row fv-row mb-7">
                                <div class="col-md-3 text-md-end">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Nombre del Sistema</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Nombre del sistema de monitoreo.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--end::Label-->
                                </div>
                                <div class="col-md-9">
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="sistema_nombre" value="{{ old('sistema_nombre', $settings['sistema_nombre'] ?? '') }}" />
                                    <!--end::Input-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Heading-->
                            <div class="row mb-7">
                                <div class="col-md-9 offset-md-3">
                                    <h2>Información del Municipio</h2>
                                </div>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Input group - Municipio-->
                            <div class="row fv-row mb-7">
                                <div class="col-md-3 text-md-end">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Municipio</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Nombre del municipio.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--end::Label-->
                                </div>
                                <div class="col-md-9">
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="servidor_municipio" value="{{ old('servidor_municipio', $settings['servidor_municipio'] ?? '') }}" />
                                    <!--end::Input-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group - Departamento-->
                            <div class="row fv-row mb-7">
                                <div class="col-md-3 text-md-end">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Departamento</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Seleccione el departamento.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--end::Label-->
                                </div>
                                <div class="col-md-9">
                                    <div class="w-100">
                                        <!--begin::Select2-->
                                        @php
                                            $departamentos = [
                                                'La Paz' => 'La Paz',
                                                'Cochabamba' => 'Cochabamba',
                                                'Santa Cruz' => 'Santa Cruz',
                                                'Chuquisaca' => 'Chuquisaca',
                                                'Oruro' => 'Oruro',
                                                'Potosí' => 'Potosí',
                                                'Tarija' => 'Tarija',
                                                'Beni' => 'Beni',
                                                'Pando' => 'Pando',
                                            ];
                                            $selectedDept = old('servidor_departamento', $settings['servidor_departamento'] ?? '');
                                        @endphp
                                        <select class="form-select form-select-solid" name="servidor_departamento" data-control="select2" data-hide-search="false" data-placeholder="Seleccione un departamento">
                                            <option></option>
                                            @foreach($departamentos as $value => $label)
                                                <option value="{{ $value }}" {{ $selectedDept == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!--end::Select2-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Heading-->
                            <div class="row mb-7">
                                <div class="col-md-9 offset-md-3">
                                    <h2>Información de la Organización</h2>
                                </div>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Input group - Organización-->
                            <div class="row fv-row mb-7">
                                <div class="col-md-3 text-md-end">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Nombre de la Organización</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Nombre de la organización.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--end::Label-->
                                </div>
                                <div class="col-md-9">
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="organizacion_nombre" value="{{ old('organizacion_nombre', $settings['organizacion_nombre'] ?? '') }}" />
                                    <!--end::Input-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Heading-->
                            <div class="row mb-7">
                                <div class="col-md-9 offset-md-3">
                                    <h2>Configuración del Servidor/API</h2>
                                </div>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Input group - Estado del Servidor-->
                            <div class="row fv-row mb-7">
                                <div class="col-md-3 text-md-end">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Estado del Servidor</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Seleccione el estado del servidor.">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--end::Label-->
                                </div>
                                <div class="col-md-9">
                                    <div class="w-100">
                                        <!--begin::Select2-->
                                        @php
                                            $estadosServidor = [
                                                'active' => 'Activo',
                                                'maintenance' => 'Mantenimiento',
                                                'inactive' => 'Inactivo',
                                                'development' => 'Desarrollo',
                                                'testing' => 'Pruebas',
                                            ];
                                            $selectedEstado = old('servidor_estado', $settings['servidor_estado'] ?? 'active');
                                        @endphp
                                        <select class="form-select form-select-solid" name="servidor_estado" data-control="select2" data-hide-search="true" data-placeholder="Seleccione un estado">
                                            <option></option>
                                            @foreach($estadosServidor as $value => $label)
                                                <option value="{{ $value }}" {{ $selectedEstado == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!--end::Select2-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group - Versión API-->
                            <div class="row fv-row mb-7">
                                <div class="col-md-3 text-md-end">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Versión de la API</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Versión de la API (ej: v1).">
                                            <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                        </span>
                                    </label>
                                    <!--end::Label-->
                                </div>
                                <div class="col-md-9">
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="version_api" value="{{ old('version_api', $settings['version_api'] ?? '') }}" />
                                    <!--end::Input-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Action buttons-->
                            <div class="row py-5">
                                <div class="col-md-9 offset-md-3">
                                    <div class="d-flex">
                                        <!--begin::Button-->
                                        <button type="reset" class="btn btn-light me-3">Cancelar</button>
                                        <!--end::Button-->
                                        <!--begin::Button-->
                                        <button type="submit" id="kt_settings_submit" class="btn btn-primary">
                                            <span class="indicator-label">Guardar</span>
                                            <span class="indicator-progress">Espere por favor... 
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                        <!--end::Button-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Action buttons-->
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('themes/admin/js/custom/settings/form.js') }}"></script>
@endsection
