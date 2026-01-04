@extends('vendor@template::admin.layouts.master', ['page' => 'index'])

@section('breadcrumbs')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="" class="text-white text-hover-secondary">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">Grupos/Salas</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            GRUPOS/SALAS
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Gestión de Grupos y Salas</span>
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
                        <input type="text" class="form-control form-control-sm form-control-solid ps-10" name="dt_search_input" value="" placeholder="Buscador" />
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="submit" id="kt_search" class="btn btn-secondary btn-sm fs-8 me-2">Buscar</button>
                        <button type="button" id="kt_reset" class="btn btn-light-secondary btn-sm fs-8 me-5">Limpiar</button>
                        <a href="javascript:void(0)" id="kt_advanced_search" class="btn btn-link fs-7 link-info fw-bold" data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">Búsqueda avanzada</a>
                    </div>
                </div>
                <div class="collapse" id="kt_advanced_search_form">
                    <div class="separator separator-dashed mt-5 mb-4"></div>
                    <div class="row g-8 mb-5">
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Nombre</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="1" name="kt_search_name" />
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Centro de Cuidado Infantil</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="2" name="kt_search_childcare_center" />
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Estado</label>
                            <select class="form-select form-select-sm form-select-solid datatable-input" data-col-index="3" name="kt_search_is_active">
                                <option value="">Todos</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap flex-stack pb-7">
            <div class="d-flex flex-wrap align-items-center my-1">
                <h3 class="fw-bold me-5 my-1">Todos los Grupos/Salas
                    <span class="text-gray-500 fs-6 ms-2"></span>
                </h3>
            </div>
            <div class="d-flex flex-wrap my-1">
                <a href="{{ route('admin.room.form_create') }}" class="btn btn-primary"><i class="ki-outline ki-file-added fs-3 me-1"></i>Nuevo Registro</a>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_rooms"
                       data-url="{{ route('admin.room.list_json_dt') }}" aria-describedby="table">
                    <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="text-start min-w-150px" name="name">Nombre</th>
                        <th class="text-center min-w-200px" name="childcare_center_id">Centro de Cuidado Infantil</th>
                        <th class="text-center min-w-100px" name="is_active">Estado</th>
                        <th class="text-center min-w-100px" name="capacity">Capacidad</th>
                        <th class="text-center min-w-150px">Descripción</th>
                        <th class="text-center min-w-100px" name="created_at">Fecha de Registro</th>
                        <th class="text-end min-w-70px">Opciones</th>
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
    <script src="{{ asset('themes/common/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('themes/admin/js/custom/room/list.js') }}"></script>
@endsection

