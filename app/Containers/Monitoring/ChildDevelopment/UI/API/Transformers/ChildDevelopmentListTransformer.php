<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Transformers;

use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluationScore;
use Vinkla\Hashids\Facades\Hashids;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

/**
 * Transformer simplificado para listado de evaluaciones.
 * Solo incluye información esencial ya guardada, sin procesamiento pesado.
 */
final class ChildDevelopmentListTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(ChildDevelopmentEvaluation $evaluation): array
    {
        $scores = $evaluation->scores->keyBy('area');
        $ageMonths = $evaluation->age_months;

        // Verificar si requiere atención basado en los status guardados (sin procesamiento)
        $requiresAttention = $scores->contains(function (ChildDevelopmentEvaluationScore $score) {
            return $score->status->requiresAttention();
        });

        // Calcular overall_score (promedio de porcentajes de todas las áreas)
        $overallScore = $this->calculateOverallScore($scores, $ageMonths);
        $overallStatus = $this->getOverallStatus($evaluation, $scores);

        return [
            'type' => $evaluation->getResourceKey(),
            'id' => $evaluation->getHashedKey(),
            
            // Datos básicos
            'evaluation_date' => $evaluation->evaluation_date->format('Y-m-d'),
            'evaluation_date_readable' => $evaluation->evaluation_date->format('d/m/Y'),
            'age_months' => $evaluation->age_months,
            'age_readable' => $evaluation->age_readable,
            
            // Scores por área (solo datos guardados: raw_score y status)
            'scores' => [
                'MG' => $this->transformSimpleScore($scores->get('MG'), $ageMonths),
                'MF' => $this->transformSimpleScore($scores->get('MF'), $ageMonths),
                'AL' => $this->transformSimpleScore($scores->get('AL'), $ageMonths),
                'PS' => $this->transformSimpleScore($scores->get('PS'), $ageMonths),
            ],
            
            // Score overall general
            'overall_score' => $overallScore,
            'overall_status' => $overallStatus,
            
            // Indicador simple de atención
            'requires_attention' => $requiresAttention,
            
            // Fecha próxima evaluación
            'next_evaluation_date' => $evaluation->next_evaluation_date?->format('Y-m-d'),
            
            // Metadatos básicos
            'created_at' => $evaluation->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Transforma un score de forma simple, solo con datos guardados.
     * 
     * @param ChildDevelopmentEvaluationScore|null $score
     * @param int|null $ageMonths Para calcular percentage si es necesario
     */
    private function transformSimpleScore(?ChildDevelopmentEvaluationScore $score, ?int $ageMonths = null): ?array
    {
        if (!$score) {
            return null;
        }

        $result = [
            'raw_score' => $score->raw_score,
            'status' => $score->status->value,
            'status_label' => $score->status->label(),
            'status_color' => $score->status->color(),
        ];

        // Opcionalmente agregar percentage si se proporciona ageMonths
        if ($ageMonths !== null) {
            $maxScore = $score->getMaxPossibleScore($ageMonths);
            if ($maxScore > 0) {
                $result['percentage'] = round(($score->raw_score / $maxScore) * 100, 2);
            }
        }

        return $result;
    }

    /**
     * Calcular overall_score (promedio de porcentajes de todas las áreas).
     */
    private function calculateOverallScore($scores, ?int $ageMonths): ?float
    {
        if ($ageMonths === null) {
            return null;
        }

        $totalPercentage = 0;
        $count = 0;

        foreach ($scores as $score) {
            $maxScore = $score->getMaxPossibleScore($ageMonths);
            if ($maxScore > 0) {
                $percentage = round(($score->raw_score / $maxScore) * 100, 2);
                $totalPercentage += $percentage;
                $count++;
            }
        }

        return $count > 0 ? round($totalPercentage / $count, 2) : null;
    }

    /**
     * Obtener overall_status basado en todos los scores.
     */
    private function getOverallStatus(ChildDevelopmentEvaluation $evaluation, $scores): string
    {
        if ($evaluation->requiresAttention()) {
            return 'alert';
        }

        $allNormal = $scores->every(fn ($score) => $score->isNormal());

        return $allNormal ? 'normal' : 'review';
    }
}

