<?php

namespace App\Containers\Monitoring\ChildDevelopment\Actions;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Containers\Monitoring\ChildDevelopment\Tasks\CreateChildDevelopmentTask;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\CreateChildDevelopmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class CreateChildDevelopmentAction extends ParentAction
{
    public function __construct(
        private readonly CreateChildDevelopmentTask $createChildDevelopmentTask,
        private readonly ChildRepository $childRepository,
    ) {
    }

    public function run(CreateChildDevelopmentRequest $request): ChildDevelopmentEvaluation
    {
        // Obtener el child_id desde el parámetro de ruta o del body (ya decodificado por Apiato)
        $childId = $request->route('child_id') ?? $request->input('child_id');
        
        if (!$childId) {
            throw new \InvalidArgumentException('child_id is required in the route or request body');
        }
        
        // Obtener el niño
        $child = $this->childRepository->findOrFail($childId);

        // Validar que el niño tenga fecha de nacimiento definida
        if (!$child->birth_date) {
            throw ValidationException::withMessages([
                'child_id' => ['El niño debe tener una fecha de nacimiento definida para realizar la evaluación de desarrollo.'],
            ]);
        }

        // Calcular la edad en meses al momento de la evaluación
        $evaluationDate = $request->input('evaluation_date') 
            ? \Carbon\Carbon::parse($request->input('evaluation_date'))
            : now();
        
        // Si se proporciona age_months, usarlo; si no, calcularlo usando el método del modelo
        $ageMonths = $request->input('age_months');
        if ($ageMonths === null) {
            $ageMonths = $child->getAgeInMonthsAt($evaluationDate);
            
            if ($ageMonths === null) {
                throw ValidationException::withMessages([
                    'child_id' => ['El niño debe tener una fecha de nacimiento definida para calcular la edad.'],
                ]);
            }
        }

        // Obtener items logrados (solo IDs, todos se consideran achieved=true)
        // El formato ahora es: items: [1, 2, 3] en lugar de items: [{development_item_id: 1, achieved: true}, ...]
        $achievedItemIds = $request->input('items', []);
        
        // Validar que los items proporcionados sean aplicables para la edad
        $this->validateItemsForAge($achievedItemIds, $ageMonths);

        // Preparar los datos para la evaluación
        $data = $request->sanitize([
            'child_id' => $child->id,
            'assessed_by' => Auth::id(),
            'evaluation_date' => $evaluationDate->format('Y-m-d'),
            'age_months' => $ageMonths,
            'weight' => $request->input('weight'),
            'height' => $request->input('height'),
            'notes' => $request->input('notes'),
            'next_evaluation_date' => $request->input('next_evaluation_date'),
            'items' => $achievedItemIds, // Solo IDs de items logrados
        ]);

        return $this->createChildDevelopmentTask->run($data);
    }

    /**
     * Validar que los items proporcionados sean acumulados hasta la edad del niño.
     * Un ítem es válido si su age_max_months es menor o igual a la edad del niño.
     * 
     * @param array $itemIds Array de IDs de items logrados
     */
    private function validateItemsForAge(array $itemIds, int $ageMonths): void
    {
        if (empty($itemIds)) {
            return;
        }

        $developmentItems = \App\Containers\Monitoring\ChildDevelopment\Models\DevelopmentItem::whereIn('id', $itemIds)->get();

        $invalidItems = [];
        foreach ($developmentItems as $item) {
            // Un ítem es válido si su edad máxima es menor o igual a la edad del niño
            // Esto significa que el ítem debería haberse alcanzado hasta esa edad
            if ($item->age_max_months <= $ageMonths) {
                $invalidItems[] = $item->id;
            }
        }

        if (!empty($invalidItems)) {
            throw ValidationException::withMessages([
                'items' => ['Uno o más ítems no son aplicables para la edad del niño (' . $ageMonths . ' meses). Los ítems deben ser acumulados hasta esa edad.'],
            ]);
        }
    }
}
