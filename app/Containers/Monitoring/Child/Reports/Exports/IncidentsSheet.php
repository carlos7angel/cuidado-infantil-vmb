<?php

namespace App\Containers\Monitoring\Child\Reports\Exports;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class IncidentsSheet implements FromArray, WithTitle, WithEvents
{
    protected $child;
    protected $incidents;

    public function __construct(Child $child)
    {
        $this->child = $child;
        $this->incidents = IncidentReport::with([
            'child',
            'reportedBy',
            'childcareCenter',
            'room'
        ])
        ->where('child_id', $child->id)
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function array(): array
    {
        $data = [];

        // Title row
        $data[] = ['REPORTE DE INCIDENTES REGISTRADOS'];

        // Child Name
        $data[] = ['Infante: ' . $this->child->full_name];

        // Headers row
        $data[] = [
            '#',
            'Código',
            'Estado',
            'Tipo',
            'Fecha Incidente',
            'Hora Incidente',
            'Lugar',
            'Descripción',
            'Personas Involucradas',
            'Testigos',
            'Tiene Evidencia',
            'Descripción Evidencia',
            'Acciones Tomadas',
            'Comentarios Adicionales',
            'Notas Seguimiento',
            'Detalles Notificación Autoridades',
            'Fecha Reporte',
            'Reportado Por',
            'Centro Cuidado',
            'Sala',
            'Niño - Nombre',
            'Niño - Apellidos',
            'Niño - Edad',
            'Niño - Género',
            'Enlaces Evidencia',
        ];

        // Data rows
        $rowNumber = 1;

        foreach ($this->incidents as $incident) {

            // Process enums
            $statusLabel = $incident->status ? $incident->status->label() : '';
            $typeLabel = $incident->type ? $incident->type->label() : '';

            // Process relationships
            $reportedByName = $incident->reportedBy ? $incident->reportedBy->name : '';
            $childcareCenterName = $incident->childcareCenter ? $incident->childcareCenter->name : '';
            $roomName = $incident->room ? $incident->room->name : '';

            // Process child information
            $childName = '';
            $childLastNames = '';
            $childAge = '';
            $childGender = '';

            if ($incident->child) {
                $childName = $incident->child->first_name ?? '';
                $childLastNames = trim(($incident->child->paternal_last_name ?? '') . ' ' . ($incident->child->maternal_last_name ?? ''));

                if ($incident->child->birth_date) {
                    $childAge = $incident->child->birth_date->diffInYears(now()) . ' años';
                }

                if ($incident->child->gender) {
                    $childGender = $incident->child->gender->label();
                }
            }

            // Process evidence files
            $evidenceLinks = '';
            $evidenceFiles = $incident->evidenceFiles(); // Call the method
            if ($evidenceFiles && $evidenceFiles->count() > 0) {
                $evidenceLinks = $evidenceFiles->map(function ($file) {
                    return $file->getDownloadUrl();
                })->join(', ');
            }

            $data[] = [
                $rowNumber++,
                $incident->code ?? '',
                $statusLabel,
                $typeLabel,
                $incident->incident_date ? $incident->incident_date->format('d/m/Y') : '',
                $incident->incident_time ?? '',
                $incident->incident_location ?? '',
                $incident->description ?? '',
                $incident->people_involved ?? '',
                $incident->witnesses ?? '',
                $incident->has_evidence ? 'Sí' : 'No',
                $incident->evidence_description ?? '',
                $incident->actions_taken ?? '',
                $incident->additional_comments ?? '',
                $incident->follow_up_notes ?? '',
                $incident->authority_notification_details ?? '',
                $incident->reported_at ? $incident->reported_at->format('d/m/Y H:i:s') : '',
                $reportedByName,
                $childcareCenterName,
                $roomName,
                $childName,
                $childLastNames,
                $childAge,
                $childGender,
                $evidenceLinks,
            ];
        }

        if ($this->incidents->isEmpty()) {
            // $data[] = ['No hay incidentes registrados'];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Incidentes';
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

                // Merge child name row
                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Style headers row (row 3)
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

                // Set column widths
                $widths = [
                    'A' => 6,   // #
                    'B' => 15,  // Código
                    'C' => 12,  // Estado
                    'D' => 15,  // Tipo
                    'E' => 12,  // Fecha Incidente
                    'F' => 12,  // Hora Incidente
                    'G' => 20,  // Lugar
                    'H' => 40,  // Descripción
                    'I' => 25,  // Personas Involucradas
                    'J' => 25,  // Testigos
                    'K' => 12,  // Tiene Evidencia
                    'L' => 30,  // Descripción Evidencia
                    'M' => 30,  // Acciones Tomadas
                    'N' => 30,  // Comentarios Adicionales
                    'O' => 30,  // Notas Seguimiento
                    'P' => 30,  // Detalles Notificación Autoridades
                    'Q' => 18,  // Fecha Reporte
                    'R' => 20,  // Reportado Por
                    'S' => 25,  // Centro Cuidado
                    'T' => 15,  // Sala
                    'U' => 15,  // Niño - Nombre
                    'V' => 25,  // Niño - Apellidos
                    'W' => 10,  // Niño - Edad
                    'X' => 10,  // Niño - Género
                    'Y' => 50,  // Enlaces Evidencia
                ];

                foreach ($widths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Apply borders to data rows (starting from row 4)
                if ($lastRow > 3) {
                    $sheet->getStyle('A4:' . $lastColumn . $lastRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                    ]);

                    // Center align specific columns
                    $centerColumns = ['A', 'C', 'D', 'E', 'F', 'K', 'W', 'X'];
                    foreach ($centerColumns as $col) {
                        $sheet->getStyle($col . '4:' . $col . $lastRow)->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    }
                }

                // Freeze panes
                $sheet->freezePane('A4');

            },
        ];
    }
}
