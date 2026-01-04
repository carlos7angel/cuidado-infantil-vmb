<?php

namespace App\Containers\AppSection\File\Tasks;

use App\Containers\AppSection\File\Models\File;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class CreateFileTask extends ParentTask
{
    public function __construct(
        private readonly StoreFileTask $storeFileTask,
    ) {
    }

    /**
     * Create a file and associate it with a model.
     *
     * @param UploadedFile $file The uploaded file
     * @param int|Model $modelOrId The model instance or ID of the related model
     * @param int|null $user_id The user ID who uploaded the file
     * @param string|null $modelClass The model class name (required if $modelOrId is an ID)
     * @param string|null $basePath Custom base path for file storage (e.g., 'incident-reports' instead of 'children')
     * @return File
     */
    public function run(
        UploadedFile $file,
        int|Model $modelOrId,
        ?int $user_id = null,
        ?string $modelClass = null,
        ?string $basePath = null
    ): File {
        // Si es un modelo, extraer ID y clase
        if ($modelOrId instanceof Model) {
            $modelId = $modelOrId->id;
            $modelClass = $modelClass ?? $modelOrId::class;
            $modelName = class_basename($modelOrId);
        } else {
            // Si es un ID, usar la clase proporcionada o Child por defecto (compatibilidad hacia atrás)
            $modelId = $modelOrId;
            $modelClass = $modelClass ?? Child::class;
            $modelName = class_basename($modelClass);
        }

        // Determinar la ruta base según el tipo de modelo o la proporcionada
        if ($basePath === null) {
            // Generar ruta base basada en el nombre del modelo
            $basePath = match ($modelClass) {
                Child::class => 'children',
                IncidentReport::class => 'incident-reports',
                default => Str::kebab($modelName),
            };
        }

        // Construir la ruta de almacenamiento
        $path = '/uploads/' . $basePath . '/' . $modelId . '/files';
        
        // Generar nombre único para el archivo
        $sanitize_name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $new_name = Str::random(10) . '__' . $sanitize_name . '.' . $file->getClientOriginalExtension();
        
        // Guardar el archivo
        Storage::disk('public')->putFileAs($path, $file, $new_name);
        $url = url('/') . '/storage' . $path . '/' . $new_name;
        
        // Preparar datos para crear el registro en la BD
        $data = [
            'unique_code'       => md5(Date::now()->timestamp . Str::random(32) . $url),
            'name'              => $new_name,
            'original_name'     => $file->getClientOriginalName(),
            'mime_type'         => $file->getMimeType(),
            'size'              => $file->getSize(),
            'url'               => $url,
            'path'              => $path . '/' . $new_name,
            'options'           => [],
            'locale_upload'     => 'local',
            'status'            => 'created',
            'user_id'           => $user_id,
            'filleable_id'      => $modelId,
            'filleable_type'    => $modelClass,
        ];

        $file = $this->storeFileTask->run($data);

        return $file;
    }
}
