<?php

namespace App\Containers\Monitoring\Attendance\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'presente';
    case ABSENT = 'falta';
    case LATE = 'retraso';
    case JUSTIFIED = 'justificado';
    case HOLIDAY = 'feriado';
    case SICK = 'enfermo';
}
