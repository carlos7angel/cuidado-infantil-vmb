<?php

namespace App\Containers\Monitoring\Child\Reports\Exports;

use App\Containers\Monitoring\Child\Models\Child;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DevelopmentEvaluationsSheet implements FromArray, WithTitle, WithEvents
{
    protected Child $child;

    public function __construct(Child $child)
    {
        $this->child = $child;
    }

    public function array(): array
    {
        $data = [];
        
        // Título
        $data[] = ['REPORTE DE EVALUACIONES DE DESARROLLO INFANTIL'];
        $data[] = ['Infante: ' . $this->child->full_name];
        
        // Encabezados
        $data[] = [
            '#',
            'Fecha',
            'Edad (meses)',
            'MG Puntaje',
            'MG Estado',
            'MF Puntaje',
            'MF Estado',
            'AL Puntaje',
            'AL Estado',
            'PS Puntaje',
            'PS Estado',
            'Indicadores Logrados',
            'Indicadores No Logrados',
            'Total Indicadores',
            'Observaciones',
        ];
        
        // Datos
        $evaluations = $this->child->developmentEvaluations()
            ->with(['scores', 'evaluationItems'])
            ->orderBy('evaluation_date', 'desc')
            ->get();
        
        $rowNumber = 1;
        
        foreach ($evaluations as $evaluation) {
            // Obtener puntajes y estados MG, MF, AL, PS
            $mgScore = '-';
            $mgStatus = '-';
            $mfScore = '-';
            $mfStatus = '-';
            $alScore = '-';
            $alStatus = '-';
            $psScore = '-';
            $psStatus = '-';
            
            $scores = $evaluation->scores->keyBy('area');
            
            // MG - Motricidad Gruesa
            if ($scores->has(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::MOTOR_GROSS->value)) {
                $mgScoreObj = $scores->get(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::MOTOR_GROSS->value);
                $mgScore = $mgScoreObj->raw_score ?? '-';
                $mgStatus = $mgScoreObj->status?->label() ?? '-';
            }
            
            // MF - Motricidad Fina
            if ($scores->has(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::MOTOR_FINE->value)) {
                $mfScoreObj = $scores->get(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::MOTOR_FINE->value);
                $mfScore = $mfScoreObj->raw_score ?? '-';
                $mfStatus = $mfScoreObj->status?->label() ?? '-';
            }
            
            // AL - Área del Lenguaje
            if ($scores->has(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::LANGUAGE->value)) {
                $alScoreObj = $scores->get(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::LANGUAGE->value);
                $alScore = $alScoreObj->raw_score ?? '-';
                $alStatus = $alScoreObj->status?->label() ?? '-';
            }
            
            // PS - Personal Social
            if ($scores->has(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::PERSONAL_SOCIAL->value)) {
                $psScoreObj = $scores->get(\App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea::PERSONAL_SOCIAL->value);
                $psScore = $psScoreObj->raw_score ?? '-';
                $psStatus = $psScoreObj->status?->label() ?? '-';
            }
            
            // Calcular indicadores logrados y no logrados
            $allAccumulatedItems = \App\Containers\Monitoring\ChildDevelopment\Models\DevelopmentItem::getAccumulatedUpToAge($evaluation->age_months);
            $totalIndicators = $allAccumulatedItems->count();
            
            $achievedItemIds = $evaluation->evaluationItems()
                ->where('achieved', true)
                ->pluck('development_item_id')
                ->toArray();
            
            $achievedCount = count($achievedItemIds);
            $notAchievedCount = $totalIndicators - $achievedCount;
            
            $data[] = [
                $rowNumber++,
                $evaluation->evaluation_date->format('d/m/Y'),
                $evaluation->age_months ?? '-',
                $mgScore,
                $mgStatus,
                $mfScore,
                $mfStatus,
                $alScore,
                $alStatus,
                $psScore,
                $psStatus,
                $achievedCount,
                $notAchievedCount,
                $totalIndicators,
                $evaluation->notes ?? '-',
            ];
        }
        
        if ($evaluations->isEmpty()) {
            $data[] = ['No hay evaluaciones de desarrollo registradas'];
        }
        
        return $data;
    }

    public function title(): string
    {
        return 'Desarrollo Infantil';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
                // Fusionar celdas del título
                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Fusionar celdas del nombre del infante
                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Formatear encabezados (fila 3)
                $sheet->getStyle('A3:' . $lastColumn . '3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E3F2FD'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
                
                // Ajustar ancho de columnas
                $sheet->getColumnDimension('A')->setWidth(6);  // #
                $sheet->getColumnDimension('B')->setWidth(12); // Fecha
                $sheet->getColumnDimension('C')->setWidth(12); // Edad
                $sheet->getColumnDimension('D')->setWidth(12); // MG Puntaje
                $sheet->getColumnDimension('E')->setWidth(15); // MG Estado
                $sheet->getColumnDimension('F')->setWidth(12); // MF Puntaje
                $sheet->getColumnDimension('G')->setWidth(15); // MF Estado
                $sheet->getColumnDimension('H')->setWidth(12); // AL Puntaje
                $sheet->getColumnDimension('I')->setWidth(15); // AL Estado
                $sheet->getColumnDimension('J')->setWidth(12); // PS Puntaje
                $sheet->getColumnDimension('K')->setWidth(15); // PS Estado
                $sheet->getColumnDimension('L')->setWidth(18); // Indicadores Logrados
                $sheet->getColumnDimension('M')->setWidth(20); // Indicadores No Logrados
                $sheet->getColumnDimension('N')->setWidth(18); // Total Indicadores
                $sheet->getColumnDimension('O')->setWidth(30); // Observaciones
                
                // Aplicar bordes a los datos (solo a las filas de datos, no a los encabezados)
                if ($lastRow > 3) {
                    $sheet->getStyle('A4:' . $lastColumn . $lastRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                    ]);
                    // Asegurar que los encabezados mantengan su estilo
                    $sheet->getStyle('A3:' . $lastColumn . '3')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E3F2FD'],
                        ],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                    ]);
                }
                
                // Congelar paneles
                $sheet->freezePane('A4');
            },
        ];
    }
}

