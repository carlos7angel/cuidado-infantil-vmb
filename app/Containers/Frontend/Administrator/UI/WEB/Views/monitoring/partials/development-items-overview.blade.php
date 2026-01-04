@php
    use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea;
    
    // Agrupar por área para calcular resúmenes
    $itemsByArea = $itemsHistory->groupBy(function ($item) {
        return $item->development_item->area->value;
    });
    
    // Organizar por área y luego por item_number dentro de cada área
    // Luego crear filas combinando items de todas las áreas por posición/edad
    $itemsByAreaSorted = [];
    $areaOrder = [
        DevelopmentArea::MOTOR_GROSS->value,
        DevelopmentArea::MOTOR_FINE->value,
        DevelopmentArea::LANGUAGE->value,
        DevelopmentArea::PERSONAL_SOCIAL->value,
    ];
    
    foreach ($areaOrder as $areaCode) {
        $areaItems = $itemsByArea->get($areaCode, collect());
        $itemsByAreaSorted[$areaCode] = $areaItems->sortBy(function ($item) {
            return [$item->development_item->age_max_months, $item->development_item->item_number];
        })->values();
    }
    
    // Obtener el máximo de items en cualquier área para saber cuántas filas necesitamos
    $maxItems = max(array_map(function ($items) {
        return $items->count();
    }, $itemsByAreaSorted));
    
    // Calcular resúmenes por área usando label() del enum
    $areaSummaries = [];
    foreach ($areaOrder as $areaCode) {
        $areaEnum = DevelopmentArea::tryFrom($areaCode);
        $areaItems = $itemsByArea->get($areaCode, collect());
        $areaSummaries[$areaCode] = [
            'label' => $areaEnum?->label() ?? $areaCode,
            'total' => $areaItems->count(),
            'evaluable' => $areaItems->where('is_evaluable', true)->count(),
            'achieved' => $areaItems->where('achieved', true)->count(),
            'not_achieved' => $areaItems->where('is_evaluable', true)->where('achieved', false)->count(),
            'not_evaluable' => $areaItems->where('is_evaluable', false)->count(),
        ];
    }
@endphp

