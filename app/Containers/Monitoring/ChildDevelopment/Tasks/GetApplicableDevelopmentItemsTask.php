<?php

namespace App\Containers\Monitoring\ChildDevelopment\Tasks;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea;
use App\Containers\Monitoring\ChildDevelopment\Models\DevelopmentItem;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetApplicableDevelopmentItemsTask extends ParentTask
{
    public function run(int $ageMonths, Child $child): array
    {
        // Obtener TODOS los items acumulados hasta la edad del niño
        // Esto incluye todos los ítems que el niño debería haber alcanzado hasta su edad actual


        $items = DevelopmentItem::where('age_min_months', '<=', $ageMonths)
            ->orderBy('area')
            ->orderBy('item_number')
            ->get();

        // Agrupar items por área
        $itemsByArea = $items->groupBy('area');

        // Formatear respuesta
        $formattedItems = [];
        foreach (DevelopmentArea::cases() as $area) {
            $areaItems = $itemsByArea->get($area->value, collect());
            
            $formattedItems[$area->value] = [
                'area' => $area->value,
                'area_label' => $area->label(),
                'items' => $areaItems->map(function ($item) {
                    return [
                        'id' => $item->getHashedKey(),
                        'item_number' => $item->item_number,
                        'description' => $item->description,
                        'age_min_months' => $item->age_min_months,
                        'age_max_months' => $item->age_max_months,
                    ];
                })->values()->toArray(),
                'total_items' => $areaItems->count(),
            ];
        }

        return [
            'child' => [
                'id' => $child->getHashedKey(),
                'full_name' => $child->full_name,
                'birth_date' => $child->birth_date->format('Y-m-d'),
            ],
            'evaluation_info' => [
                'age_months' => $ageMonths,
                'age_readable' => Child::formatAgeFromMonths($ageMonths),
            ],
            'items_by_area' => $formattedItems,
            'total_items' => $items->count(),
        ];
    }
}

