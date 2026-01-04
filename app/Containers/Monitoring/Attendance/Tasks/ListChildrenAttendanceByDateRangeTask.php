<?php

namespace App\Containers\Monitoring\Attendance\Tasks;

use App\Containers\Monitoring\Attendance\Enums\AttendanceStatus;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

final class ListChildrenAttendanceByDateRangeTask extends ParentTask
{
    public function run(ChildcareCenter $childcareCenter, string $startDate, string $endDate): array
    {
        // Generar array de fechas del rango
        $dates = [];
        $currentDate = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        while ($currentDate <= $endDateCarbon) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Generar UNION ALL para las fechas (optimizado para MySQL)
        $dateUnion = collect($dates)->map(function ($date) {
            return "SELECT '{$date}' AS date";
        })->implode(' UNION ALL ');

        // Query optimizada con LEFT JOINs
        $results = DB::table('child_enrollments')
            ->select([
                'children.id as child_id',
                'children.first_name',
                'children.paternal_last_name',
                'children.maternal_last_name',
                DB::raw("CONCAT(children.first_name, ' ', children.paternal_last_name, ' ', children.maternal_last_name) as full_name"),
                'children.birth_date',
                'children.gender',
                'rooms.id as room_id',
                'rooms.name as room_name',
                'child_enrollments.enrollment_date',
                'child_enrollments.withdrawal_date',
                'dates_table.date as attendance_date',
                'attendances.status as attendance_status',
            ])
            ->join('children', 'child_enrollments.child_id', '=', 'children.id')
            ->leftJoin('rooms', 'child_enrollments.room_id', '=', 'rooms.id')
            ->crossJoin(DB::raw("({$dateUnion}) as dates_table"))
            ->leftJoin('attendances', function ($join) use ($childcareCenter) {
                $join->on('attendances.child_id', '=', 'children.id')
                    ->on('attendances.childcare_center_id', '=', DB::raw($childcareCenter->id))
                    ->on('attendances.date', '=', 'dates_table.date');
            })
            ->where('child_enrollments.childcare_center_id', $childcareCenter->id)
            // Filtrar solo por inscripciones activas (sin validar fechas de inscripción/retiro por ahora)
            ->where('child_enrollments.status', EnrollmentStatus::ACTIVE->value)
            
            // ->whereRaw('child_enrollments.enrollment_date <= dates_table.date')
            // ->where(function ($query) {
            //     $query->whereNull('child_enrollments.withdrawal_date')
            //         ->orWhereRaw('child_enrollments.withdrawal_date >= dates_table.date');
            // })
            
            ->orderBy('children.id')
            ->orderBy('dates_table.date')
            ->get();

        // Procesar resultados y agrupar por niño (optimizado)
        $childrenData = [];
        $statusMap = $this->getStatusMap();

        foreach ($results as $row) {
            $childId = $row->child_id;

            // Inicializar niño si es primera vez que lo vemos
            if (!isset($childrenData[$childId])) {
                $childrenData[$childId] = [
                    'child_id' => Hashids::encode($childId),
                    'full_name' => $row->full_name,
                    'first_name' => $row->first_name,
                    'paternal_last_name' => $row->paternal_last_name,
                    'maternal_last_name' => $row->maternal_last_name,
                    'birth_date' => $row->birth_date,
                    // 'age' => $row->age,
                    'gender' => $row->gender,
                    'room_id' => Hashids::encode($row->room_id),
                    'room_name' => $row->room_name ?? 'Sin sala asignada',
                    'attendance' => [],
                ];
            }

            // Mapear status de asistencia (optimizado)
            $attendanceStatus = null;
            if ($row->attendance_status) {
                // $attendanceStatus = $statusMap[$row->attendance_status] ?? $row->attendance_status;
                $attendanceStatus = $row->attendance_status;
            } else {
                // No hay registro pero el niño estaba inscrito
                $attendanceStatus = 'unspecified';
            }

            $childrenData[$childId]['attendance'][$row->attendance_date] = $attendanceStatus;
        }

        // Convertir a array indexado numéricamente
        $children = array_values($childrenData);

        return [
            'center_id' => Hashids::encode($childcareCenter->id),
            'range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'children_count' => count($children),
            'dates' => $dates,
            'children' => $children,
        ];
    }

    /**
     * Get status mapping for attendance status enum to frontend format.
     */
    private function getStatusMap(): array
    {
        return [
            AttendanceStatus::PRESENT->value => 'present',
            AttendanceStatus::ABSENT->value => 'absent',
            AttendanceStatus::LATE->value => 'late',
            AttendanceStatus::JUSTIFIED->value => 'justified',
            AttendanceStatus::HOLIDAY->value => 'holiday',
            AttendanceStatus::SICK->value => 'sick',
        ];
    }
}

