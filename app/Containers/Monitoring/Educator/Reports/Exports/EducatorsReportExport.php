<?php

namespace App\Containers\Monitoring\Educator\Reports\Exports;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\AppSection\Settings\Models\Settings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EducatorsReportExport implements FromArray, WithTitle, WithEvents
{
    protected $educators;

    public function __construct($childcareCenterId = null)
    {
        // Load all educators with their relationships
        $query = Educator::with(['user', 'childcareCenters'])
            ->orderBy('first_name')
            ->orderBy('last_name');

        if ($childcareCenterId) {
            $query->whereHas('childcareCenters', function ($q) use ($childcareCenterId) {
                $q->where('childcare_centers.id', $childcareCenterId);
            });
        }

        $this->educators = $query->get();
    }

    public function array(): array
    {
        $data = [];

        // Title row
        $data[] = ['REPORTE DE EDUCADORES REGISTRADOS'];

        // Generation date and municipality header
        $municipality = Settings::get('servidor_municipio', 'Sin configurar');
        $data[] = ['Fecha de Generación: ' . now()->format('d/m/Y H:i:s')];
        $data[] = ['Municipio/Departamento: ' . $municipality];

        // Empty row for spacing
        $data[] = [''];

        // Headers row
        $data[] = [
            '#',
            'Nombre',
            'Apellido',
            'Correo Electrónico',
            'Género',
            'Fecha de Nacimiento',
            'DNI',
            'Departamento',
            'Teléfono',
            'Fecha Inicio Contrato',
            'Fecha Fin Contrato',
            'Centros de Cuidado Infantil',
            'Fecha de Registro',
        ];

        // Data rows
        $rowNumber = 1;
        foreach ($this->educators as $educator) {
            // Get childcare centers as comma-separated string
            $centers = $educator->childcareCenters->pluck('name')->join(', ');

            $data[] = [
                $rowNumber++,
                $educator->first_name,
                $educator->last_name,
                $educator->user ? $educator->user->email : '-',
                $educator->gender ? $educator->gender->label() : '-',
                $educator->birth ? $educator->birth->format('d/m/Y') : '-',
                $educator->dni ?? '-',
                $educator->state ?? '-',
                $educator->phone ?? '-',
                $educator->contract_start_date ? $educator->contract_start_date->format('d/m/Y') : '-',
                $educator->contract_end_date ? $educator->contract_end_date->format('d/m/Y') : '-',
                $centers ?: '-',
                $educator->created_at ? $educator->created_at->format('d/m/Y H:i:s') : '-',
            ];
        }

        if ($this->educators->isEmpty()) {
            $data[] = ['No hay educadores registrados'];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Educadores';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Merge title row
                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Merge generation date row
                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                // Merge municipality row
                $sheet->mergeCells('A3:' . $lastColumn . '3');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                // Style headers row (row 5)
                $sheet->getStyle('A5:' . $lastColumn . '5')->applyFromArray([
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

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(6);  // #
                $sheet->getColumnDimension('B')->setWidth(20); // Nombre
                $sheet->getColumnDimension('C')->setWidth(20); // Apellido
                $sheet->getColumnDimension('D')->setWidth(30); // Correo
                $sheet->getColumnDimension('E')->setWidth(12); // Género
                $sheet->getColumnDimension('F')->setWidth(18); // Fecha Nacimiento
                $sheet->getColumnDimension('G')->setWidth(15); // DNI
                $sheet->getColumnDimension('H')->setWidth(15); // Departamento
                $sheet->getColumnDimension('I')->setWidth(15); // Teléfono
                $sheet->getColumnDimension('J')->setWidth(18); // Fecha Inicio Contrato
                $sheet->getColumnDimension('K')->setWidth(18); // Fecha Fin Contrato
                $sheet->getColumnDimension('L')->setWidth(40); // Centros
                $sheet->getColumnDimension('M')->setWidth(20); // Fecha Registro

                // Apply borders to data rows (starting from row 6)
                if ($lastRow > 5) {
                    $sheet->getStyle('A6:' . $lastColumn . $lastRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                    ]);

                    // Center align specific columns
                    $sheet->getStyle('A6:A' . $lastRow)->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->getStyle('E6:E' . $lastRow)->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->getStyle('F6:K' . $lastRow)->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }

                // Freeze panes
                $sheet->freezePane('A6');
            },
        ];
    }
}
