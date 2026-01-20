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
            <a class="text-white text-hover-secondary">Reporte de Asistencia</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            REPORTE DE ASISTENCIA
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Visualización de asistencia por rango de fechas</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <!-- Filtros -->
        <div class="card mb-7 border-0 shadow-none">
            <div class="card-body p-0">
                <form method="GET" action="{{ route('admin.attendance.report') }}" id="kt_attendance_report_form" data-download-url="{{ route('admin.attendance.report.download') }}">
                    <div class="row g-8 mb-5">
                        <div class="col-lg-4">
                            <label class="fs-6 form-label fw-bold text-gray-900">Centro de Cuidado Infantil</label>
                            <select class="form-select form-select-solid" name="childcare_center_id" id="childcare_center_id" data-control="select2" data-hide-search="false" data-allow-clear="true" data-placeholder="Todos los centros">
                                <option value="">Todos los centros</option>
                                @foreach($childcare_centers as $center)
                                    <option value="{{ $center->id }}" {{ old('childcare_center_id', request('childcare_center_id')) == $center->id ? 'selected' : '' }}>
                                        {{ $center->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="fs-6 form-label fw-bold text-gray-900 required">Rango de Fechas</label>
                            <div class="input-group input-group-solid">
                                <input type="text" id="kt_field_daterangepicker" class="form-control form-control-solid" name="date_range" />
                                <span class="input-group-text">
                                    <i class="ki-outline ki-calendar-8 text-gray-500 lh-0 fs-2"></i>
                                </span>
                            </div>
                            <input type="hidden" name="start_date" id="start_date" value="{{ old('start_date', request('start_date', $defaultStartDate ?? '')) }}" />
                            <input type="hidden" name="end_date" id="end_date" value="{{ old('end_date', request('end_date', $defaultEndDate ?? '')) }}" />
                        </div>
                        <div class="col-lg-2">
                            <label class="fs-6 form-label fw-bold text-gray-900">Tipo de Reporte</label>
                            <select class="form-select form-select-solid" name="report_type" id="report_type" data-control="select2" data-hide-search="true" data-allow-clear="false" data-placeholder="Selecciona un tipo de reporte">
                                <option value="complete" {{ old('report_type', request('report_type', 'complete')) == 'complete' ? 'selected' : '' }}>Completo (4 estados)</option>
                                <option value="simplified" {{ old('report_type', request('report_type')) == 'simplified' ? 'selected' : '' }}>Simplificado (1/0)</option>
                            </select>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end gap-2">
                            <button type="submit" name="action" value="generate" class="btn btn-sm btn-primary flex-1">
                                <i class="ki-outline ki-file-up fs-2"></i>
                                Generar Reporte
                            </button>
                            <button type="button" name="action" value="download" id="kt_attendance_download_btn" class="btn btn-sm btn-success flex-1">
                                <i class="ki-outline ki-file-down fs-2"></i>
                                Descargar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($reportData)
        <div class="card card-flush">
            <div class="card-header">
                <div class="card-title">
                    <h2>Reporte de Asistencia</h2>
                </div>
            </div>
            <div class="card-body pt-0">
                @if($reportData['childcare_center'])
                    <div class="mb-5">
                        <h3 class="fw-bold text-gray-800 fs-4">Centro: {{ $reportData['childcare_center']->name }}</h3>
                    </div>
                @else
                    <div class="mb-5">
                        <h3 class="fw-bold text-gray-800 fs-4">Todos los Centros</h3>
                    </div>
                @endif
                <div class="table-responsive attendance-report-table-wrapper">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_attendance_report_table">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="sticky-column min-w-200px bg-white ps-4 rounded-start">Infante</th>
                                @foreach($reportData['dates'] as $date)
                                    <th class="text-center min-w-100px">
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</div>
                                        <div class="text-muted fs-7">{{ \Carbon\Carbon::parse($date)->format('D') }}</div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['children'] as $child)
                            <tr>
                                <td class="sticky-column bg-white fw-bold">
                                    {{ $child['child_name'] }}
                                </td>
                                @foreach($reportData['dates'] as $date)
                                    @php
                                        $attendance = $child['attendance'][$date] ?? null;
                                        $status = $attendance['status'] ?? null;
                                        $reportType = request('report_type', 'complete');
                                        $statusClass = 'badge-light-secondary';
                                        $statusText = '-';
                                        
                                        if ($status) {
                                            if ($reportType === 'simplified') {
                                                // Reporte simplificado: Presente (1) engloba presente y retraso, Falta (0) engloba falta y justificado
                                                if ($status === 'presente' || $status === 'retraso') {
                                                    $statusClass = 'badge-light-success';
                                                    $statusText = '1';
                                                } elseif ($status === 'falta' || $status === 'justificado') {
                                                    $statusClass = 'badge-light-danger';
                                                    $statusText = '0';
                                                }
                                            } else {
                                                // Reporte completo con 4 estados
                                                switch($status) {
                                                    case 'presente':
                                                        $statusClass = 'badge-light-success';
                                                        $statusText = 'P';
                                                        break;
                                                    case 'retraso':
                                                        $statusClass = 'badge-light-warning';
                                                        $statusText = 'R';
                                                        break;
                                                    case 'falta':
                                                        $statusClass = 'badge-light-danger';
                                                        $statusText = 'F';
                                                        break;
                                                    case 'justificado':
                                                        $statusClass = 'badge-light-info';
                                                        $statusText = 'J';
                                                        break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <td class="text-center">
                                        @if($status)
                                            <span class="badge {{ $statusClass }}" title="{{ ucfirst($status) }}{{ $attendance['check_in_time'] ? ' - Entrada: ' . $attendance['check_in_time'] : '' }}{{ $attendance['check_out_time'] ? ' - Salida: ' . $attendance['check_out_time'] : '' }}">
                                                {{ $statusText }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Leyenda -->
                <div class="mt-7">
                    <h4 class="fw-bold mb-4">Leyenda:</h4>
                    <div class="d-flex flex-wrap gap-3">
                        @if(request('report_type', 'complete') === 'simplified')
                            <span class="badge badge-light-success">1 = Presente (Presente + Retraso)</span>
                            <span class="badge badge-light-danger">0 = Falta (Falta + Justificado)</span>
                        @else
                            <span class="badge badge-light-success">P = Presente</span>
                            <span class="badge badge-light-warning">R = Retraso</span>
                            <span class="badge badge-light-danger">F = Falta</span>
                            <span class="badge badge-light-info">J = Justificado</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('styles')
    <style>
        .attendance-report-table-wrapper {
            overflow-x: auto;
            overflow-y: auto;
            max-height: calc(100vh - 400px);
            position: relative;
        }
        
        .sticky-column {
            position: sticky;
            z-index: 10;
            background-color: white;
        }
        
        .sticky-column:first-child {
            left: 0;
            z-index: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        #kt_attendance_report_table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        #kt_attendance_report_table thead th {
            background-color: #f9fafb !important;
            position: sticky;
            top: 0;
            z-index: 11;
        }
        
        #kt_attendance_report_table thead .sticky-column {
            background-color: #f9fafb !important;
            z-index: 12;
        }
        
        #kt_attendance_report_table thead .sticky-column:first-child {
            left: 0;
            z-index: 13;
        }
        
        #kt_attendance_report_table tbody .sticky-column {
            background-color: white;
        }
        
        #kt_attendance_report_table tbody tr:hover .sticky-column {
            background-color: #f5f8fa;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('themes/admin/js/custom/attendance/report.js') }}"></script>
@endsection

