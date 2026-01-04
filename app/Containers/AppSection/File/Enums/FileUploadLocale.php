<?php

namespace App\Containers\AppSection\File\Enums;

enum FileUploadLocale: string
{
    case LOCAL = 'local';
    case S3 = 's3';
    case SFTP = 'sftp';
}