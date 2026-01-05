<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Actions;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Containers\Monitoring\NutritionalAssessment\Tasks\CreateNutritionalAssessmentTask;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\CreateNutritionalAssessmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class CreateNutritionalAssessmentAction extends ParentAction
{
    public function __construct(
        private readonly CreateNutritionalAssessmentTask $createNutritionalAssessmentTask,
        private readonly ChildRepository $childRepository,
    ) {
    }

    public function run(CreateNutritionalAssessmentRequest $request): NutritionalAssessment
    {
        // Obtener el child_id desde el parámetro de ruta (ya decodificado por Apiato)
        $childId = $request->route('child_id');
        
        if (!$childId) {
            throw new \InvalidArgumentException('child_id is required in the route');
        }
        
        // Obtener el niño (el ID puede venir hasheado, el repository lo maneja)
        $child = $this->childRepository->findOrFail($childId);

        // Validar que el niño tenga género definido (necesario para cálculos WHO)
        if (!$child->gender) {
            throw ValidationException::withMessages([
                'child_id' => ['El niño debe tener un género definido para realizar la valoración nutricional.'],
            ]);
        }

        // Calcular la edad en meses al momento de la valoración
        $assessmentDate = $request->input('assessment_date') 
            ? \Carbon\Carbon::parse($request->input('assessment_date'))
            : now();
        
        $ageInMonths = $child->getAgeInMonthsAt($assessmentDate);

        // Preparar los datos para la valoración
        $data = $request->sanitize([
            'child_id' => $child->id,
            'assessed_by' => Auth::id(),
            'assessment_date' => $assessmentDate->format('Y-m-d'),
            'age_in_months' => $ageInMonths,
            'weight' => $request->input('weight'),
            'height' => $request->input('height'),
            'head_circumference' => $request->input('head_circumference'),
            'arm_circumference' => $request->input('arm_circumference'),
            'observations' => $request->input('observations'),
            'recommendations' => $request->input('recommendations'),
        ]);

        return $this->createNutritionalAssessmentTask->run($data);
    }
}
