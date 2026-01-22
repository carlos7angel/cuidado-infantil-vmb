@extends('vendor@template::admin.layouts.master', ['page' => 'index'])

@section('breadcrumbs')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.child.manage') }}" class="text-white text-hover-secondary">
                <i class="ki-outline ki-home text-white fs-3"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a href="{{ route('admin.child.manage') }}" class="text-white text-hover-secondary">Infantes Inscritos</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">Resumen</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            Resumen del Infante
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Por infante: {{ $child->full_name }}</span>
        </h1>
    </div>
    <div class="d-flex">
        <a href="{{ route('admin.monitoring.summarize-by-child.export-excel', ['child_id' => $child->id]) }}" 
           class="btn btn-success me-3" 
           id="btn-export-excel"
           onclick="showExportLoader(this)">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="export-spinner"></span>
            <i class="ki-outline ki-file-down fs-2" id="export-icon"></i> 
            <span id="export-text">Generar Reporte Excel</span>
        </a>
        <a href="{{ route('admin.child.manage') }}" class="btn btn-light">
            <i class="ki-outline ki-arrow-left fs-2"></i> Volver
        </a>
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="d-flex flex-column flex-lg-row">
            @include('frontend@administrator::child.partials.sidebar-card', ['child' => $child, 'showEnrollmentStatus' => true])
            
            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
                
                <!--begin::Nav tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    <li class="nav-item">
                        <a href="{{ route('admin.monitoring.summarize-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4 active">General</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.monitoring.list-nutrition-assessments-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">Nutrición</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.monitoring.list-vaccination-tracking-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">Vacunas</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.monitoring.list-development-evaluations-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">Desarrollo Infantil</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.monitoring.list-incidents-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">Incidentes</a>
                    </li>
                </ul>
                <!--end::Nav tabs-->

                <!--begin::Cards KPI-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-500 pt-1 fw-semibold fs-7">Evaluaciones Nutricionales</span>
                                    <span class="text-gray-800 fw-bold fs-2x lh-1">{{ $nutritionalStats['total'] }}</span>
                                </div>
                                <div class="d-flex align-items-center pt-2">
                                    @if($nutritionalStats['latest'])
                                        <span class="badge badge-light-success fs-7">Última: {{ $nutritionalStats['latest']->assessment_date->format('d/m/Y') }}</span>
                                    @else
                                        <span class="badge badge-light-secondary fs-7">Sin evaluaciones</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-500 pt-1 fw-semibold fs-7">Progreso de Vacunas</span>
                                    <span class="text-gray-800 fw-bold fs-2x lh-1">{{ $vaccineStats['completion_percentage'] }}%</span>
                                </div>
                                <div class="d-flex align-items-center pt-2">
                                    <span class="badge badge-light-info fs-7">{{ $vaccineStats['applied_doses'] }}/{{ $vaccineStats['total_doses'] }} dosis</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-500 pt-1 fw-semibold fs-7">Evaluaciones de Desarrollo</span>
                                    <span class="text-gray-800 fw-bold fs-2x lh-1">{{ $developmentStats['total'] }}</span>
                                </div>
                                <div class="d-flex align-items-center pt-2">
                                    @if($developmentStats['total'] > 0)
                                        @if($developmentStats['average_score'] !== null)
                                            <span class="badge badge-light-primary fs-7">Promedio: {{ number_format($developmentStats['average_score'], 1) }}%</span>
                                        @else
                                            <span class="badge badge-light-primary fs-7">Registradas</span>
                                        @endif
                                    @else
                                        <span class="badge badge-light-secondary fs-7">Sin evaluaciones</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-500 pt-1 fw-semibold fs-7">Asistencia (30 días)</span>
                                    <span class="text-gray-800 fw-bold fs-2x lh-1">{{ $attendanceStats['last_30_days']['percentage'] }}%</span>
                                </div>
                                <div class="d-flex align-items-center pt-2">
                                    <span class="badge badge-light-success fs-7">{{ $attendanceStats['last_30_days']['present_days'] }} presentes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Cards KPI-->

                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Estadísticas Nutricionales</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Distribución de estados</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                @if(!empty($nutritionalStats['status_distribution']))
                                    <div class="d-flex flex-column gap-5">
                                        @foreach($nutritionalStats['status_distribution'] as $status => $count)
                                            @php
                                                $statusEnum = \App\Containers\Monitoring\NutritionalAssessment\Enums\NutritionalStatus::tryFrom($status);
                                                $statusLabel = $statusEnum ? $statusEnum->label() : $status;
                                                $statusColor = $statusEnum ? $statusEnum->color() : 'secondary';
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <span class="text-gray-800 fw-semibold fs-6">{{ $statusLabel }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="text-gray-600 fw-bold fs-6">{{ $count }}</span>
                                                    <div class="progress h-6px w-100px" style="background-color: #f1f1f2;">
                                                        <div class="progress-bar bg-{{ $statusColor }}" role="progressbar" style="width: {{ ($count / $nutritionalStats['total']) * 100 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <span class="text-gray-500 fs-6">No hay datos disponibles</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Última Evaluación Nutricional</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Detalles de la evaluación más reciente</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                @if($nutritionalStats['latest'])
                                    @php
                                        $latest = $nutritionalStats['latest'];
                                        $mostCriticalStatus = $latest->getMostCriticalStatus();
                                    @endphp
                                    <div class="d-flex flex-column gap-5">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-gray-600 fw-semibold">Fecha:</span>
                                            <span class="text-gray-800 fw-bold">{{ $latest->assessment_date->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-gray-600 fw-semibold">Edad:</span>
                                            <span class="text-gray-800 fw-bold">{{ $latest->age_readable }}</span>
                                        </div>
                                        @if($latest->weight)
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="text-gray-600 fw-semibold">Peso:</span>
                                                <span class="text-gray-800 fw-bold">{{ number_format($latest->weight, 2) }} kg</span>
                                            </div>
                                        @endif
                                        @if($latest->height)
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="text-gray-600 fw-semibold">Talla:</span>
                                                <span class="text-gray-800 fw-bold">{{ number_format($latest->height, 2) }} cm</span>
                                            </div>
                                        @endif
                                        @if($mostCriticalStatus)
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="text-gray-600 fw-semibold">Estado:</span>
                                                <span class="badge badge-{{ $mostCriticalStatus->color() }} fs-7">{{ $mostCriticalStatus->label() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <span class="text-gray-500 fs-6">No hay evaluaciones registradas</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Progreso de Vacunación</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Estado general del esquema de vacunación</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                <div class="d-flex flex-column gap-5">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-gray-600 fw-semibold">Dosis aplicadas:</span>
                                        <span class="text-gray-800 fw-bold">{{ $vaccineStats['applied_doses'] }}/{{ $vaccineStats['total_doses'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-gray-600 fw-semibold">Porcentaje de completitud:</span>
                                        <span class="text-gray-800 fw-bold">{{ $vaccineStats['completion_percentage'] }}%</span>
                                    </div>
                                    <div class="progress h-10px">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $vaccineStats['completion_percentage'] }}%"></div>
                                    </div>
                                    @if($vaccineStats['overdue_count'] > 0)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-gray-600 fw-semibold">Vacunas vencidas:</span>
                                            <span class="badge badge-danger fs-7">{{ $vaccineStats['overdue_count'] }}</span>
                                        </div>
                                    @endif
                                    @if($vaccineStats['upcoming_count'] > 0)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-gray-600 fw-semibold">Próximas vacunas:</span>
                                            <span class="badge badge-info fs-7">{{ $vaccineStats['upcoming_count'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Próximas Vacunas</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Próximas 5 vacunas a aplicar</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                @if($upcomingVaccines->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Vacuna</th>
                                                    <th class="min-w-100px">Dosis</th>
                                                    <th class="min-w-120px">Edad recomendada</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($upcomingVaccines as $vaccine)
                                                    <tr>
                                                        <td class="text-gray-800 fw-semibold">{{ $vaccine['vaccine_name'] }}</td>
                                                        <td class="text-gray-600">{{ $vaccine['dose_number'] }}</td>
                                                        <td class="text-gray-600">{{ $vaccine['recommended_age'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <span class="text-gray-500 fs-6">No hay vacunas próximas</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Desarrollo por Áreas</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Última evaluación de desarrollo</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                @if(!empty($areaScores))
                                    <div class="d-flex flex-column gap-5">
                                        @foreach($areaScores as $areaData)
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="text-gray-800 fw-semibold fs-6">{{ $areaData['area_label'] }}</span>
                                                    @if($areaData['percentage'] !== null)
                                                        <span class="text-gray-600 fw-bold fs-6">{{ $areaData['percentage'] }}%</span>
                                                    @else
                                                        <span class="text-gray-400 fs-7">N/A</span>
                                                    @endif
                                                </div>
                                                @if($areaData['percentage'] !== null)
                                                    <div class="progress h-6px">
                                                        <div class="progress-bar bg-{{ $areaData['status'] === 'normal' ? 'success' : ($areaData['status'] === 'alert' ? 'danger' : 'warning') }}" role="progressbar" style="width: {{ $areaData['percentage'] }}%"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <span class="text-gray-500 fs-6">No hay evaluaciones de desarrollo registradas</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Asistencia</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Últimos 3 meses</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                @if(!empty($attendanceStats['last_6_months']))
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-120px">Mes</th>
                                                    <th class="min-w-100px">Presentes</th>
                                                    <th class="min-w-100px">Total</th>
                                                    <th class="min-w-100px">%</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($attendanceStats['last_6_months'] as $month)
                                                    <tr>
                                                        <td class="text-gray-800 fw-semibold">{{ $month['month_label'] }}</td>
                                                        <td class="text-gray-600">{{ $month['present'] }}</td>
                                                        <td class="text-gray-600">{{ $month['total'] }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $month['percentage'] >= 80 ? 'success' : ($month['percentage'] >= 60 ? 'warning' : 'danger') }} fs-7">{{ $month['percentage'] }}%</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <span class="text-gray-500 fs-6">No hay datos de asistencia disponibles</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

            </div>
            <!--end::Content-->
        </div>

    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script>
        function showExportLoader(button) {
            const spinner = document.getElementById('export-spinner');
            const icon = document.getElementById('export-icon');
            const text = document.getElementById('export-text');
            
            // Mostrar spinner y ocultar icono
            if (spinner && icon && text) {
                spinner.classList.remove('d-none');
                icon.classList.add('d-none');
                text.textContent = 'Generando...';
                button.disabled = true;
                
                // El loader se ocultará cuando se descargue el archivo
                // Si hay un error, se puede resetear después de un tiempo
                setTimeout(function() {
                    if (button.disabled) {
                        spinner.classList.add('d-none');
                        icon.classList.remove('d-none');
                        text.textContent = 'Generar Reporte Excel';
                        button.disabled = false;
                    }
                }, 10000); // Resetear después de 30 segundos si no se descargó
            }
        }
    </script>
@endsection
