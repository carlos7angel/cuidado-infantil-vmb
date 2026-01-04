<?php

namespace App\Containers\AppSection\File\Enums;

enum FileStatus: string
{
    case CREATED = 'created';
    case SUBMITTED = 'submitted';
    case ARCHIVED = 'archived';
}