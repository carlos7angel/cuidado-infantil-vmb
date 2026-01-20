<?php

namespace App\Containers\Monitoring\Child\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Child\PreviewChildrenImportRequest;
use App\Containers\Monitoring\Child\Imports\ChildrenImport;
use App\Ship\Parents\Actions\Action as ParentAction;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PreviewChildrenImportAction extends ParentAction
{
    public function run(PreviewChildrenImportRequest $request): array
    {
        $file = $request->file('file');
        
        // Load data using the Import class
        $collections = Excel::toCollection(new ChildrenImport, $file);
        
        // Assuming the first sheet
        $rows = $collections->first();
        
        $previewData = [];

        foreach ($rows as $index => $row) {
            // Skip header row
            if ($index === 0) {
                continue;
            }

            // Skip empty rows (check if first few columns are empty)
            if (empty($row[1]) && empty($row[3])) {
                continue;
            }

            $isValid = true;
            $errors = [];

            // Basic Validation
            // 1: Apellido Paterno, 3: Nombres, 5: Fecha Nacimiento
            if (empty($row[3])) {
                $isValid = false;
                $errors[] = 'Nombres es requerido';
            }
            if (empty($row[1])) {
                $isValid = false;
                $errors[] = 'Apellido Paterno es requerido';
            }
            if (empty($row[5])) {
                $isValid = false;
                $errors[] = 'Fecha de Nacimiento es requerida';
            }

            // Date parsing (Index 5)
            $birthDate = null;
            if (!empty($row[5])) {
                try {
                    if (is_numeric($row[5])) {
                        $birthDate = Date::excelToDateTimeObject($row[5])->format('Y-m-d');
                    } else {
                        // Try standard format d/m/Y or Y-m-d
                        $birthDate = Carbon::parse(str_replace('/', '-', $row[5]))->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $isValid = false;
                    $errors[] = 'Formato de fecha inválido';
                }
            }

            // Map Gender (Index 4)
            $gender = strtolower($row[4] ?? '');
            if (str_contains($gender, 'masculino') || $gender == 'm' || $gender == 'hombre') {
                $genderCode = 'masculino';
            } elseif (str_contains($gender, 'femenino') || $gender == 'f' || $gender == 'mujer') {
                $genderCode = 'femenino';
            } else {
                $genderCode = 'no_especificado';
            }

            // Map Insurance (Index 10)
            $hasInsurance = false;
            $insuranceVal = strtolower($row[10] ?? '');
            if ($insuranceVal == 'si' || $insuranceVal == 'sí' || $insuranceVal == 'yes' || $insuranceVal == '1') {
                $hasInsurance = true;
            }

            $previewData[] = [
                'row_index' => $index + 1, // Excel row number (1-based)
                'nombres' => $row[3] ?? null,
                'apellido_paterno' => $row[1] ?? null,
                'apellido_materno' => $row[2] ?? null,
                'genero' => $genderCode,
                'genero_label' => $row[4] ?? null,
                'fecha_nacimiento' => $birthDate,
                'direccion' => $row[6] ?? null,
                'departamento' => $row[7] ?? null,
                'ciudad' => $row[8] ?? null,
                'municipio' => $row[9] ?? null,
                'tiene_seguro' => $hasInsurance,
                'detalle_seguro' => $row[11] ?? null,
                'peso' => $row[12] ?? null,
                'talla' => $row[13] ?? null,
                'is_valid' => $isValid,
                'errors' => $errors,
            ];

        
        }

        return $previewData;
    }
}
