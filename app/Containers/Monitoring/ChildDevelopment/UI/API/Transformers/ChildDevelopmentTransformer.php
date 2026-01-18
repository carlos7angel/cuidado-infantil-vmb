<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Transformers;

use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluationScore;
use Vinkla\Hashids\Facades\Hashids;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ChildDevelopmentTransformer extends ParentTransformer
{
    // Incluir items por defecto en el detalle (se puede desactivar con ?include=-items)
    protected array $defaultIncludes = [
        'items',
    ];

    protected array $availableIncludes = [
        'items',
        'scores',
        'child',
        'assessed_by',
    ];

    public function transform(ChildDevelopmentEvaluation $evaluation): array
    {
        $scores = $evaluation->scores->keyBy('area');
        $ageMonths = $evaluation->age_months; // Obtener age_months una vez para reutilizar
        
        // Obtener historial completo mapeado (todos los items acumulados con achieved)
        $completeHistory = $evaluation->getCompleteItemsHistory();
        
        // Agrupar por área para el resumen
        $itemsByArea = $completeHistory->groupBy(function ($item) {
            return $item->development_item->area->value;
        });

        $response = [
            'type' => $evaluation->getResourceKey(),
            'id' => $evaluation->getHashedKey(),
            'child_id' => $evaluation->child_id ? Hashids::encode($evaluation->child_id) : null,
            
            // Datos básicos de la evaluación
            'evaluation_date' => $evaluation->evaluation_date->format('Y-m-d'),
            'evaluation_date_readable' => $evaluation->evaluation_date->format('d/m/Y'),
            'age_months' => $evaluation->age_months,
            'age_readable' => $evaluation->age_readable,
            
            // Evaluador
            'assessed_by' => $evaluation->assessed_by ? [
                'id' => Hashids::encode($evaluation->assessed_by),
                'name' => $evaluation->assessedBy?->name ?? 'N/A',
            ] : null,
            
            // Mediciones antropométricas
            'measurements' => [
                'weight' => $evaluation->weight,
                'height' => $evaluation->height,
                'weight_formatted' => $evaluation->weight ? "{$evaluation->weight} kg" : null,
                'height_formatted' => $evaluation->height ? "{$evaluation->height} cm" : null,
            ],
            
            // Puntajes por área (calculados automáticamente)
            'scores' => [
                'MG' => $this->transformScore($scores->get('MG'), $ageMonths),
                'MF' => $this->transformScore($scores->get('MF'), $ageMonths),
                'AL' => $this->transformScore($scores->get('AL'), $ageMonths),
                'PS' => $this->transformScore($scores->get('PS'), $ageMonths),
            ],
            
            // Resumen de items por área (para vista rápida)
            'items_summary' => [
                'MG' => [
                    'total' => $itemsByArea->get('MG', collect())->count(),
                    'achieved' => $itemsByArea->get('MG', collect())->where('achieved', true)->count(),
                    'not_achieved' => $itemsByArea->get('MG', collect())->where('achieved', false)->count(),
                ],
                'MF' => [
                    'total' => $itemsByArea->get('MF', collect())->count(),
                    'achieved' => $itemsByArea->get('MF', collect())->where('achieved', true)->count(),
                    'not_achieved' => $itemsByArea->get('MF', collect())->where('achieved', false)->count(),
                ],
                'AL' => [
                    'total' => $itemsByArea->get('AL', collect())->count(),
                    'achieved' => $itemsByArea->get('AL', collect())->where('achieved', true)->count(),
                    'not_achieved' => $itemsByArea->get('AL', collect())->where('achieved', false)->count(),
                ],
                'PS' => [
                    'total' => $itemsByArea->get('PS', collect())->count(),
                    'achieved' => $itemsByArea->get('PS', collect())->where('achieved', true)->count(),
                    'not_achieved' => $itemsByArea->get('PS', collect())->where('achieved', false)->count(),
                ],
            ],
            
            // Análisis general
            'overall_score' => $evaluation->getOverallScore(), // Porcentaje promedio de todas las áreas
            'overall_status' => $evaluation->getOverallStatus(), // 'normal'|'review'|'alert'
            'requires_attention' => $evaluation->requiresAttention(),
            'areas_requiring_attention' => $evaluation->getAreasRequiringAttention()
                ->map(fn ($area) => $area->label())
                ->toArray(),
            
            // Observaciones
            'notes' => $evaluation->notes,
            'actions_taken' => $evaluation->actions_taken,
            'next_evaluation_date' => $evaluation->next_evaluation_date?->format('Y-m-d'),
            'next_evaluation_date_readable' => $evaluation->next_evaluation_date?->format('d/m/Y'),
            
            // Metadatos
            'created_at' => $evaluation->created_at,
            'updated_at' => $evaluation->updated_at,
            'readable_created_at' => $evaluation->created_at->diffForHumans(),
            'readable_updated_at' => $evaluation->updated_at->diffForHumans(),
        ];

        // Incluir items agrupados por área (historial completo mapeado)
        $response['items_by_area'] = $this->formatItemsByArea($itemsByArea);

        return $response;
    }

    /**
     * Format items grouped by area.
     */
    private function formatItemsByArea($itemsByArea): array
    {
        $formattedItems = [];
        foreach (\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::cases() as $area) {
            $areaItems = $itemsByArea->get($area->value, collect());
            
            $formattedItems[$area->value] = [
                'area' => $area->value,
                'area_label' => $area->label(),
                'items' => $areaItems->map(function ($item) {
                    return [
                        'id' => $item->evaluation_item_id ? Hashids::encode($item->evaluation_item_id) : null,
                        'development_item_id' => Hashids::encode($item->development_item->id),
                        'item_number' => $item->development_item->item_number,
                        'description' => $item->development_item->description,
                        'age_range' => [
                            'min_months' => $item->development_item->age_min_months,
                            'max_months' => $item->development_item->age_max_months,
                        ],
                        'achieved' => $item->achieved,
                        'achieved_label' => $item->achieved ? 'Logrado' : 'No logrado',
                    ];
                })->sortBy(function ($item) {
                    return $item['item_number'];
                })->values()->toArray(),
                'total' => $areaItems->count(),
                'achieved_count' => $areaItems->where('achieved', true)->count(),
                'not_achieved_count' => $areaItems->where('achieved', false)->count(),
            ];
        }

        return $formattedItems;
    }


    /**
     * Transform a score for a specific area.
     * 
     * @param ChildDevelopmentEvaluationScore|null $score
     * @param int|null $ageMonths Edad en meses para calcular max_possible_score y percentage
     */
    private function transformScore(?ChildDevelopmentEvaluationScore $score, ?int $ageMonths = null): ?array
    {
        if (!$score) {
            return null;
        }

        // Calcular max_possible_score y percentage pasando ageMonths directamente
        // para evitar lazy loading de la relación evaluation
        $maxPossibleScore = $ageMonths !== null ? $score->getMaxPossibleScore($ageMonths) : 0;
        $percentage = $maxPossibleScore > 0 
            ? round(($score->raw_score / $maxPossibleScore) * 100, 2) 
            : null;

        return [
            'area' => $score->area->value,
            'area_label' => $score->area->label(),
            'raw_score' => $score->raw_score,
            'max_possible_score' => $maxPossibleScore,
            'percentage' => $percentage,
            'status' => $score->status->value,
            'status_label' => $score->status->label(),
            'status_description' => $score->status->description(),
            'status_color' => $score->status->color(),
            'requires_attention' => $score->requiresAttention(),
        ];
    }

    /**
     * Include items in the response (historial completo mapeado).
     * Returns all accumulated items with achieved status.
     */
    public function includeItems(ChildDevelopmentEvaluation $evaluation)
    {
        $completeHistory = $evaluation->getCompleteItemsHistory();

        return $this->collection($completeHistory, function ($item) {
            return [
                'id' => $item->evaluation_item_id ? Hashids::encode($item->evaluation_item_id) : null,
                'development_item_id' => Hashids::encode($item->development_item->id),
                'item_number' => $item->development_item->item_number,
                'area' => $item->development_item->area->value,
                'area_label' => $item->development_item->area->label(),
                'description' => $item->development_item->description,
                'age_range' => [
                    'min_months' => $item->development_item->age_min_months,
                    'max_months' => $item->development_item->age_max_months,
                ],
                'achieved' => $item->achieved,
                'achieved_label' => $item->achieved ? 'Logrado' : 'No logrado',
            ];
        });
    }

    /**
     * Include scores in the response (already included in main transform, but available as separate include).
     */
    public function includeScores(ChildDevelopmentEvaluation $evaluation)
    {
        return $this->collection($evaluation->scores, function ($score) {
            return $this->transformScore($score);
        });
    }

    /**
     * Include child in the response.
     */
    public function includeChild(ChildDevelopmentEvaluation $evaluation)
    {
        if (!$evaluation->child) {
            return null;
        }

        return $this->item($evaluation->child, new \App\Containers\Monitoring\Child\UI\API\Transformers\ChildTransformer());
    }

    /**
     * Include assessed_by user in the response.
     */
    public function includeAssessedBy(ChildDevelopmentEvaluation $evaluation)
    {
        if (!$evaluation->assessedBy) {
            return null;
        }

        return $this->item($evaluation->assessedBy, new \App\Containers\AppSection\User\UI\API\Transformers\UserTransformer());
    }
}
