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
            <a class="text-white text-hover-secondary">Infantes Inscritos</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            INFANTES INSCRITOS
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Gestión de Infantes Inscritos</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        {{-- Dropdown de filtro por centro (arriba, separado) --}}
        <div class="card mb-7 border-0 shadow-none">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <label class="form-label text-nowrap fw-bold text-gray-700 me-3 mb-0">Filtrar por Centro:</label>
                        <select class="form-select form-select-solid w-250px" id="kt_filter_childcare_center" data-control="select2" data-hide-search="false" data-allow-clear="true" data-placeholder="Todos los centros">
                            <option value="">Todos los centros</option>
                            @foreach($childcare_centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

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
                            <label class="fs-6 form-label fw-bold text-gray-900">Nombre del Infante</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="1" name="kt_search_name" />
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Estado de Inscripción</label>
                            <select class="form-select form-select-sm form-select-solid datatable-input" data-col-index="6" name="kt_search_status">
                                <option value="">Todos</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="egresado">Egresado</option>
                                <option value="trasladado">Trasladado</option>
                                <option value="retirado">Retirado</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Departamento</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="4" name="kt_search_state" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap flex-stack pb-7">
            <div class="d-flex flex-wrap align-items-center my-1">
                <h3 class="fw-bold me-5 my-1">Todos los Infantes Inscritos
                    <span class="text-gray-500 fs-6 ms-2"></span>
                </h3>
            </div>
            <div class="d-flex flex-wrap my-1">
                <a href="{{ route('admin.child.report.excel') }}" class="btn btn-success" id="kt_children_report_btn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <i class="ki-outline ki-file-down fs-3"></i>
                    <span class="ms-2">Generar Reporte</span>
                </a>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_children"
                       data-url="{{ route('admin.child.list_json_dt') }}" aria-describedby="table">
                    <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="text-start min-w-250px" name="child_name">Infante</th>
                        <th class="text-center min-w-200px" name="childcare_center_id">Centro / Sala</th>
                        <th class="text-center min-w-150px" name="state">Departamento</th>
                        <th class="text-center min-w-150px" name="enrollment_date">Fecha de Inscripción</th>
                        <th class="text-center min-w-150px" name="status">Estado</th>
                        <th class="text-end min-w-100px">Opciones</th>
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
    <script src="{{ asset('themes/admin/js/custom/child/list.js') }}"></script>
@endsection

