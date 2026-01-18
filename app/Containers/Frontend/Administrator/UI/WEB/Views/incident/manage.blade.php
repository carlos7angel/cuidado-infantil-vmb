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
            <a class="text-white text-hover-secondary">Reportes de Incidentes</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            REPORTES DE INCIDENTES
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Gestión de Incidentes</span>
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
                            <label class="fs-6 form-label fw-bold text-gray-900">Código</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="1" name="kt_search_code" />
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Infante (Nombres y Apellidos)</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="2" name="kt_search_child" />
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Centro de Cuidado Infantil</label>
                            <select class="form-select form-select-sm datatable-input" data-control="select2" data-hide-search="false" data-col-index="3" name="kt_search_childcare_center">
                                <option value="">Todos</option>
                                @foreach($childcareCenters as $center)
                                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="fs-6 form-label fw-bold text-gray-900">Sala/Grupo</label>
                            <select class="form-select form-select-sm" data-control="select2" data-hide-search="false" name="kt_search_room">
                                <option value="">Todas</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->childcareCenter->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="fs-6 form-label fw-bold text-gray-900">Tipo de Incidente</label>
                            <select class="form-select form-select-sm datatable-input" data-control="select2" data-hide-search="false" data-col-index="4" name="kt_search_type">
                                <option value="">Todos</option>
                                @foreach($incidentTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="fs-6 form-label fw-bold text-gray-900">Estado</label>
                            <select class="form-select form-select-sm datatable-input" data-control="select2" data-hide-search="false" data-col-index="6" name="kt_search_status">
                                <option value="">Todos</option>
                                @foreach($incidentStatuses as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap flex-stack pb-7">
            <div class="d-flex flex-wrap align-items-center my-1">
                <h3 class="fw-bold me-5 my-1">Todos los Incidentes
                    <span class="text-gray-500 fs-6 ms-2"></span>
                </h3>
            </div>
            <div class="d-flex flex-wrap my-1">
                <button type="button" id="kt_incident_report_btn" class="btn btn-success" data-url="{{ route('admin.incident.report.excel') }}">
                    <i class="ki-outline ki-file-down fs-3 me-2"></i>
                    Generar Reporte
                </button>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_incidents"
                       data-url="{{ route('admin.incident.list_json_dt') }}" aria-describedby="table">
                    <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="text-start min-w-150px" name="code">Código</th>
                        <th class="text-start min-w-250px" name="child_name">Infante</th>
                        <th class="text-center min-w-200px" name="childcare_center_name">Centro / Sala</th>
                        <th class="text-start min-w-150px" name="type">Tipo</th>
                        <th class="text-center min-w-120px" name="status">Estado</th>
                        <th class="text-center min-w-120px" name="incident_date">Fecha Incidente</th>
                        <th class="text-end min-w-70px">Opciones</th>
                    </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend for attention-needed incidents -->
        <div class="d-flex justify-content-center align-items-center mt-4 mb-4">
            <div class="d-flex align-items-center me-4">
                <div class="w-20px h-20px rounded me-2" style="background-color: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.3);"></div>
                <span class="fs-7 fw-semibold text-gray-700">Incidentes que requieren atención (más de 60 días sin resolver)</span>
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
    <script src="{{ asset('themes/admin/js/custom/incident/list.js') }}"></script>
@endsection

