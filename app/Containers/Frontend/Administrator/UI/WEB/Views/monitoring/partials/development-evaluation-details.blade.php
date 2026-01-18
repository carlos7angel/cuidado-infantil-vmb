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
                <h2 class="mb-1">Detalle de la Evaluación de Desarrollo Infantil</h2>
                <div class="fs-6 fw-semibold text-muted">
                    Fecha: {{ $item->evaluation_date->format('d') }} de {{ \Carbon\Carbon::parse($item->evaluation_date)->locale('es')->translatedFormat('F') }} de {{ $item->evaluation_date->format('Y') }}
                </div>
            </div>
        </div>

        <div class="card-body p-9 pt-4">
            <h6 class="mb-5 fw-bolder text-primary">Datos registrados en la evaluación</h6>

            <div class="row mb-3">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Fecha de Evaluación:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">
                        {{ \Carbon\Carbon::parse($item->evaluation_date)->locale('es')->translatedFormat('d \de F \de Y') }}
                    </p>
                </div>
            </div>

            <div class="separator separator-dashed border-muted"></div>

            <div class="row mb-3">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Edad:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ $item->age_readable }}</p>
                </div>
            </div>

            @if($item->weight)
            <div class="separator separator-dashed border-muted"></div>
            <div class="row mb-3">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Peso:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ number_format($item->weight, 2) }} Kg</p>
                </div>
            </div>
            @endif

            @if($item->height)
            <div class="separator separator-dashed border-muted"></div>
            <div class="row mb-3">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Talla/Longitud:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">{{ number_format($item->height, 2) }} cm</p>
                </div>
            </div>
            @endif

            @php
                $overallStatus = $item->getOverallStatus();
                $statusColor = match($overallStatus) {
                    'alert' => 'red',
                    'review' => 'yellow',
                    'normal' => 'green',
                    default => 'secondary'
                };
                $alertClass = match($statusColor) {
                    'red' => 'alert-danger',
                    'yellow' => 'alert-warning',
                    'green' => 'alert-success',
                    default => 'alert-secondary'
                };
                $iconClass = match($statusColor) {
                    'red' => 'ki-shield-cross text-danger',
                    'yellow' => 'ki-information text-warning',
                    'green' => 'ki-shield-tick text-success',
                    default => 'ki-information text-secondary'
                };
                $statusLabel = match($overallStatus) {
                    'alert' => 'Alerta',
                    'review' => 'Revisar',
                    'normal' => 'Normal',
                    default => 'Sin clasificación'
                };
                $statusDescription = match($overallStatus) {
                    'alert' => 'El infante requiere atención inmediata en una o más áreas de desarrollo.',
                    'review' => 'El infante presenta algunas áreas que requieren seguimiento.',
                    'normal' => 'Desarrollo dentro de parámetros normales en todas las áreas.',
                    default => 'No se pudo determinar el estado del desarrollo.'
                };
            @endphp

            <div class="separator separator-dashed border-muted my-10"></div>

            <h6 class="mb-5 mt-10 fw-bolder text-primary">Estado general</h6>

            <div class="row">
                <div class="col-md-12 d-flex align-items-center mt-5">
                    <div class="flex-grow-1">
                        <div class="alert {{ $alertClass }} d-flex align-items-center p-5">
                            <i class="ki-duotone {{ $iconClass }} fs-2hx me-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-dark">{{ $statusLabel }}</h4>
                                <span>{{ $statusDescription }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5 mt-10">
                <h6 class="fw-bolder text-primary mb-0">Resultados por Área de Desarrollo</h6>
                <button type="button" 
                        class="btn btn-sm btn-light-primary kt_btn_view_indicators" 
                        data-url="{{ route('admin.monitoring.get-development-evaluation-indicators', ['development_evaluation_id' => $item->getHashedKey()]) }}">
                    <i class="ki-outline ki-eye fs-2"></i> Ver indicadores
                </button>
            </div>

            <div class="row">
                @php
                    use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea;
                    $areaIcons = [
                        DevelopmentArea::MOTOR_GROSS->value => 'ki-graph-3',
                        DevelopmentArea::MOTOR_FINE->value => 'ki-color-swatch',
                        DevelopmentArea::LANGUAGE->value => 'ki-speaker',
                        DevelopmentArea::PERSONAL_SOCIAL->value => 'ki-profile-user',
                    ];
                    $areas = [];
                    foreach (DevelopmentArea::cases() as $area) {
                        $areas[$area->value] = [
                            'label' => $area->label(),
                            'icon' => $areaIcons[$area->value] ?? 'ki-information',
                        ];
                    }
                    $scoresByArea = $item->scores->keyBy('area');
                @endphp

                @foreach($areas as $areaCode => $areaInfo)
                    @php
                        $score = $scoresByArea->get($areaCode);
                    @endphp
                    @if($score)
                        @php
                            $indStatus = $score->status;
                            $indColor = $indStatus->color();
                            $indBadgeClass = match($indColor) {
                                'red' => 'badge-light-danger',
                                'yellow' => 'badge-light-warning',
                                'blue' => 'badge-light-info',
                                'green' => 'badge-light-success',
                                default => 'badge-light-secondary'
                            };
                            $indBgClass = match($indColor) {
                                'red' => 'bg-light-danger',
                                'yellow' => 'bg-light-warning',
                                'blue' => 'bg-light-info',
                                'green' => 'bg-light-success',
                                default => 'bg-light-secondary'
                            };
                            $indTextColor = match($indColor) {
                                'red' => 'text-danger',
                                'yellow' => 'text-warning',
                                'blue' => 'text-info',
                                'green' => 'text-success',
                                default => 'text-secondary'
                            };
                            $indIconClass = match($indColor) {
                                'red' => 'fas fa-exclamation-triangle',
                                'yellow' => 'fas fa-exclamation-circle',
                                'blue' => 'fas fa-info-circle',
                                'green' => 'fas fa-check-circle',
                                default => 'fas fa-question-circle'
                            };
                            $label = $indStatus->label();
                            $description = $indStatus->description();
                            $maxScore = $score->getMaxPossibleScore($item->age_months);
                            $percentage = $score->getPercentage($item->age_months);
                        @endphp
                        <div class="col-12">
                            <div class="card card-dashed mb-5">
                                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_area_{{ $areaCode }}">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline {{ $areaInfo['icon'] }} fs-2hx {{ $indTextColor }} me-4"></i>
                                            <div class="d-flex flex-column">
                                                <h4 class="mb-1 text-dark">{{ $areaInfo['label'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <i class="ki-duotone ki-down fs-1"></i>
                                    </div>
                                </div>
                                <div id="kt_area_{{ $areaCode }}" class="collapse">
                                    <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                                        <div class="symbol symbol-65px symbol-circle mb-5">
                                            <span class="symbol-label fs-2x fw-bold {{ $indBgClass }}">
                                                <i class="{{ $indIconClass }} fs-2hx {{ $indTextColor }}"></i>
                                            </span>
                                        </div>
                                        <a href="#" class="fs-4 {{ $indTextColor }} fw-bolder mb-0">{{ $label }}</a>
                                        <div class="fw-bold text-gray-400 mb-6">{{ $description }}</div>
                                        <div class="d-flex flex-center flex-wrap">
                                            <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                                <div class="fs-6 fw-bolder text-gray-700 text-center">{{ $score->raw_score }}/{{ $maxScore > 0 ? $maxScore : 'NA' }}</div>
                                                <div class="fw-bold text-gray-400">Puntaje</div>
                                            </div>
                                            
                                            <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                                <div class="fs-6 fw-bolder text-gray-700 text-center">4</div>
                                                <div class="fw-bold text-gray-400">Logrados</div>
                                            </div>

                                            <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                                <div class="fs-6 fw-bolder text-gray-700 text-center">12</div>
                                                <div class="fw-bold text-gray-400">No Logrados</div>
                                            </div>
                                            
                                            @if($percentage !== null)
                                            <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                                <div class="fs-6 fw-bolder text-gray-700 text-center">{{ number_format($percentage, 1) }}%</div>
                                                <div class="fw-bold text-gray-400">Porcentaje</div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            @if($item->notes || $item->actions_taken)
            <div class="separator separator-dashed border-muted my-10"></div>

            <h6 class="mb-5 mt-10 fw-bolder text-primary">Notas</h6>

            @if($item->notes)
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="fw-semibold fs-7 text-gray-600 mb-2">Observaciones:</label>
                    <div class="form-control form-control-plaintext" style="min-height: 80px;">{{ $item->notes }}</div>
                </div>
            </div>
            @endif

            @if($item->actions_taken)
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="fw-semibold fs-7 text-gray-600 mb-2">Acciones tomadas:</label>
                    <div class="form-control form-control-plaintext" style="min-height: 80px; white-space: pre-wrap;">{{ $item->actions_taken }}</div>
                </div>
            </div>
            @endif
            @endif

            @if($item->next_evaluation_date)
            <div class="separator separator-dashed border-muted my-10"></div>

            <div class="row">
                <div class="col-md-4 d-flex align-items-center justify-content-end text-end">
                    <label class="fw-semibold fs-7 text-gray-600">Próxima evaluación:</label>
                </div>
                <div class="col-md-8">
                    <p class="form-control form-control-plaintext">
                        {{ $item->next_evaluation_date->format('d') }} de 
                        {{ \Carbon\Carbon::parse($item->next_evaluation_date)->locale('es')->translatedFormat('F') }} de 
                        {{ $item->next_evaluation_date->format('Y') }}
                    </p>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

