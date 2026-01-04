<?php

namespace App\Containers\AppSection\File\Models;

use App\Containers\AppSection\File\Enums\FileStatus;
use App\Containers\AppSection\File\Enums\FileUploadLocale;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class File extends ParentModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'files';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'unique_code',
        'file_hash',
        'name',
        'original_name',
        'description',
        'mime_type',
        'size',
        'url',
        'path',
        'options',
        'locale_upload',
        'status',
        'user_id',
        'filleable_id',
        'filleable_type',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'locale_upload' => FileUploadLocale::class,
        'status' => FileStatus::class,
        'size' => 'integer',
        'filleable_id' => 'integer',
    ];

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get the file size in human readable format.
     */
    protected function humanReadableSize(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $bytes = $this->size;
                $units = ['B', 'KB', 'MB', 'GB', 'TB'];

                for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                    $bytes /= 1024;
                }

                return round($bytes, 2) . ' ' . $units[$i];
            },
        );
    }

    /**
     * Get the file extension from mime type.
     */
    protected function extension(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match ($this->mime_type) {
                    'application/pdf' => 'pdf',
                    'image/jpeg', 'image/jpg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    'text/plain' => 'txt',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'application/vnd.ms-excel' => 'xls',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                    'application/vnd.ms-powerpoint' => 'ppt',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
                    'application/zip' => 'zip',
                    'application/x-rar-compressed' => 'rar',
                    default => explode('/', $this->mime_type)[1] ?? 'unknown',
                };
            },
        );
    }

    /**
     * Check if file is an image.
     */
    protected function isImage(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => str_starts_with($this->mime_type, 'image/'),
        );
    }

    /**
     * Check if file is a document.
     */
    protected function isDocument(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => in_array($this->mime_type, [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain',
            ]),
        );
    }

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the user that uploaded this file.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning filleable model.
     */
    public function filleable(): MorphTo
    {
        return $this->morphTo();
    }

    // ==========================================================================
    // Scopes
    // ==========================================================================

    /**
     * Scope to filter files by status.
     */
    public function scopeStatus($query, FileStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter files by upload locale.
     */
    public function scopeLocaleUpload($query, FileUploadLocale $locale)
    {
        return $query->where('locale_upload', $locale);
    }

    /**
     * Scope to filter image files.
     */
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    /**
     * Scope to filter document files.
     */
    public function scopeDocuments($query)
    {
        return $query->whereIn('mime_type', [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ]);
    }

    // ==========================================================================
    // Helpers
    // ==========================================================================

    /**
     * Check if file is accessible via public URL.
     */
    public function isPubliclyAccessible(): bool
    {
        return $this->locale_upload === FileUploadLocale::LOCAL && str_starts_with($this->url, 'http');
    }

    /**
     * Get file download URL based on upload locale.
     */
    public function getDownloadUrl(): string
    {
        return match ($this->locale_upload) {
            FileUploadLocale::LOCAL => $this->url,
            FileUploadLocale::S3 => $this->url, // S3 URL handling would be implemented here
            FileUploadLocale::SFTP => $this->url, // SFTP URL handling would be implemented here
        };
    }

    /**
     * Mark file as submitted.
     */
    public function markAsSubmitted(): bool
    {
        return $this->update(['status' => FileStatus::SUBMITTED]);
    }

    /**
     * Mark file as archived.
     */
    public function markAsArchived(): bool
    {
        return $this->update(['status' => FileStatus::ARCHIVED]);
    }

    /**
     * Check if file can be deleted (only created status allows deletion).
     */
    public function canBeDeleted(): bool
    {
        return $this->status === FileStatus::CREATED;
    }

    /**
     * Get file type category.
     */
    public function getTypeCategory(): string
    {
        if ($this->is_image) {
            return 'image';
        }

        if ($this->is_document) {
            return 'document';
        }

        return 'other';
    }
}
