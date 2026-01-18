<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Http\UploadedFile;

final class ProcessChildAvatarTask extends ParentTask
{
    public function run(UploadedFile $file, ?string $existingAvatarPath = null): string
    {
        $realPath = $file->getRealPath();
        if (!$realPath) {
            throw new \RuntimeException('Invalid image file');
        }

        $mime = $file->getMimeType() ?: mime_content_type($realPath);

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($realPath);
                $extension = 'jpg';
                break;
            case 'image/png':
                $source = imagecreatefrompng($realPath);
                $extension = 'png';
                break;
            default:
                throw new \RuntimeException('Unsupported image type');
        }

        if (!$source) {
            throw new \RuntimeException('Unable to create image');
        }

        $width = imagesx($source);
        $height = imagesy($source);

        if ($width <= 0 || $height <= 0) {
            imagedestroy($source);
            throw new \RuntimeException('Invalid image dimensions');
        }

        $size = min($width, $height);
        $x = (int) floor(($width - $size) / 2);
        $y = (int) floor(($height - $size) / 2);

        $square = imagecreatetruecolor($size, $size);

        if ($extension === 'png') {
            imagealphablending($square, false);
            imagesavealpha($square, true);
        }

        imagecopyresampled($square, $source, 0, 0, $x, $y, $size, $size, $size, $size);
        imagedestroy($source);

        $targetSize = $size > 500 ? 500 : $size;

        if ($targetSize !== $size) {
            $resized = imagecreatetruecolor($targetSize, $targetSize);
            if ($extension === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            imagecopyresampled($resized, $square, 0, 0, 0, 0, $targetSize, $targetSize, $size, $size);
            imagedestroy($square);
        } else {
            $resized = $square;
        }

        $uploadPath = public_path('uploads/children');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBaseName = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $baseName) ?: 'avatar';
        $fileName = time() . '_' . $safeBaseName . '.' . $extension;
        $destinationPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;

        if ($extension === 'jpg') {
            imagejpeg($resized, $destinationPath, 90);
        } elseif ($extension === 'png') {
            imagepng($resized, $destinationPath, 6);
        }

        imagedestroy($resized);

        if ($existingAvatarPath) {
            $oldPath = public_path($existingAvatarPath);
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return 'uploads/children/' . $fileName;
    }
}

