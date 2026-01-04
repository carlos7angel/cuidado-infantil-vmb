<?php

namespace App\Containers\Monitoring\Child\Enums;

enum DeficitLevel: string
{
    case HIGH = 'alto';
    case MEDIUM = 'medio';
    case LOW = 'bajo';
}
