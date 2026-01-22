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
            <a class="text-white text-hover-secondary">Control de Vacunas</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            Listado de Control de Vacunas
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Por infante: {{ $child->full_name }}</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
        <a href="{{ route('admin.child.manage') }}" class="btn btn-light">
            <i class="ki-outline ki-arrow-left fs-2"></i> Volver
        </a>
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="d-flex flex-column flex-lg-row">
            @include('frontend@administrator::child.partials.sidebar-card', ['child' => $child])
            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
                
                <div class="d-none" id="kt_vaccination_detail"></div>

                <div class="d-block" id="kt_vaccination_list">

                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.summarize-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">General</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-nutrition-assessments-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4" href="">Nutrición</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-vaccination-tracking-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4 active" href="">Vacunas</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-development-evaluations-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4" href="">Desarrollo Infantil</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-incidents-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">Incidentes</a>
                        </li>
                    </ul>

                    <div class="card card-flush mb-6 mb-xl-9">
                        <div class="card-header mt-6">
                            <div class="card-title flex-column">
                                <h2 class="mb-1">Vacunas</h2>
                                <div class="fs-6 fw-semibold text-muted">
                                    Total {{ $vaccines->count() }} {{ $vaccines->count() === 1 ? 'vacuna' : 'vacunas' }}
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-9 pt-4">
                            <div class="row g-2 g-xl-8">
                                @foreach($vaccines as $vaccineData)
                                    @php
                                        $vaccine = $vaccineData['vaccine'];
                                        $progress = $vaccineData['progress'];
                                        
                                        $progressBarColor = match(true) {
                                            $progress['is_complete'] => 'bg-success',
                                            $progress['percentage'] >= 50 => 'bg-primary',
                                            $progress['percentage'] >= 25 => 'bg-warning',
                                            default => 'bg-danger'
                                        };
                                    @endphp
                                    <div class="col-xl-4">
                                        <div class="card card-xl-stretch mb-xl-8 h-100">
                                            <div class="card-body p-5 d-flex flex-column">
                                                <div class="d-flex flex-stack">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-50px me-3">
                                                            <span class="symbol-label bg-light-primary">
                                                                <i class="ki-outline ki-syringe fs-1 text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
                                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-5">{{ $vaccine->name }}</a>
                                                            <span class="text-muted fw-bold">Aplicadas {{ $progress['applied'] }}/{{ $progress['total'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column w-100 mt-8" style="min-height: 48px;">
                                                    @if($vaccine->description)
                                                    <span class="text-muted fw-bold fs-7" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">{{ $vaccine->description }}</span>
                                                    @else
                                                    <span class="text-muted fw-bold fs-7" style="visibility: hidden;">&nbsp;</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column w-100 mt-8">
                                                    <span class="text-gray-900 me-2 fw-bold pb-3">Progreso: {{ number_format($progress['percentage'], 0) }}%</span>
                                                    <div class="progress h-5px w-100">
                                                        <div class="progress-bar {{ $progressBarColor }}" role="progressbar" style="width: {{ $progress['percentage'] }}%" aria-valuenow="{{ $progress['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column mt-auto pt-5">
                                                    <div class="d-flex justify-content-end flex-shrink-0">
                                                        <a href="javascript:void(0)" 
                                                           data-vaccine-index="{{ $loop->index }}" 
                                                           class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm kt_btn_view_vaccine_detail" 
                                                           title="Ver detalle">
                                                            <i class="ki-outline ki-arrow-right fs-2"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
            <!--end::Content-->
        </div>

    </div>
</div>

@endsection

@section('styles')
    <link href="{{ asset('themes/common/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    @php
        $vaccinesJson = $vaccines->map(function($v) {
            return [
                'vaccine' => [
                    'id' => $v['vaccine']->id,
                    'name' => $v['vaccine']->name,
                    'description' => $v['vaccine']->description,
                    'total_doses' => $v['vaccine']->total_doses,
                ],
                'doses' => $v['doses']->map(function($d) {
                    $dose = $d['dose'];
                    $cv = $d['child_vaccination'];
                    return [
                        'dose' => [
                            'id' => $dose->id,
                            'dose_number' => $dose->dose_number,
                            'recommended_age_months' => $dose->recommended_age_months,
                            'min_age_months' => $dose->min_age_months,
                            'max_age_months' => $dose->max_age_months,
                            'recommended_age' => $dose->recommended_age,
                            'age_range' => $dose->age_range,
                            'description' => $dose->description,
                        ],
                        'status' => $d['status'],
                        'status_label' => $d['status_label'],
                        'status_color' => $d['status_color'],
                        'child_vaccination' => $cv ? [
                            'id' => $cv->id,
                            'date_applied' => $cv->date_applied->format('Y-m-d'),
                            'applied_at' => $cv->applied_at,
                            'notes' => $cv->notes,
                        ] : null,
                    ];
                })->values()->toArray(),
                'progress' => $v['progress'],
            ];
        })->values()->toArray();
    @endphp
    <script>
        // Pasar datos de vacunas al JavaScript
        window.vaccinesData = @json($vaccinesJson);
    </script>
    <script src="{{ asset('themes/common/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('themes/admin/js/custom/monitoring/list-vaccination-tracking.js') }}"></script>
@endsection

