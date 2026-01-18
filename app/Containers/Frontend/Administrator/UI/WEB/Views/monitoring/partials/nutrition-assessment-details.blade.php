<div class="d-flex flex-column">
    <!-- Botón para volver al listado -->
    <div class="mb-5">
        <button type="button" class="btn btn-light kt_btn_back_to_list">
            <i class="ki-outline ki-arrow-left fs-2"></i> Volver al listado
        </button>
    </div>

    <div class="card card-flush mb-6 mb-xl-9">
        <div class="card-header mt-6">
            <div class="card-title flex-column">
                <h2 class="mb-1">Detalle de la Evaluación Nutricional</h2>
                <div class="fs-6 fw-semibold text-muted">
                    Fecha: {{ $item->assessment_date->format('d') }} de {{ \Carbon\Carbon::parse($item->assessment_date)->locale('es')->translatedFormat('F') }} de {{ $item->assessment_date->format('Y') }}
                </div>
            </div>
        </div>

        <div class="card-body p-9 pt-4">
            <h6 class="mb-5 fw-bolder text-primary">Datos registrados en la evaluación</h6>

            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Edad:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ $item->age_readable }}</p>
                </div>
            </div>

            <div class="separator separator-dashed border-muted"></div>

            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Peso:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ $item->weight ? number_format($item->weight, 2) . ' kg' : '-' }}</p>
                </div>
            </div>

            <div class="separator separator-dashed border-muted"></div>

            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Talla/Longitud:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ $item->height ? number_format($item->height, 2) . ' cm' : '-' }}</p>
                </div>
            </div>

            @if($item->head_circumference)
            <div class="separator separator-dashed border-muted"></div>
            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Perímetro Cefálico:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ number_format($item->head_circumference, 2) }} cm</p>
                </div>
            </div>
            @endif

            @if($item->arm_circumference)
            <div class="separator separator-dashed border-muted"></div>
            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Perímetro Braquial:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ number_format($item->arm_circumference, 2) }} cm</p>
                </div>
            </div>
            @endif

            <div class="separator separator-dashed border-muted"></div>

            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">IMC:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ $item->bmi ? number_format($item->bmi, 2) . ' kg/m²' : '-' }}</p>
                </div>
            </div>

            @php
                $criticalStatus = $item->getMostCriticalStatus();
                $statusColor = $criticalStatus?->color() ?? 'secondary';
                $alertClass = match($statusColor) {
                    'red' => 'alert-danger',
                    'orange' => 'alert-warning',
                    'yellow' => 'alert-info',
                    'green' => 'alert-success',
                    default => 'alert-secondary'
                };
                $iconClass = match($statusColor) {
                    'red' => 'ki-shield-cross',
                    'orange' => 'ki-information-5',
                    'yellow' => 'ki-information-5',
                    'green' => 'ki-shield-tick',
                    default => 'ki-information-5'
                };
                $iconColor = match($statusColor) {
                    'red' => 'text-danger',
                    'orange' => 'text-warning',
                    'yellow' => 'text-info',
                    'green' => 'text-success',
                    default => 'text-secondary'
                };
                $titleColor = match($statusColor) {
                    'red' => 'text-danger',
                    'orange' => 'text-warning',
                    'yellow' => 'text-info',
                    'green' => 'text-success',
                    default => 'text-secondary'
                };
            @endphp

            <div class="separator separator-dashed border-muted my-10"></div>

            <h6 class="mb-5 mt-10 fw-bolder text-primary">Estado general</h6>

            <div class="row">
                <div class="col-md-12 d-flex align-items-center mt-5">
                    <div class="flex-grow-1">
                        @if($criticalStatus)
                        <div class="alert {{ $alertClass }} d-flex align-items-center p-5">
                            <i class="ki-duotone {{ $iconClass }} fs-2hx {{ $iconColor }} me-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 {{ $titleColor }}">{{ $item->getMostCriticalStatusLabel() }}</h4>
                                <span>{{ $item->requiresAttention() ? 'El infante requiere atención nutricional' : 'Estado nutricional dentro de parámetros normales' }}</span>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-secondary d-flex align-items-center p-5">
                            <i class="ki-duotone ki-information-5 fs-2hx text-secondary me-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-secondary">Sin clasificación</h4>
                                <span>No se pudo determinar el estado nutricional</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <h6 class="mb-5 mt-10 fw-bolder text-primary">Resultados por indicador</h6>

            <div class="row">
                @php
                    $indicators = [
                        [
                            'id' => 'weight_age',
                            'title' => 'Peso para la Edad',
                            'status' => $item->status_weight_age,
                            'z_score' => $item->z_weight_age,
                            'label_method' => 'labelForWeightForAge',
                            'interpretation_method' => 'interpretationForWeightForAge',
                            'icon' => 'ki-chart-line-down'
                        ],
                        [
                            'id' => 'height_age',
                            'title' => 'Longitud/Talla para la Edad',
                            'status' => $item->status_height_age,
                            'z_score' => $item->z_height_age,
                            'label_method' => 'labelForHeightForAge',
                            'interpretation_method' => 'interpretationForHeightForAge',
                            'icon' => 'ki-chart-line-down'
                        ],
                        [
                            'id' => 'weight_height',
                            'title' => 'Peso para la Talla/Longitud',
                            'status' => $item->status_weight_height,
                            'z_score' => $item->z_weight_height,
                            'label_method' => 'labelForWeightForHeight',
                            'interpretation_method' => 'interpretationForWeightForHeight',
                            'icon' => 'ki-chart-line-down'
                        ],
                        [
                            'id' => 'bmi_age',
                            'title' => 'IMC para la Edad',
                            'status' => $item->status_bmi_age,
                            'z_score' => $item->z_bmi_age,
                            'label_method' => 'labelForBmiForAge',
                            'interpretation_method' => 'interpretationForBmiForAge',
                            'icon' => 'ki-chart-line-down'
                        ]
                    ];
                @endphp

                @foreach($indicators as $indicator)
                    @if($indicator['status'])
                        @php
                            $indStatus = $indicator['status'];
                            $indColor = $indStatus->color();
                            $indBadgeClass = match($indColor) {
                                'red' => 'badge-light-danger',
                                'orange' => 'badge-light-warning',
                                'yellow' => 'badge-light-info',
                                'green' => 'badge-light-success',
                                default => 'badge-light-secondary'
                            };
                            $indBgClass = match($indColor) {
                                'red' => 'bg-light-danger',
                                'orange' => 'bg-light-warning',
                                'yellow' => 'bg-light-info',
                                'green' => 'bg-light-success',
                                default => 'bg-light-secondary'
                            };
                            $indTextColor = match($indColor) {
                                'red' => 'text-danger',
                                'orange' => 'text-warning',
                                'yellow' => 'text-info',
                                'green' => 'text-success',
                                default => 'text-secondary'
                            };
                            $indIconClass = match($indColor) {
                                'red' => 'fas fa-exclamation-triangle',
                                'orange' => 'fas fa-exclamation-circle',
                                'yellow' => 'fas fa-info-circle',
                                'green' => 'fas fa-check-circle',
                                default => 'fas fa-question-circle'
                            };
                            $label = $indStatus->{$indicator['label_method']}();
                            $interpretation = $indStatus->{$indicator['interpretation_method']}();
                        @endphp
                        <div class="col-12">
                            <div class="card card-dashed mb-5">
                                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_indicator_{{ $indicator['id'] }}">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline {{ $indicator['icon'] }} fs-2hx {{ $indTextColor }} me-4"></i>
                                            <div class="d-flex flex-column">
                                                <h4 class="mb-1 text-dark">{{ $indicator['title'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-toolbar rotate-180">
                                        <i class="ki-duotone ki-down fs-1"></i>
                                    </div>
                                </div>
                                <div id="kt_indicator_{{ $indicator['id'] }}" class="collapse show__">
                                    <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                                        <div class="symbol symbol-65px symbol-circle mb-5">
                                            <span class="symbol-label fs-2x fw-bold {{ $indBgClass }}">
                                                <i class="{{ $indIconClass }} fs-2hx {{ $indTextColor }}"></i>
                                            </span>
                                        </div>
                                        <a href="#" class="fs-4 {{ $indTextColor }} fw-bolder mb-0">{{ $label }}</a>
                                        <div class="fw-bold text-gray-400 mb-6">{{ $interpretation }}</div>
                                        @if($indicator['z_score'] !== null)
                                        <div class="d-flex flex-center flex-wrap">
                                            <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                                <div class="fs-6 fw-bolder text-gray-700 text-center">{{ number_format($indicator['z_score'], 2) }}</div>
                                                <div class="fw-bold text-gray-400">Z-score</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            @if($item->observations || $item->recommendations || $item->actions_taken)
            <div class="separator separator-dashed border-muted my-10"></div>

            <h6 class="mb-5 mt-10 fw-bolder text-primary">Observaciones y recomendaciones</h6>

            @if($item->observations)
            <div class="row mb-5">
                <div class="col-md-12">
                    <label class="fw-semibold fs-7 text-gray-600 mb-2">Observaciones:</label>
                    <div class="form-control form-control-plaintext" style="min-height: 80px;">{{ $item->observations }}</div>
                </div>
            </div>
            @endif

            @if($item->recommendations)
            <div class="row">
                <div class="col-md-12">
                    <label class="fw-semibold fs-7 text-gray-600 mb-2">Recomendaciones:</label>
                    <div class="form-control form-control-plaintext" style="min-height: 80px;">{{ $item->recommendations }}</div>
                </div>
            </div>
            @endif

            @if($item->actions_taken)
            <div class="row mt-5">
                <div class="col-md-12">
                    <label class="fw-semibold fs-7 text-gray-600 mb-2">Acciones tomadas:</label>
                    <div class="form-control form-control-plaintext" style="min-height: 80px; white-space: pre-wrap;">{{ $item->actions_taken }}</div>
                </div>
            </div>
            @endif
            @endif

            @if($item->next_assessment_date)
            <div class="separator separator-dashed border-muted my-10"></div>

            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Próxima evaluación:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">
                        {{ $item->next_assessment_date->format('d') }} de 
                        {{ \Carbon\Carbon::parse($item->next_assessment_date)->locale('es')->translatedFormat('F') }} de 
                        {{ $item->next_assessment_date->format('Y') }}
                    </p>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
