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
            <a class="text-white text-hover-secondary">Reportes de Incidentes</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            Listado de Incidentes
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
            @include('frontend@administrator::child.partials.sidebar-card', ['child' => $child, 'showEnrollmentStatus' => true])
            
            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
                
                <!--begin::Nav tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    <li class="nav-item">
                        <a href="{{ route('admin.monitoring.summarize-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4">General</a>
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
                        <a href="{{ route('admin.monitoring.list-incidents-by-child', ['child_id' => $child->id]) }}" class="nav-link text-active-primary pb-4 active">Incidentes</a>
                    </li>
                </ul>
                <!--end::Nav tabs-->

                <!--begin::Card-->
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Historial de Incidentes</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Listado de incidentes registrados para {{ $child->first_name }}</span>
                        </h3>
                        <div class="card-toolbar">
                            <!-- Search and other tools can go here if needed -->
                        </div>
                    </div>
                    <div class="card-body p-9 pt-4">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_incidents">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-40px pe-2">#</th>
                                    <th class="min-w-100px">Código</th>
                                    <th class="min-w-150px">Tipo</th>
                                    <th class="min-w-150px">Fecha Incidente</th>
                                    <th class="text-end min-w-70px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @if($items->count() > 0)
                                    @foreach($items as $index => $item)
                                    <tr>
                                        <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <span class="fw-bold text-gray-800">{{ $item->code }}</span>
                                        </td>
                                        <td>
                                            {{ $item->type?->label() ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $item->incident_date ? $item->incident_date->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.incident.show', ['incident_id' => $item->id]) }}" class="btn btn-sm btn-light btn-active-light-primary">
                                                Ver Detalle
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Card-->

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
    <script>
        var KTListIncidentsByChild = function () {
            var table = document.getElementById('kt_table_incidents');
            var datatable;
            
            var initTable = function () {
                datatable = $(table).DataTable({
                    dom:
                        "<'row mb-2'" +
                        "<'col-sm-6 d-flex align-items-center justify-content-start dt-toolbar'l>" +
                        "<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'f>" +
                        ">" +
                        "<'table-responsive'tr>" +
                        "<'row'" +
                        "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                        "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                        ">",
                    pageLength: 10,
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "No existen registros.",
                        "sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 registros",
                        "sInfoFiltered": "(filtrado de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar: ",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    },
                    columnDefs: [
                        { orderable: false, targets: -1 } // Disable sorting on the last column (Opciones)
                    ]
                });
            };

            return {
                init: function () {
                    if (!table) {
                        return;
                    }
                    initTable();
                }
            };
        }();

        KTUtil.onDOMContentLoaded(function () {
            KTListIncidentsByChild.init();
        });
    </script>
@endsection
