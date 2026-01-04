@php
    $vaccine = $vaccineData['vaccine'];
    $doses = $vaccineData['doses'];
    $progress = $vaccineData['progress'];
@endphp

<div class="d-flex flex-column">
    <!-- Botón para volver al listado -->
    <div class="mb-5">
        <button type="button" class="btn btn-light kt_btn_back_to_vaccines_list">
            <i class="ki-outline ki-arrow-left fs-2"></i> Volver al listado
        </button>
    </div>

    <div class="card card-flush mb-6 mb-xl-9">
        <div class="card-header mt-6">
            <div class="card-title flex-column">
                <h2 class="mb-1">Vacuna: {{ $vaccine->name }}</h2>
                <div class="fs-6 fw-semibold text-muted">
                    Total {{ $progress['total'] }} {{ $progress['total'] === 1 ? 'dosis' : 'dosis' }}
                </div>
            </div>
        </div>
        <div class="card-body p-9 pt-4">
            
            @if($vaccine->description)
            <div class="mb-10">
                <p class="text-gray-700 fs-6">{{ $vaccine->description }}</p>
            </div>
            @endif

            @foreach($doses as $doseData)
                @php
                    $dose = $doseData['dose'];
                    $status = $doseData['status'];
                    $statusLabel = $doseData['status_label'];
                    $statusColor = $doseData['status_color'];
                    $childVaccination = $doseData['child_vaccination'];
                    
                    $badgeClass = match($statusColor) {
                        'success' => 'badge-light-success',
                        'warning' => 'badge-light-warning',
                        'danger' => 'badge-light-danger',
                        'info' => 'badge-light-info',
                        'primary' => 'badge-light-primary',
                        default => 'badge-light-secondary'
                    };
                    
                    $iconBgClass = match($statusColor) {
                        'success' => 'bg-success',
                        'warning' => 'bg-warning',
                        'danger' => 'bg-danger',
                        'info' => 'bg-info',
                        'primary' => 'bg-primary',
                        default => 'bg-secondary'
                    };
                @endphp
                
                <div class="card card-dashed h-md-100 mb-5">
                    <div class="card-body p-5 py-9">
                        <div class="row gx-9 h-100">
                            <div class="col-12">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-7">
                                        <div class="d-flex flex-stack mb-6">
                                            <div class="flex-shrink-0 me-5">
                                                <span class="text-gray-500 fs-7 fw-bold me-2 d-block lh-1 pb-1">
                                                    @if($dose->dose_number === 1)
                                                        Primera dosis
                                                    @elseif($dose->dose_number === 2)
                                                        Segunda dosis
                                                    @elseif($dose->dose_number === 3)
                                                        Tercera dosis
                                                    @else
                                                        Dosis {{ $dose->dose_number }}
                                                    @endif
                                                </span>
                                                <span class="text-gray-800 fs-1 fw-bold">Dosis {{ $dose->dose_number }}</span>
                                            </div>
                                            <span class="badge {{ $badgeClass }} flex-shrink-0 align-self-center py-3 px-4 fs-7">{{ $statusLabel }}</span>
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap d-grid gap-2">
                                            <div class="d-flex align-items-center me-5 me-xl-13">
                                                <div class="symbol symbol-30px symbol-circle me-3">
                                                    <span class="symbol-label {{ $iconBgClass }}">
                                                        <i class="ki-outline ki-calendar fs-5 text-white"></i>
                                                    </span>
                                                </div>
                                                <div class="m-0">
                                                    <span class="fw-semibold text-gray-500 d-block fs-8">Edad recomendada</span>
                                                    <span class="fw-bold text-gray-800 fs-7">{{ $dose->recommended_age }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-30px symbol-circle me-3">
                                                    <span class="symbol-label {{ $iconBgClass }}">
                                                        <i class="ki-outline ki-calendar-tick fs-5 text-white"></i>
                                                    </span>
                                                </div>
                                                <div class="m-0">
                                                    <span class="fw-semibold text-gray-500 d-block fs-8">Rango de edad</span>
                                                    <span class="fw-bold text-gray-800 fs-7">{{ $dose->age_range }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($childVaccination)
                                    <div class="mb-6__">
                                        @if($childVaccination->notes)
                                        <span class="fw-semibold text-gray-600 fs-6 mb-8 d-block">{{ $childVaccination->notes }}</span>
                                        @endif
                                        <div class="d-flex flex-wrap gap-3">
                                            <div class="border border-gray-300 border-dashed rounded min-w-150px py-2 px-4">
                                                <span class="fs-6 text-gray-700 fw-bold">
                                                    {{ $childVaccination->date_applied->locale('es')->translatedFormat('d \de F \de Y') }}
                                                </span>
                                                <div class="fw-semibold text-gray-500">Fecha de aplicación</div>
                                            </div>
                                            @if($childVaccination->applied_at)
                                            <div class="border border-gray-300 border-dashed rounded min-w-150px py-2 px-4">
                                                <span class="fs-6 text-gray-700 fw-bold">{{ $childVaccination->applied_at }}</span>
                                                <div class="fw-semibold text-gray-500">Lugar</div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                        @if($dose->description)
                                        <div class="mb-6">
                                            <span class="fw-semibold text-gray-600 fs-6 mb-8 d-block">{{ $dose->description }}</span>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

