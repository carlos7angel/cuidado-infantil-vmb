<?php

namespace App\Containers\Monitoring\ChildDevelopment\Tasks;

use App\Containers\Monitoring\ChildDevelopment\Data\Repositories\ChildDevelopmentEvaluationRepository;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluationItem;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;

final class CreateChildDevelopmentTask extends ParentTask
{
    public function __construct(
        private readonly ChildDevelopmentEvaluationRepository $repository,
    ) {
    }

    public function run(array $data): ChildDevelopmentEvaluation
    {
        // Extraer items del array de datos
        $items = $data['items'] ?? [];
        unset($data['items']);

        return DB::transaction(function () use ($data, $items) {
            // Crear la evaluación principal
            $evaluation = $this->repository->create($data);

            // Crear los resultados de items logrados (solo se guardan los achieved=true)
            // Los items no logrados se reconstruirán al mostrar la evaluación
            foreach ($items as $itemId) {
                ChildDevelopmentEvaluationItem::create([
                    'evaluation_id' => $evaluation->id,
                    'development_item_id' => $itemId,
                    'achieved' => true, // Todos los items enviados son logrados
                ]);
            }

            // Cargar la relación child necesaria para cálculos
            $evaluation->load('child');

            // Calcular y guardar los puntajes automáticamente
            $evaluation->calculateAndSaveScores();

            // Cargar relaciones para la respuesta
            // No cargar scores.evaluation (relación circular), el transformer pasará age_months directamente
            $evaluation->load([
                'scores', 
                'evaluationItems.developmentItem', 
                'assessedBy'
            ]);

            return $evaluation;
        });
    }
}
