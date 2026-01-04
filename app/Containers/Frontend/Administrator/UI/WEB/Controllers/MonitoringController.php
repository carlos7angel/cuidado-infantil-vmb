<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring\DetailListDevelopmentEvaluationsByChildRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring\DetailListNutritionAssessmentsByChildRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring\GetDevelopmentEvaluationIndicatorsRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring\ListDevelopmentEvaluationsByChildRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring\ListNutritionAssessmentsByChildRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring\ListVaccinationTrackingByChildRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Monitoring\SummarizeByChildRequest;
use App\Containers\Monitoring\Child\Tasks\FindChildByIdTask;
use App\Containers\Monitoring\ChildDevelopment\Tasks\FindChildDevelopmentByIdTask;
use App\Containers\Monitoring\ChildDevelopment\Tasks\ListChildDevelopmentEvaluationsTask;
use App\Containers\Monitoring\ChildVaccination\Tasks\GetChildVaccinationTrackingTask;
use App\Containers\Monitoring\Attendance\Enums\AttendanceStatus as AttendanceStatusEnum;
use App\Containers\Monitoring\NutritionalAssessment\Actions\ListChildNutritionalAssessmentsAction;
use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Containers\Monitoring\NutritionalAssessment\Tasks\FindNutritionalAssessmentByIdTask;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;

final class MonitoringController extends WebController
{
    public function listNutritionAssessmentsByChild(ListNutritionAssessmentsByChildRequest $request): View
    {
        $page_title = 'Evaluaciones Nutricionales';

        // request()->merge() es necesario en lugar de $request->merge()
        request()->merge([
            'page' => $request->get('page', 1),
            'limit' => $request->get('limit', 100),
        ]);

        $childId = $request->route('child_id');
        if (!$childId) {
            throw new \InvalidArgumentException('child_id is required in the route');
        }

        $child = app(FindChildByIdTask::class)->run($childId);
        
        $items = app(ListChildNutritionalAssessmentsAction::class)->run($request);

        return view('frontend@administrator::monitoring.list-nutrition-assessments', compact('page_title', 'items', 'child'));
    }

    public function detailListNutritionAssessmentsByChild(DetailListNutritionAssessmentsByChildRequest $request)
    {
        try {
            $nutritionalAssessmentId = $request->route('nutritional_assessment_id');
            if (!$nutritionalAssessmentId) {
                throw new \InvalidArgumentException('nutritional_assessment_id is required in the route');
            }
            $item = app(FindNutritionalAssessmentByIdTask::class)->run($nutritionalAssessmentId);
            $render = view('frontend@administrator::monitoring.partials.nutrition-assessment-details', compact('item'))->render();
            return response()->json(['success' => true, 'render' => $render]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'render' => null, 'message' => $e->getMessage()]);
        }
    }

    public function listDevelopmentEvaluationsByChild(ListDevelopmentEvaluationsByChildRequest $request): View
    {
        $page_title = 'Evaluaciones de Desarrollo Infantil';

        request()->merge([
            'page' => $request->get('page', 1),
            'limit' => $request->get('limit', 100),
        ]);

        $childId = $request->route('child_id');
        if (!$childId) {
            throw new \InvalidArgumentException('child_id is required in the route');
        }

        $child = app(FindChildByIdTask::class)->run($childId);
        $child->load(['activeEnrollment.childcareCenter', 'activeEnrollment.room']);
        
        $items = app(ListChildDevelopmentEvaluationsTask::class)->run($childId);

        return view('frontend@administrator::monitoring.list-development-evaluations', compact('page_title', 'items', 'child'));
    }

    public function detailListDevelopmentEvaluationsByChild(DetailListDevelopmentEvaluationsByChildRequest $request)
    {
        try {
            $developmentEvaluationId = $request->route('development_evaluation_id');
            if (!$developmentEvaluationId) {
                throw new \InvalidArgumentException('development_evaluation_id is required in the route');
            }
            $item = app(FindChildDevelopmentByIdTask::class)->run($developmentEvaluationId);
            $render = view('frontend@administrator::monitoring.partials.development-evaluation-details', compact('item'))->render();
            return response()->json(['success' => true, 'render' => $render]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'render' => null, 'message' => $e->getMessage()]);
        }
    }

    public function getDevelopmentEvaluationIndicators(GetDevelopmentEvaluationIndicatorsRequest $request)
    {
        try {
            $developmentEvaluationId = $request->route('development_evaluation_id');
            if (!$developmentEvaluationId) {
                throw new \InvalidArgumentException('development_evaluation_id is required in the route');
            }
            $item = app(FindChildDevelopmentByIdTask::class)->run($developmentEvaluationId);
            
            // Obtener todos los items hasta 96 meses
            $allItems = \App\Containers\Monitoring\ChildDevelopment\Models\DevelopmentItem::where('age_max_months', '<=', 96)
                ->orderBy('area')
                ->orderBy('item_number')
                ->get();
            
            // Obtener los items logrados en esta evaluación
            $evaluationItems = $item->evaluationItems()
                ->where('achieved', true)
                ->with('developmentItem')
                ->get()
                ->keyBy('development_item_id');
            
            // Mapear todos los items: si está en la evaluación Y es acumulado hasta la edad, achieved=true; si no está acumulado, es "no evaluable"
            $itemsHistory = $allItems->map(function ($developmentItem) use ($evaluationItems, $item) {
                $evaluationItem = $evaluationItems->get($developmentItem->id);
                $isAccumulatedUpToAge = $developmentItem->age_max_months <= $item->age_months;
                
                return (object) [
                    'development_item' => $developmentItem,
                    'achieved' => $isAccumulatedUpToAge && $evaluationItem !== null,
                    'is_evaluable' => $isAccumulatedUpToAge,
                    'evaluation_item_id' => $evaluationItem?->id,
                ];
            });
            
            $render = view('frontend@administrator::monitoring.partials.development-items-overview', compact('item', 'itemsHistory'))->render();
            return response()->json(['success' => true, 'render' => $render]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'render' => null, 'message' => $e->getMessage()]);
        }
    }

    public function listVaccinationTrackingByChild(ListVaccinationTrackingByChildRequest $request): View
    {
        $page_title = 'Seguimiento de Vacunación';

        $childId = $request->route('child_id');
        if (!$childId) {
            throw new \InvalidArgumentException('child_id is required in the route');
        }

        $child = app(FindChildByIdTask::class)->run($childId);
        $child->load(['activeEnrollment.childcareCenter', 'activeEnrollment.room']);
        
        // Obtener datos de vacunación
        $trackingData = app(GetChildVaccinationTrackingTask::class)->run($childId);
        
        $vaccinesData = $trackingData['vaccines'];
        $appliedVaccinations = $trackingData['applied_vaccinations'];
        $ageInMonths = $child->age_in_months ?? 0;
        
        // Procesar vacunas con su información
        $vaccines = $vaccinesData->map(function ($vaccine) use ($appliedVaccinations, $ageInMonths) {
            $doses = $vaccine->doses->map(function ($dose) use ($appliedVaccinations, $ageInMonths) {
                $isApplied = $appliedVaccinations->has($dose->id);
                $childVaccination = $isApplied ? $appliedVaccinations->get($dose->id) : null;
                $ageStatus = $dose->getStatusForAge($ageInMonths);
                
                if ($isApplied) {
                    $status = 'applied';
                    $statusLabel = 'Aplicada';
                    $statusColor = 'success';
                } else {
                    switch ($ageStatus) {
                        case 'overdue':
                            $status = 'overdue';
                            $statusLabel = 'Atrasada';
                            $statusColor = 'warning';
                            break;
                        case 'expired':
                            $status = 'expired';
                            $statusLabel = 'Expirada';
                            $statusColor = 'danger';
                            break;
                        case 'too_young':
                            $status = 'upcoming';
                            $statusLabel = 'Próxima';
                            $statusColor = 'info';
                            break;
                        case 'ideal':
                            $status = 'due';
                            $statusLabel = 'Pendiente';
                            $statusColor = 'primary';
                            break;
                        default:
                            $status = 'pending';
                            $statusLabel = 'Pendiente';
                            $statusColor = 'secondary';
                    }
                }
                
                return [
                    'dose' => $dose,
                    'status' => $status,
                    'status_label' => $statusLabel,
                    'status_color' => $statusColor,
                    'age_status' => $ageStatus,
                    'child_vaccination' => $childVaccination,
                ];
            });
            
            $applied = $doses->where('status', 'applied')->count();
            $total = $doses->count();
            $percentage = $total > 0 ? round(($applied / $total) * 100, 2) : 0;
            
            return [
                'vaccine' => $vaccine,
                'doses' => $doses,
                'progress' => [
                    'applied' => $applied,
                    'total' => $total,
                    'percentage' => $percentage,
                    'is_complete' => $applied === $total,
                ],
            ];
        });

        return view('frontend@administrator::monitoring.list-vaccination-tracking', compact('page_title', 'child', 'vaccines'));
    }

    public function summarizeByChild(SummarizeByChildRequest $request): View
    {
        $page_title = 'Resumen del Infante';
        $childId = $request->route('child_id');
        $child = app(FindChildByIdTask::class)->run($childId);
        
        // Cargar relaciones necesarias (solo las mínimas necesarias)
        $child->load([
            'activeEnrollment.childcareCenter',
            'activeEnrollment.room',
            'latestDevelopmentEvaluation.scores',
            'latestNutritionalAssessment',
        ]);
        
        // ===== ESTADÍSTICAS NUTRICIONALES =====
        // Calcular total y distribución en una sola consulta optimizada
        $nutritionalCounts = NutritionalAssessment::where('child_id', $child->id)
            ->selectRaw('
                COUNT(*) as total,
                COUNT(CASE WHEN status_weight_age IS NOT NULL THEN 1 END) as with_status_count
            ')
            ->first();
        
        $nutritionalStats = [
            'total' => $nutritionalCounts->total ?? 0,
            'latest' => $child->latestNutritionalAssessment,
            'status_distribution' => NutritionalAssessment::where('child_id', $child->id)
                ->selectRaw('status_weight_age, count(*) as count')
                ->whereNotNull('status_weight_age')
                ->groupBy('status_weight_age')
                ->pluck('count', 'status_weight_age')
                ->toArray(),
        ];
        
        // ===== ESTADÍSTICAS DE VACUNAS =====
        $trackingData = app(GetChildVaccinationTrackingTask::class)->run($childId);
        $vaccinesData = $trackingData['vaccines'];
        $appliedVaccinations = $trackingData['applied_vaccinations'];
        
        $vaccineStats = [
            'total_vaccines' => $vaccinesData->count(),
            'total_doses' => $vaccinesData->sum(fn($v) => $v->doses->count()),
            'applied_doses' => $appliedVaccinations->count(),
            'completion_percentage' => 0,
            'upcoming_count' => 0,
            'overdue_count' => 0,
        ];
        
        $ageInMonths = $child->age_in_months ?? 0;
        $totalDoses = 0;
        $appliedDoses = 0;
        
        foreach ($vaccinesData as $vaccine) {
            foreach ($vaccine->doses as $dose) {
                $totalDoses++;
                if ($appliedVaccinations->has($dose->id)) {
                    $appliedDoses++;
                } else {
                    $status = $dose->getStatusForAge($ageInMonths);
                    if ($status === 'overdue') {
                        $vaccineStats['overdue_count']++;
                    } elseif ($status === 'too_young') {
                        $vaccineStats['upcoming_count']++;
                    }
                }
            }
        }
        
        $vaccineStats['total_doses'] = $totalDoses;
        $vaccineStats['applied_doses'] = $appliedDoses;
        $vaccineStats['completion_percentage'] = $totalDoses > 0 
            ? round(($appliedDoses / $totalDoses) * 100, 1) 
            : 0;
        
        // Próximas vacunas (próximas 5)
        $upcomingVaccines = $child->getUpcomingVaccineDoses(6)
            ->take(5)
            ->map(function($dose) {
                return [
                    'vaccine_name' => $dose->vaccine->name,
                    'dose_number' => $dose->dose_number,
                    'recommended_age_months' => $dose->recommended_age_months,
                    'recommended_age' => $dose->recommended_age,
                ];
            });
        
        // ===== ESTADÍSTICAS DE DESARROLLO =====
        // Solo calcular total y promedio sin traer todos los datos
        $developmentTotal = $child->developmentEvaluations()->count();
        
        // Solo cargar la última evaluación con scores (ya está cargada arriba)
        $developmentStats = [
            'total' => $developmentTotal,
            'latest' => $child->latestDevelopmentEvaluation,
            'average_score' => null, // Se puede calcular después si es necesario, pero es costoso
        ];
        
        // Calcular promedio solo si hay evaluaciones (optimizado)
        if ($developmentTotal > 0 && $child->latestDevelopmentEvaluation) {
            // Para el dashboard, solo mostramos el promedio si realmente se necesita
            // Por ahora lo dejamos null para evitar la consulta pesada
        }
        
        // Distribución por áreas (última evaluación)
        $latestDevelopment = $child->latestDevelopmentEvaluation;
        $areaScores = [];
        if ($latestDevelopment) {
            foreach ($latestDevelopment->scores as $score) {
                $percentage = $latestDevelopment->age_months 
                    ? $score->getPercentage($latestDevelopment->age_months) 
                    : null;
                $areaScores[$score->area->value] = [
                    'area' => $score->area->value,
                    'area_label' => $score->area->label(),
                    'percentage' => $percentage !== null ? round($percentage, 1) : null,
                    'status' => $score->status->value,
                ];
            }
        }
        
        // ===== ESTADÍSTICAS DE ASISTENCIA =====
        $attendanceStats = [
            'last_30_days' => [
                'total_days' => 0,
                'present_days' => 0,
                'absent_days' => 0,
                'percentage' => 0,
            ],
            'last_6_months' => [],
        ];
        
        // Calcular asistencia últimos 30 días en una sola consulta optimizada
        $thirtyDaysAgo = now()->subDays(30);
        $last30DaysStats = \App\Containers\Monitoring\Attendance\Models\Attendance::where('child_id', $childId)
            ->where('date', '>=', $thirtyDaysAgo)
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as absent_days
            ', [
                AttendanceStatusEnum::PRESENT->value,
                AttendanceStatusEnum::ABSENT->value,
            ])
            ->first();
        
        $attendanceStats['last_30_days']['total_days'] = $last30DaysStats->total_days ?? 0;
        $attendanceStats['last_30_days']['present_days'] = $last30DaysStats->present_days ?? 0;
        $attendanceStats['last_30_days']['absent_days'] = $last30DaysStats->absent_days ?? 0;
        $attendanceStats['last_30_days']['percentage'] = $attendanceStats['last_30_days']['total_days'] > 0
            ? round(($attendanceStats['last_30_days']['present_days'] / $attendanceStats['last_30_days']['total_days']) * 100, 1)
            : 0;
        
        // Asistencia últimos 3 meses en una sola consulta optimizada con GROUP BY
        $threeMonthsAgo = now()->subMonths(3)->startOfMonth();
        $monthlyAttendance = \App\Containers\Monitoring\Attendance\Models\Attendance::where('child_id', $childId)
            ->where('date', '>=', $threeMonthsAgo)
            ->selectRaw('
                DATE_FORMAT(date, "%Y-%m") as month,
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as present
            ', [AttendanceStatusEnum::PRESENT->value])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        // Llenar los últimos 3 meses (incluyendo meses sin datos)
        for ($i = 2; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthKey = $monthStart->format('Y-m');
            $monthData = $monthlyAttendance->get($monthKey);
            
            $attendanceStats['last_6_months'][] = [
                'month' => $monthKey,
                'month_label' => $monthStart->format('M Y'),
                'present' => $monthData->present ?? 0,
                'total' => $monthData->total ?? 0,
                'percentage' => ($monthData->total ?? 0) > 0 
                    ? round((($monthData->present ?? 0) / $monthData->total) * 100, 1) 
                    : 0,
            ];
        }
        
        return view('frontend@administrator::monitoring.summarize-by-child', compact(
            'page_title',
            'child',
            'nutritionalStats',
            'vaccineStats',
            'upcomingVaccines',
            'developmentStats',
            'areaScores',
            'attendanceStats'
        ));
    }

}

