<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

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
        $stats = $this->getDashboardStatistics();

        // Generar saludo personalizado
        $greeting = $this->getGreeting($user->name ?? 'Usuario');

        return view('frontend@administrator::dashboard.index', compact('page_title', 'stats', 'greeting', 'user'));
    }

    /**
     * Obtener estadísticas del dashboard de forma optimizada.
     */
    private function getDashboardStatistics(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        return [
            // Estadísticas principales
            'total_children' => Child::count(),
            'total_centers' => ChildcareCenter::count(),
            'active_enrollments' => ChildEnrollment::where('status', EnrollmentStatus::ACTIVE->value)->count(),
            
            // Asistencias
            'today_attendances' => Attendance::whereDate('date', $today)->count(),
            'today_present' => Attendance::whereDate('date', $today)
                ->where('status', 'presente')
                ->count(),
            'attendance_rate' => $this->calculateAttendanceRate(),
            
            // Evaluaciones
            'total_nutritional_assessments' => NutritionalAssessment::count(),
            'recent_nutritional_assessments' => NutritionalAssessment::where('assessment_date', '>=', $thisMonth)->count(),
            'total_development_evaluations' => ChildDevelopmentEvaluation::count(),
            'recent_development_evaluations' => ChildDevelopmentEvaluation::where('evaluation_date', '>=', $thisMonth)->count(),
            
            // Reportes de incidentes
            'total_incidents' => IncidentReport::count(),
            'active_incidents' => IncidentReport::whereIn('status', [
                IncidentStatus::REPORTED->value,
                IncidentStatus::UNDER_REVIEW->value,
                IncidentStatus::ESCALATED->value,
            ])->count(),
            'recent_incidents' => IncidentReport::where('reported_at', '>=', $thisMonth)->count(),
            
            // Vacunaciones
            'total_vaccinations' => ChildVaccination::count(),
            'recent_vaccinations' => ChildVaccination::where('date_applied', '>=', $thisMonth)->count(),
            
            // Estadísticas de crecimiento (último mes vs mes anterior)
            'children_growth' => $this->calculateGrowth(
                Child::where('created_at', '<', $lastMonth)->count(),
                Child::where('created_at', '<', $thisMonth)->count()
            ),
            'enrollments_growth' => $this->calculateGrowth(
                ChildEnrollment::where('created_at', '<', $lastMonth)->count(),
                ChildEnrollment::where('created_at', '<', $thisMonth)->count()
            ),
        ];
    }

    /**
     * Calcular tasa de asistencia del mes actual.
     */
    private function calculateAttendanceRate(): float
    {
        $thisMonth = now()->startOfMonth();
        $totalDays = now()->day;
        $expectedAttendances = ChildEnrollment::where('status', EnrollmentStatus::ACTIVE->value)
            ->count() * $totalDays;
        
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

