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
            <a class="text-white text-hover-secondary">Inicio</a>
        </li>
    </ul>
@endsection

@section('headline')
    <div class="page-title d-flex align-items-center me-3">
        <h1 class="page-heading d-flex text-white fw-bolder fs-1 flex-column justify-content-center my-0">
            INICIO
            <span class="page-desc text-white opacity-50 fs-6 fw-bold pt-3">Panel de Control</span>
        </h1>
    </div>
    <div class="d-flex gap-4 gap-lg-13">
    </div>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content">

        <!-- Saludo de Bienvenida -->
        <div class="row gy-5 gx-xl-10 mb-5">
            <div class="col-12">
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                    <i class="ki-duotone ki-abstract-26 fs-3tx text-primary me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h3 class="text-gray-900 fw-bold mb-2">{{ $greeting }}</h3>
                            <p class="text-gray-700 mb-0">Bienvenido al Sistema de Gestión y Monitoreo de Cuidado Infantil. Aquí tienes un resumen de la información más relevante del sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas Principales -->
        <div class="row gy-5 gx-xl-10 mb-5">
            <!-- Total de Infantes -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-abstract-28 fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Total de Infantes</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['total_children']) }}</span>
                            @if($stats['children_growth']['percentage'] > 0)
                                <span class="text-success fs-7 fw-semibold">
                                    <i class="ki-duotone ki-arrow-up fs-8">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    {{ $stats['children_growth']['percentage'] }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Centros de Cuidado -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-home-2 fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Centros de Cuidado</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['total_centers']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inscripciones Activas -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-duotone ki-abstract-38 fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Inscripciones Activas</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['active_enrollments']) }}</span>
                            @if($stats['enrollments_growth']['percentage'] > 0)
                                <span class="text-success fs-7 fw-semibold">
                                    <i class="ki-duotone ki-arrow-up fs-8">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    {{ $stats['enrollments_growth']['percentage'] }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asistencias de Hoy -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-calendar-tick fs-2x text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Asistencias Hoy</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['today_attendances']) }}</span>
                            <span class="text-gray-600 fs-7">{{ number_format($stats['today_present']) }} presentes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Fila de Estadísticas -->
        <div class="row gy-5 gx-xl-10 mb-5">
            <!-- Tasa de Asistencia -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column p-6">
                        <div class="d-flex align-items-center mb-4">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-light-danger">
                                    <i class="ki-duotone ki-chart-simple fs-2x text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-500 fw-semibold fs-6 mb-1">Tasa de Asistencia</span>
                                <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['attendance_rate'], 1) }}%</span>
                            </div>
                        </div>
                        <div class="progress h-6px">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min($stats['attendance_rate'], 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluaciones Nutricionales -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-health fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Evaluaciones Nutricionales</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['total_nutritional_assessments']) }}</span>
                            <span class="text-gray-600 fs-7">{{ number_format($stats['recent_nutritional_assessments']) }} este mes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluaciones de Desarrollo -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-abstract-26 fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Evaluaciones de Desarrollo</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['total_development_evaluations']) }}</span>
                            <span class="text-gray-600 fs-7">{{ number_format($stats['recent_development_evaluations']) }} este mes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reportes de Incidentes -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-information-5 fs-2x text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Reportes de Incidentes</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['total_incidents']) }}</span>
                            @if($stats['active_incidents'] > 0)
                                <span class="text-danger fs-7 fw-semibold">{{ number_format($stats['active_incidents']) }} activos</span>
                            @else
                                <span class="text-gray-600 fs-7">{{ number_format($stats['recent_incidents']) }} este mes</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera Fila - Vacunaciones -->
        <div class="row gy-5 gx-xl-10 mb-5">
            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-duotone ki-medicine-bottle fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-500 fw-semibold fs-6 mb-1">Vacunaciones Registradas</span>
                            <span class="text-gray-900 fw-bold fs-2x">{{ number_format($stats['total_vaccinations']) }}</span>
                            <span class="text-gray-600 fs-7">{{ number_format($stats['recent_vaccinations']) }} este mes</span>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Información del Sistema -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card h-100">
                    <div class="card-body p-6">
                        <h4 class="text-gray-900 fw-bold mb-4">Sobre el Sistema</h4>
                        <p class="text-gray-700 fs-6 mb-3">
                            Sistema de Gestión y Monitoreo de Cuidado Infantil diseñado para centralizar y gestionar toda la información relacionada con el cuidado, desarrollo y seguimiento de infantes en centros de cuidado infantil.
                        </p>
                        <p class="text-gray-700 fs-6 mb-0">
                            El sistema permite registrar y monitorear asistencias, evaluaciones nutricionales y de desarrollo, vacunaciones, reportes de incidentes y toda la información relevante para el bienestar de los infantes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection