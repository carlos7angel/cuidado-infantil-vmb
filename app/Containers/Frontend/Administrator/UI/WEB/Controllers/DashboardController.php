<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentStatus;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

final class DashboardController extends WebController
{
    public function showIndexPage(): View
    {
        $page_title = 'Panel de Control';
        $user = Auth::user();

        // Obtener estadísticas optimizadas usando consultas agregadas
        $stats = $this->getDashboardStatistics($user);

        // Generar saludo personalizado
        $greeting = $this->getGreeting($user->name ?? 'Usuario');

        return view('frontend@administrator::dashboard.index', compact('page_title', 'stats', 'greeting', 'user'));
    }

    /**
     * Obtener estadísticas del dashboard de forma optimizada.
     */
    private function getDashboardStatistics($user): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        $isChildcareAdmin = $user->hasRole(Role::CHILDCARE_ADMIN);
        $centerId = $user->childcare_center_id;

        // Helper queries based on role
        $childQuery = Child::query();
        $centerQuery = ChildcareCenter::query();
        $enrollmentQuery = ChildEnrollment::query();
        $attendanceQuery = Attendance::query();
        $nutritionQuery = NutritionalAssessment::query();
        $developmentQuery = ChildDevelopmentEvaluation::query();
        $incidentQuery = IncidentReport::query();
        $vaccinationQuery = ChildVaccination::query();
        
        if ($isChildcareAdmin) {
            $childQuery->whereHas('activeEnrollment', fn($q) => $q->where('childcare_center_id', $centerId));
            $centerQuery->where('id', $centerId);
            $enrollmentQuery->where('childcare_center_id', $centerId);
            $attendanceQuery->whereHas('child.activeEnrollment', fn($q) => $q->where('childcare_center_id', $centerId));
            $nutritionQuery->whereHas('child.activeEnrollment', fn($q) => $q->where('childcare_center_id', $centerId));
            $developmentQuery->whereHas('child.activeEnrollment', fn($q) => $q->where('childcare_center_id', $centerId));
            $incidentQuery->where('childcare_center_id', $centerId);
            $vaccinationQuery->whereHas('child.activeEnrollment', fn($q) => $q->where('childcare_center_id', $centerId));
        }

        return [
            // Estadísticas principales
            'total_children' => $childQuery->count(),
            'total_centers' => $centerQuery->count(),
            'active_enrollments' => $enrollmentQuery->where('status', EnrollmentStatus::ACTIVE->value)->count(),
            
            // Asistencias
            'today_attendances' => (clone $attendanceQuery)->whereDate('date', $today)->count(),
            'today_present' => (clone $attendanceQuery)->whereDate('date', $today)
                ->where('status', 'presente')
                ->count(),
            'attendance_rate' => $this->calculateAttendanceRate($user),
            
            // Evaluaciones
            'total_nutritional_assessments' => $nutritionQuery->count(),
            'recent_nutritional_assessments' => (clone $nutritionQuery)->where('assessment_date', '>=', $thisMonth)->count(),
            'total_development_evaluations' => $developmentQuery->count(),
            'recent_development_evaluations' => (clone $developmentQuery)->where('evaluation_date', '>=', $thisMonth)->count(),
            
            // Reportes de incidentes
            'total_incidents' => $incidentQuery->count(),
            'active_incidents' => (clone $incidentQuery)->whereIn('status', [
                IncidentStatus::REPORTED->value,
                IncidentStatus::UNDER_REVIEW->value,
                IncidentStatus::ESCALATED->value,
            ])->count(),
            'recent_incidents' => (clone $incidentQuery)->where('reported_at', '>=', $thisMonth)->count(),
            
            // Vacunaciones
            'total_vaccinations' => $vaccinationQuery->count(),
            'recent_vaccinations' => (clone $vaccinationQuery)->where('date_applied', '>=', $thisMonth)->count(),
            
            // Estadísticas de crecimiento (último mes vs mes anterior)
            'children_growth' => $this->calculateGrowth(
                (clone $childQuery)->where('created_at', '<', $lastMonth)->count(),
                (clone $childQuery)->where('created_at', '<', $thisMonth)->count()
            ),
            'enrollments_growth' => $this->calculateGrowth(
                (clone $enrollmentQuery)->where('created_at', '<', $lastMonth)->count(),
                (clone $enrollmentQuery)->where('created_at', '<', $thisMonth)->count()
            ),
        ];
    }

    /**
     * Calcular tasa de asistencia del mes actual.
     */
    private function calculateAttendanceRate($user): float
    {
        $thisMonth = now()->startOfMonth();
        $totalDays = now()->day;
        
        $query = ChildEnrollment::where('status', EnrollmentStatus::ACTIVE->value);
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $query->where('childcare_center_id', $user->childcare_center_id);
        }
        
        $expectedAttendances = $query->count() * $totalDays;
        
        if ($expectedAttendances === 0) {
            return 0;
        }

        $actualAttendances = Attendance::where('date', '>=', $thisMonth)
            ->where('status', 'presente')
            ->count();

        return round(($actualAttendances / $expectedAttendances) * 100, 1);
    }

    /**
     * Calcular crecimiento porcentual.
     */
    private function calculateGrowth(int $previous, int $current): array
    {
        if ($previous === 0) {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'trend' => $current > 0 ? 'up' : 'neutral',
            ];
        }

        $percentage = round((($current - $previous) / $previous) * 100, 1);
        
        return [
            'percentage' => abs($percentage),
            'trend' => $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'neutral'),
        ];
    }

    /**
     * Generar saludo personalizado según la hora del día.
     */
    private function getGreeting(string $name): string
    {
        $hour = (int) now()->format('H');
        
        if ($hour >= 5 && $hour < 12) {
            return "¡Buenos días, {$name}!";
        } elseif ($hour >= 12 && $hour < 18) {
            return "¡Buenas tardes, {$name}!";
        } else {
            return "¡Buenas noches, {$name}!";
        }
    }
}