<div class="d-flex flex-column">
    <div class="mb-5">
        <h3 class="mb-1">Indicadores de Desarrollo Infantil</h3>
        <div class="fs-6 fw-semibold text-muted">
            Evaluación del {{ \Carbon\Carbon::parse($item->evaluation_date)->locale('es')->translatedFormat('d \de F \de Y') }} - Edad: {{ $item->age_readable }}
        </div>
    </div>

    <div class="card card-flush">
        <div class="card-header">
            <div class="card-title">
                <h4 class="mb-1">Resumen por Área</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-5">
                @foreach($areaOrder as $areaCode)
                    @php
                        $summary = $areaSummaries[$areaCode];
                    @endphp
                    <div class="col-md-3">
                        <div class="border border-dashed border-gray-300 rounded p-4">
                            <div class="fw-bold fs-6 mb-2">{{ $summary['label'] }}</div>
                            <div class="fs-7 text-muted">
                                <div>Total: <span class="fw-bold text-gray-800">{{ $summary['total'] }}</span></div>
                                <div>Evaluables: <span class="fw-bold text-gray-800">{{ $summary['evaluable'] }}</span></div>
                                <div>Logrados: <span class="fw-bold text-success">{{ $summary['achieved'] }}</span></div>
                                <div>No logrados: <span class="fw-bold text-danger">{{ $summary['not_achieved'] }}</span></div>
                                <div>Aún no evaluables: <span class="fw-bold text-muted">{{ $summary['not_evaluable'] }}</span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card card-flush mt-5">
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto; max-height: 600px;">
                <table class="table table-row-bordered table-row-dashed align-middle" style="min-width: 1400px;">
                    <thead class="sticky-top bg-light">
                        <tr class="fw-bold fs-7 text-gray-800 text-start">
                            <th class="min-w-60px bg-white sticky-left z-index-1">#</th>
                            <th class="min-w-100px bg-white sticky-left-60 z-index-1">Edad (meses)</th>
                            @foreach($areaOrder as $areaCode)
                                <th class="min-w-350px text-center" style="border-left: 2px solid #e4e6ef;">
                                    <div class="fw-bold fs-6 mb-1">{{ $areaSummaries[$areaCode]['label'] }}</div>
                                    <div class="fs-8 text-muted">
                                        Total: {{ $areaSummaries[$areaCode]['total'] }} | 
                                        Logrados: <span class="text-success fw-bold">{{ $areaSummaries[$areaCode]['achieved'] }}</span> | 
                                        No logrados: <span class="text-danger fw-bold">{{ $areaSummaries[$areaCode]['not_achieved'] }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @for($rowIndex = 0; $rowIndex < $maxItems; $rowIndex++)
                            <tr>
                                @php
                                    // Obtener el item de la primera área para mostrar # y edad en la primera columna
                                    $firstAreaItem = $itemsByAreaSorted[$areaOrder[0]]->get($rowIndex);
                                @endphp
                                <td class="fw-semibold bg-white sticky-left z-index-1">
                                    @if($firstAreaItem)
                                        {{ $firstAreaItem->development_item->item_number }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="fw-semibold bg-white sticky-left-60 z-index-1">
                                    @if($firstAreaItem)
                                        @php
                                            $ageItem = $firstAreaItem->development_item;
                                            $ageText = $ageItem->age_min_months == $ageItem->age_max_months 
                                                ? $ageItem->age_max_months 
                                                : $ageItem->age_min_months . '-' . $ageItem->age_max_months;
                                        @endphp
                                        {{ $ageText }}
                                    @else
                                        -
                                    @endif
                                </td>
                                @foreach($areaOrder as $areaCode)
                                    @php
                                        $itemData = $itemsByAreaSorted[$areaCode]->get($rowIndex);
                                    @endphp
                                    <td class="text-start" style="border-left: 2px solid #e4e6ef;">
                                        @if($itemData)
                                            @php
                                                $developmentItem = $itemData->development_item;
                                                $achieved = $itemData->achieved;
                                                $isEvaluable = $itemData->is_evaluable;
                                                
                                                if (!$isEvaluable) {
                                                    $cellClass = 'bg-light-secondary';
                                                    $badgeClass = 'badge-light-secondary';
                                                    $badgeText = 'Aún no evaluable';
                                                    $iconClass = 'ki-lock text-secondary';
                                                    $textClass = 'text-muted';
                                                } elseif ($achieved) {
                                                    $cellClass = 'bg-light-success';
                                                    $badgeClass = 'badge-light-success';
                                                    $badgeText = 'Logrado';
                                                    $iconClass = 'ki-check-circle text-success';
                                                    $textClass = 'text-gray-800';
                                                } else {
                                                    $cellClass = 'bg-light-danger';
                                                    $badgeClass = 'badge-light-danger';
                                                    $badgeText = 'No logrado';
                                                    $iconClass = 'ki-cross-circle text-danger';
                                                    $textClass = 'text-gray-800';
                                                }
                                            @endphp
                                            <div class="p-3 {{ $cellClass }} rounded">
                                                <div class="fw-semibold {{ $textClass }} mb-2">{{ $developmentItem->description }}</div>
                                                <div class="d-flex justify-content-center">
                                                    <span class="badge {{ $badgeClass }} fs-7 fw-bold">
                                                        <i class="ki-duotone {{ $iconClass }} fs-6 me-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                        {{ $badgeText }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-3 text-muted text-center">-</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .sticky-left {
        position: sticky;
        left: 0;
        z-index: 2;
        box-shadow: 2px 0 4px rgba(0,0,0,0.1);
    }
    .sticky-left-60 {
        position: sticky;
        left: 60px;
        z-index: 2;
        box-shadow: 2px 0 4px rgba(0,0,0,0.1);
    }
    .z-index-1 {
        z-index: 1;
    }
</style>
