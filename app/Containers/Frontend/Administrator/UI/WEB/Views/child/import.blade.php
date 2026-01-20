@extends('vendor@template::admin.layouts.master', ['page' => 'child_import'])

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
            <a class="text-white text-hover-secondary">Infantes</a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
        </li>
        <li class="breadcrumb-item text-white fw-bold lh-1">
            <a class="text-white text-hover-secondary">Importar</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            IMPORTAR INFANTES
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Carga masiva desde Excel</span>
        </h1>
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3">Seleccionar Archivo</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Sube el archivo Excel con los datos de los infantes</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ asset('themes/common/docs/modelo_infantes.xlsx') }}" class="btn btn-sm btn-light-primary" download>
                        <i class="ki-outline ki-file-down fs-2"></i> Descargar Modelo
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <form id="kt_import_form" class="form" enctype="multipart/form-data">
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label class="d-block fw-semibold fs-6 mb-3">Centro de Cuidado Infantil</label>
                            <select name="childcare_center_id" id="childcare_center_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccionar centro...">
                                <option></option>
                                @foreach($childcareCenters as $center)
                                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="d-block fw-semibold fs-6 mb-3">Sala (Opcional)</label>
                            <select name="room_id" id="room_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccionar sala...">
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="d-block fw-semibold fs-6 mb-5">Archivo Excel (.xlsx, .xls)</label>
                        <input type="file" name="file" class="form-control form-control-solid mb-3 mb-lg-0" accept=".xlsx, .xls, .csv" />
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="kt_preview_btn" class="btn btn-primary">
                            <span class="indicator-label">Previsualizar Datos</span>
                            <span class="indicator-progress">Procesando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="kt_preview_section" class="card card-flush mt-5 d-none">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3">Previsualización</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Revisa los datos antes de importar. Las filas con errores serán omitidas.</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" id="kt_import_btn" class="btn btn-success">
                        <i class="ki-outline ki-check fs-2"></i> Confirmar e Importar
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_preview_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">Nombres</th>
                                <th class="min-w-100px">Apellidos</th>
                                <th class="min-w-100px">Género</th>
                                <th class="min-w-100px">F. Nacimiento</th>
                                <th class="min-w-100px">Dirección</th>
                                <th class="min-w-100px">Ubicación</th>
                                <th class="min-w-100px">Seguro</th>
                                <th class="min-w-50px">Peso</th>
                                <th class="min-w-50px">Talla</th>
                                <th class="min-w-50px">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600" id="kt_preview_table_body">
                            <!-- Rows will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="kt_results_section" class="card card-flush mt-5 d-none">
             <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3">Resultados de Importación</span>
                    </h3>
                </div>
            </div>
            <div class="card-body pt-0" id="kt_results_body">
                
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('themes/admin/js/custom/child/import.js') }}"></script>
@endsection
