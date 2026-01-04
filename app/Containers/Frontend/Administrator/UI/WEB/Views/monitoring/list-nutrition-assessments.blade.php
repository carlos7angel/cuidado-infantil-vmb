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
            <a class="text-white text-hover-secondary">Evaluaciones Nutricionales</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            Listado de Evaluaciones Nutricionales
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
                
                <div class="d-none" id="kt_monitoring_nutritional_assessments_detail">

                    
                </div>

                <div class="d-block" id="kt_monitoring_nutritional_assessments_list">

                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.summarize-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">General</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-nutrition-assessments-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4 active" href="">Nutrición</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-vaccination-tracking-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4" href="">Vacunas</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-development-evaluations-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4" href="">Desarrollo Infantil</a>
                        </li>
                    </ul>
              
                    <div class="tab-content" id="myTabContent">
                      
                        <div class="tab-pane fade show active">
                            <!--begin::Tasks-->
                            <div class="card card-flush mb-6 mb-xl-9">
                                <div class="card-header mt-6">
                                    <!--begin::Card title-->
                                    <div class="card-title flex-column">
                                        <h2 class="mb-1">Evaluaciones Nutricionales</h2>
                                        <div class="fs-6 fw-semibold text-muted">
                                            Total {{ $items->total() }} {{ $items->total() === 1 ? 'evaluación' : 'evaluaciones' }}
                                        </div>
                                    </div>
                                    <!--end::Card title-->
                                </div>
                                <div class="card-body p-9 pt-4">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_nutritional_assessments"
                                           data-url="" aria-describedby="table">
                                        <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-40px pe-2">#</th>
                                            <th class="min-w-150px">Fecha de Evaluación</th>
                                            <th class="min-w-150px">Edad</th>
                                            <th class="min-w-150px">Estado</th>
                                            <th class="text-end min-w-70px">Opciones</th>
                                        </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @if($items->count() > 0)
                                                @foreach($items as $index => $item)
                                                @php
                                                    $criticalStatus = $item->getMostCriticalStatus();
                                                    $statusColor = $criticalStatus?->color() ?? 'secondary';
                                                    $badgeClass = match($statusColor) {
                                                        'red' => 'badge-light-danger',
                                                        'orange' => 'badge-light-warning',
                                                        'yellow' => 'badge-light-info',
                                                        'green' => 'badge-light-success',
                                                        default => 'badge-light-secondary'
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <span>{{ $items->firstItem() + $index }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-40px me-2">
                                                                <span class="symbol-label bg-light-primary">
                                                                    <i class="ki-outline ki-book-open fs-2x text-info"></i>
                                                                </span>
                                                            </div>
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-gray-900 fw-bolder text-hover-primary fs-6">
                                                                    {{ $item->assessment_date->format('d') }} de 
                                                                    {{ \Carbon\Carbon::parse($item->assessment_date)->locale('es')->translatedFormat('F') }} de 
                                                                    {{ $item->assessment_date->format('Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span>{{ $item->age_readable }}</span>
                                                    </td>
                                                    <td>
                                                        @if($criticalStatus)
                                                            <span class="badge {{ $badgeClass }} fs-7 fw-bold">
                                                                {{ $item->getMostCriticalStatusLabel() }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-light-secondary fs-7 fw-bold">
                                                                Sin clasificación
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex justify-content-end flex-shrink-0">
                                                            <a href="javascript:void(0)" 
                                                               data-url="{{ route('admin.monitoring.detail-nutritional-assessment-by-child', ['nutritional_assessment_id' => $item->getHashedKey()]) }}" 
                                                               class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 kt_btn_nutritional_assessment_detail" 
                                                               title="Ver detalle">
                                                                <i class="ki-outline ki-magnifier fs-2"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end::Tasks-->
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
    <script src="{{ asset('themes/common/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('themes/admin/js/custom/monitoring/list-nutrition-assessments.js') }}"></script>
@endsection

