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
            <a class="text-white text-hover-secondary">Logs de Auditoría</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            LOGS DE AUDITORÍA
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Gestión de Logs de Actividad</span>
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
                            <label class="fs-6 form-label fw-bold text-gray-900">Tipo de Log</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="1" name="kt_search_log_name" />
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Descripción</label>
                            <input type="text" class="form-control form-control-sm form-control-solid-x datatable-input" data-col-index="2" name="kt_search_description" />
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Rango de Fecha</label>
                            <div class="input-group">
                                <input type="text" id="kt_field_daterangepicker_logs" class="form-control form-control-sm form-control-solid" name="date_range" />
                                <span class="input-group-text">
                                    <i class="ki-outline ki-calendar-8 text-gray-500 lh-0 fs-2"></i>
                                </span>
                            </div>
                            <input type="hidden" name="start_date" id="start_date_logs" value="" />
                            <input type="hidden" name="end_date" id="end_date_logs" value="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap flex-stack pb-7">
            <div class="d-flex flex-wrap align-items-center my-1">
                <h3 class="fw-bold me-5 my-1">Todos los Logs de Actividad
                    <span class="text-gray-500 fs-6 ms-2"></span>
                </h3>
            </div>
            <div class="d-flex flex-wrap my-1">
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_activity_logs"
                       data-url="{{ route('admin.activity_log.list_json_dt') }}" aria-describedby="table">
                    <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">#</th>
                        <th class="text-start min-w-150px" name="log_name">Tipo</th>
                        <th class="text-start min-w-250px" name="description">Descripción</th>
                        <th class="text-start min-w-200px" name="user_name">Usuario</th>
                        <th class="text-center min-w-150px" name="created_at">Fecha</th>
                        <th class="text-center min-w-120px" name="ip_address">IP</th>
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
    <link href="{{ asset('themes/common/plugins/custom/daterangepicker/daterangepicker.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{ asset('themes/common/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('themes/common/plugins/custom/moment/moment.min.js') }}"></script>
    <script src="{{ asset('themes/common/plugins/custom/daterangepicker/daterangepicker.bundle.js') }}"></script>
    <script src="{{ asset('themes/admin/js/custom/activity_log/list.js') }}"></script>
@endsection

@section('modals')
<div class="modal fade" id="kt_modal_activity_log_detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h2 class="fw-bold">Detalle del Log de Actividad</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body py-5 px-5">
                <div class="mb-4">
                    <div class="d-flex flex-column mb-2">
                        <span class="fw-bold text-gray-700">Tipo</span>
                        <span id="activity_log_type" class="text-gray-900"></span>
                    </div>
                    <div class="d-flex flex-column mb-2">
                        <span class="fw-bold text-gray-700">Descripción</span>
                        <span id="activity_log_description" class="text-gray-900"></span>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-700">Usuario</span>
                                <span id="activity_log_user" class="text-gray-900"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-700">Fecha</span>
                                <span id="activity_log_date" class="text-gray-900"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-700">IP</span>
                                <span id="activity_log_ip" class="text-gray-900"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column mb-2">
                        <span class="fw-bold text-gray-700">Agente</span>
                        <span id="activity_log_agent" class="text-gray-900"></span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-gray-700">Propiedades</span>
                        <pre id="activity_log_properties" class="mt-2 bg-light p-3 rounded border overflow-auto" style="max-height: 300px;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

