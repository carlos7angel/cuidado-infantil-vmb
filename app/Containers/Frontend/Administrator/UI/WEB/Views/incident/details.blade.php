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
            <a href="{{ route('admin.incident.manage') }}" class="text-white text-hover-secondary">Reportes de Incidentes</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">Detalle</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            DETALLE DEL REPORTE DE INCIDENTE
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Código: {{ $incident->code }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
        <a href="{{ route('admin.incident.manage') }}" class="btn btn-light">
            <i class="ki-outline ki-arrow-left fs-2"></i> Volver
        </a>
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="d-flex flex-column flex-lg-row">

            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                @include('frontend@administrator::child.partials.sidebar-card', ['child' => $incident->child])

                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <div class="d-flex flex-center flex-column py-5">
                            @php
                                $severityColor = $incident->severity_level?->color() ?? '#9E9E9E';
                                $badgeClass = match($severityColor) {
                                    '#4CAF50' => 'badge-light-success',
                                    '#FF9800' => 'badge-light-warning',
                                    '#F44336' => 'badge-light-danger',
                                    '#9C27B0' => 'badge-light-info',
                                    default => 'badge-light-secondary'
                                };
                                $statusBadgeClass = match($incident->status?->value) {
                                    'reportado' => 'badge-light-primary',
                                    'en_revision' => 'badge-light-warning',
                                    'cerrado' => 'badge-light-success',
                                    'escalado' => 'badge-light-danger',
                                    'archivado' => 'badge-light-secondary',
                                    default => 'badge-light-secondary'
                                };
                            @endphp
                            <div class="fs-3 text-gray-800 text-center fw-bold mb-3">{{ $incident->code }}</div>
                            <div class="fs-5 fw-bold text-muted mb-3">
                                {{ $incident->type?->label() ?? '-' }}
                            </div>
                            <div class="mb-5 w-100">
                                <label class="fs-7 fw-bold text-gray-600 mb-2 d-block">Estado:</label>
                                <select id="kt_incident_status_select" 
                                        class="form-select form-select-sm form-select-solid" 
                                        data-control="select2" 
                                        data-hide-search="false"
                                        data-update-url="{{ route('admin.incident.update_status', ['incident_id' => $incident->id]) }}">
                                    @foreach($incidentStatuses as $status)
                                        <option value="{{ $status['value'] }}" {{ $incident->status?->value === $status['value'] ? 'selected' : '' }}>
                                            {{ $status['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="kt_incident_status_badge" class="badge badge-lg {{ $statusBadgeClass }} d-block mt-2">
                                    {{ $incident->status?->label() ?? 'Sin estado' }}
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
            <!--end::Sidebar-->

            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">

                <!--begin::Información General del Incidente-->
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header mt-6">
                        <div class="card-title flex-column">
                            <h2 class="mb-1">Información General del Incidente</h2>
                            <div class="fs-6 fw-semibold text-muted">
                                Datos principales del reporte
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-9 pt-4">
                        <h6 class="mb-5 fw-bolder text-primary">Datos registrados</h6>

                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Código:</label>
                            </div>
                            <div class="col-md-8">
                                <p class="form-control form-control-plaintext">{{ $incident->code }}</p>
                            </div>
                        </div>

                        <div class="separator separator-dashed border-muted"></div>

                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Tipo de Incidente:</label>
                            </div>
                            <div class="col-md-8">
                                <p class="form-control form-control-plaintext">{{ $incident->type?->label() ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="separator separator-dashed border-muted"></div>

                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Nivel de Severidad:</label>
                            </div>
                            <div class="col-md-8">
                                @php
                                    $severityColor = $incident->severity_level?->color() ?? '#9E9E9E';
                                    $badgeClass = match($severityColor) {
                                        '#4CAF50' => 'badge-light-success',
                                        '#FF9800' => 'badge-light-warning',
                                        '#F44336' => 'badge-light-danger',
                                        '#9C27B0' => 'badge-light-info',
                                        default => 'badge-light-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-7 fw-bold my-3">
                                    {{ $incident->severity_level?->label() ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="separator separator-dashed border-muted"></div>

                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Estado:</label>
                            </div>
                            <div class="col-md-8">
                                @php
                                    $statusBadgeClass = match($incident->status?->value) {
                                        'reportado' => 'badge-light-primary',
                                        'en_revision' => 'badge-light-warning',
                                        'cerrado' => 'badge-light-success',
                                        'escalado' => 'badge-light-danger',
                                        'archivado' => 'badge-light-secondary',
                                        default => 'badge-light-secondary'
                                    };
                                @endphp
                                <span id="kt_incident_status_badge_content" class="badge {{ $statusBadgeClass }} fs-7 my-3 fw-bold">
                                    {{ $incident->status?->label() ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="separator separator-dashed border-muted"></div>

                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Fecha del Incidente:</label>
                            </div>
                            <div class="col-md-8">
                                <p class="form-control form-control-plaintext">
                                    {{ $incident->incident_date ? $incident->incident_date->locale('es')->translatedFormat('d \de F \de Y') : '-' }}
                                    @if($incident->incident_time)
                                        {{ date('H:i', strtotime($incident->incident_time)) }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($incident->incident_location)
                        <div class="separator separator-dashed border-muted"></div>
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Ubicación del Incidente:</label>
                            </div>
                            <div class="col-md-8">
                                <p class="form-control form-control-plaintext">{{ $incident->incident_location }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="separator separator-dashed border-muted"></div>

                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Fecha de Reporte:</label>
                            </div>
                            <div class="col-md-8">
                                <p class="form-control form-control-plaintext">
                                    {{ $incident->reported_at ? $incident->reported_at->locale('es')->translatedFormat('d \de F \de Y \a \l\a\s H:i') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="separator separator-dashed border-muted"></div>

                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                                <label class="fw-semibold fs-7 text-gray-600">Reportado por:</label>
                            </div>
                            <div class="col-md-8">
                                <p class="form-control form-control-plaintext">{{ $incident->reportedBy?->name ?? '-' }}</p>
                            </div>
                        </div>

                    </div>
                </div>
                <!--end::Información General del Incidente-->

                <!--begin::Descripción del Incidente-->
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header mt-6">
                        <div class="card-title flex-column">
                            <h2 class="mb-1">Descripción del Incidente</h2>
                            <div class="fs-6 fw-semibold text-muted">
                                Detalles del incidente reportado
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-9 pt-4">
                        @if($incident->description)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->description }}</div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-10">
                            <span class="text-gray-500 fs-6">No hay descripción registrada</span>
                        </div>
                        @endif

                        @if($incident->people_involved)
                        <div class="separator separator-dashed border-muted"></div>
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Personas Involucradas:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->people_involved }}</div>
                            </div>
                        </div>
                        @endif

                        @if($incident->witnesses)
                        <div class="separator separator-dashed border-muted"></div>
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Testigos:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->witnesses }}</div>
                            </div>
                        </div>
                        @endif

                        @if($incident->escalated_to)
                        <div class="separator separator-dashed border-muted"></div>
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Reportado a:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->escalated_to }}</div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
                <!--end::Descripción del Incidente-->


                <!--begin::Evidencia-->
                @if($incident->has_evidence)
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header mt-6">
                        <div class="card-title flex-column">
                            <h2 class="mb-1">Evidencia</h2>
                            <div class="fs-6 fw-semibold text-muted">
                                Archivos e imágenes de evidencia adjuntos
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-9 pt-4">
                        @if($incident->evidence_description)
                        <div class="row mb-8">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Descripción de la Evidencia:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->evidence_description }}</div>
                            </div>
                        </div>
                        <div class="separator separator-dashed border-muted"></div>
                        @endif

                        @php
                            $evidenceFiles = $incident->evidenceFiles();
                            $isImage = function($mimeType) {
                                return str_starts_with($mimeType, 'image/');
                            };
                        @endphp

                        @if($evidenceFiles->count() > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row g-5">
                                    @foreach($evidenceFiles as $file)
                                    <div class="col-md-4">
                                        <div class="card card-flush h-100">
                                            <div class="card-body p-5">
                                                @if($isImage($file->mime_type))
                                                <div class="mb-5">
                                                    <img src="{{ $file->getDownloadUrl() }}" 
                                                         alt="{{ $file->original_name ?? $file->name }}" 
                                                         class="img-fluid rounded"
                                                         style="max-height: 200px; width: 100%; object-fit: cover;">
                                                </div>
                                                @else
                                                <div class="d-flex flex-center mb-5" style="height: 150px;">
                                                    <i class="ki-outline ki-file fs-3x text-gray-400"></i>
                                                </div>
                                                @endif
                                                <div class="mb-3">
                                                    <a href="{{ $file->getDownloadUrl() }}" 
                                                       target="_blank" 
                                                       class="text-gray-800 text-hover-primary fw-semibold d-block">
                                                        {{ $file->original_name ?? $file->name }}
                                                    </a>
                                                    <span class="text-muted fs-8">
                                                        ({{ $file->human_readable_size }})
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ $file->getDownloadUrl() }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-light-primary"
                                                       title="Ver/Descargar">
                                                        <i class="ki-outline ki-arrow-down fs-4"></i>
                                                        @if($isImage($file->mime_type))
                                                            Ver Imagen
                                                        @else
                                                            Descargar
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-10">
                            <span class="text-gray-500 fs-6">No hay archivos de evidencia adjuntos</span>
                        </div>
                        @endif

                    </div>
                </div>
                @endif
                <!--end::Evidencia-->

                <!--begin::Seguimiento-->
                @if($incident->actions_taken || $incident->additional_comments || $incident->follow_up_notes || $incident->authority_notification_details)
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header mt-6">
                        <div class="card-title flex-column">
                            <h2 class="mb-1">Seguimiento</h2>
                            <div class="fs-6 fw-semibold text-muted">
                                Información del seguimiento y resolución del incidente
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-9 pt-4">
                        @if($incident->actions_taken)
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Acciones Tomadas:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->actions_taken }}</div>
                            </div>
                        </div>
                        @if($incident->additional_comments || $incident->follow_up_notes || $incident->authority_notification_details)
                        <div class="separator separator-dashed border-muted"></div>
                        @endif
                        @endif

                        @if($incident->additional_comments)
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Comentarios Adicionales:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->additional_comments }}</div>
                            </div>
                        </div>
                        @if($incident->follow_up_notes || $incident->authority_notification_details)
                        <div class="separator separator-dashed border-muted"></div>
                        @endif
                        @endif

                        @if($incident->follow_up_notes)
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Notas de Seguimiento:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->follow_up_notes }}</div>
                            </div>
                        </div>
                        @if($incident->authority_notification_details)
                        <div class="separator separator-dashed border-muted"></div>
                        @endif
                        @endif

                        @if($incident->authority_notification_details)
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-start justify-content-end text-end pt-4">
                                <label class="fw-semibold fs-7 text-gray-600">Detalles de Notificación a Autoridades:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-control form-control-plaintext" style="white-space: pre-wrap;">{{ $incident->authority_notification_details }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <!--end::Seguimiento-->

            </div>
            <!--end::Content-->
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('themes/admin/js/custom/incident/details.js') }}"></script>
@endsection
