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
            <a class="text-white text-hover-secondary">Evaluaciones de Desarrollo</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            Listado de Evaluaciones de Desarrollo Infantil
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
                
                <div class="d-none" id="kt_monitoring_development_evaluations_detail">

                    
                </div>

                <div class="d-block" id="kt_monitoring_development_evaluations_list">

                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.summarize-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">General</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-nutrition-assessments-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4" href="">Nutrición</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-vaccination-tracking-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4" href="">Vacunas</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.monitoring.list-development-evaluations-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4 active" href="">Desarrollo Infantil</a>
                        </li>
                    </ul>
              
                    <div class="tab-content" id="myTabContent">
                      
                        <div class="tab-pane fade show active">
                            <!--begin::Tasks-->
                            <div class="card card-flush mb-6 mb-xl-9">
                                <div class="card-header mt-6">
                                    <!--begin::Card title-->
                                    <div class="card-title flex-column">
                                        <h2 class="mb-1">Evaluaciones de Desarrollo Infantil</h2>
                                        <div class="fs-6 fw-semibold text-muted">
                                            Total {{ $items->total() }} {{ $items->total() === 1 ? 'evaluación' : 'evaluaciones' }}
                                        </div>
                                    </div>
                                    <!--end::Card title-->
                                </div>
                                <div class="card-body p-9 pt-4">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_development_evaluations"
                                           data-url="" aria-describedby="table">
                                        <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-40px pe-2">#</th>
                                            <th class="min-w-150px">Fecha Evaluación</th>
                                            <th class="min-w-120px">Edad</th>
                                            <th class="min-w-180px text-center">Estado</th>
                                            <th class="min-w-100px">Desarrollo</th>
                                            <th class="text-end min-w-70px">Opciones</th>
                                        </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @if($items->count() > 0)
                                                @foreach($items as $index => $item)
                                                @php
                                                    $overallStatus = $item->getOverallStatus();
                                                    $overallScore = $item->getOverallScore();
                                                    $statusColor = match($overallStatus) {
                                                        'alert' => 'red',
                                                        'review' => 'yellow',
                                                        'normal' => 'green',
                                                        default => 'secondary'
                                                    };
                                                    $badgeClass = match($statusColor) {
                                                        'red' => 'badge-light-danger',
                                                        'yellow' => 'badge-light-warning',
                                                        'green' => 'badge-light-success',
                                                        default => 'badge-light-secondary'
                                                    };
                                                    $statusLabel = match($overallStatus) {
                                                        'alert' => 'Alerta',
                                                        'review' => 'Revisar',
                                                        'normal' => 'Normal',
                                                        default => 'Sin clasificación'
                                                    };
                                                    $progressBarColor = match($statusColor) {
                                                        'red' => 'bg-danger',
                                                        'yellow' => 'bg-warning',
                                                        'green' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                    // Obtener scores por área
                                                    $scoresByArea = $item->scores->keyBy('area');
                                                    $areas = [];
                                                    foreach (\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::cases() as $area) {
                                                        $areas[$area->value] = $area->abbreviation();
                                                    }
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
                                                                    {{ Str::ucfirst(\Carbon\Carbon::parse($item->evaluation_date)->locale('es')->translatedFormat('M')) }}
                                                                    {{ $item->evaluation_date->format('d') }}, 
                                                                    {{ $item->evaluation_date->format('Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span>{{ $item->age_readable }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <span class="badge {{ $badgeClass }} fs-7 fw-bold mb-2">
                                                                {{ $statusLabel }}
                                                            </span>
                                                            @php
                                                                $areaScoresDisplay = [];
                                                                foreach ($areas as $areaCode => $areaAbbr) {
                                                                    $score = $scoresByArea->get($areaCode);
                                                                    if ($score) {
                                                                        $areaStatusColor = $score->status->color();
                                                                        $bulletColor = match($areaStatusColor) {
                                                                            'red' => 'bg-danger',
                                                                            'yellow' => 'bg-warning',
                                                                            'blue' => 'bg-info',
                                                                            'green' => 'bg-success',
                                                                            default => 'bg-secondary'
                                                                        };
                                                                        $areaScoresDisplay[] = [
                                                                            'abbr' => $areaAbbr,
                                                                            'score' => $score->raw_score,
                                                                            'bulletColor' => $bulletColor
                                                                        ];
                                                                    }
                                                                }
                                                            @endphp
                                                            @if(count($areaScoresDisplay) > 0)
                                                            <div class="fs-9 text-gray-400 d-flex align-items-center justify-content-center flex-wrap">
                                                                @foreach($areaScoresDisplay as $areaScore)
                                                                    <span class="d-flex align-items-center">{{ $areaScore['abbr'] }}: {{ $areaScore['score'] }}</span>
                                                                    @if(!$loop->last)
                                                                    <span class="bullet bullet-dot {{ $areaScore['bulletColor'] }} mx-1"></span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                            @else
                                                            <div class="fs-9 text-gray-400">Sin datos</div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column w-100 me-2">
                                                            <div class="d-flex flex-stack mb-2">
                                                                <span class="text-muted me-2 fs-7 fw-bold">
                                                                    {{ $overallScore !== null ? number_format($overallScore, 1) . '%' : '-' }}
                                                                </span>
                                                            </div>
                                                            @if($overallScore !== null)
                                                            <div class="progress h-6px w-100">
                                                                <div class="progress-bar {{ $progressBarColor }}" role="progressbar" style="width: {{ $overallScore }}%" aria-valuenow="{{ $overallScore }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            @else
                                                            <div class="progress h-6px w-100 bg-light">
                                                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%"></div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex justify-content-end flex-shrink-0">
                                                            <a href="javascript:void(0)" 
                                                               data-url="{{ route('admin.monitoring.detail-development-evaluation-by-child', ['development_evaluation_id' => $item->getHashedKey()]) }}" 
                                                               class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 kt_btn_development_evaluation_detail" 
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

                                    @if($items->hasPages())
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-5">
                                            <div class="fs-6 fw-semibold text-gray-700">
                                                Mostrando {{ $items->firstItem() }} a {{ $items->lastItem() }} de {{ $items->total() }} resultados
                                            </div>
                                            <div>
                                                {{ $items->links() }}
                                            </div>
                                        </div>
                                    @endif
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

@section('modals')
    <!-- Modal para mostrar indicadores de desarrollo -->
    <div class="modal fade" id="kt_modal_development_indicators" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15" id="kt_development_indicators_content">
                    <div class="text-center py-10">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
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
    <script src="{{ asset('themes/admin/js/custom/monitoring/list-development-evaluations.js') }}"></script>
@endsection

