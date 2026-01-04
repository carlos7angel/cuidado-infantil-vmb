<?php

namespace App\Containers\Monitoring\Attendance\Reports\Exports;

use App\Containers\Monitoring\Attendance\Tasks\GetAttendanceReportTask;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class AttendanceXlsReportExport implements FromArray, WithTitle, WithEvents
{
    use Exportable;

    protected $request;
    protected $reportData;
    protected $weeks;
    protected $reportType;

    public function __construct(object $request)
    {
        $this->request = $request;
        $this->reportData = app(GetAttendanceReportTask::class)->run($request);
        $this->reportType = $request->input('report_type', 'complete');
        $this->groupDatesByWeeks();
    }

    /**
     * Agrupa las fechas por semanas (Lunes a Sábado)
     */
    protected function groupDatesByWeeks(): void
    {
        $this->weeks = [];
        $currentWeek = [];
        $weekNumber = 1;

        foreach ($this->reportData['dates'] as $date) {
            $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
            $dayOfWeek = $carbonDate->dayOfWeek; // 0 = domingo, 1 = lunes, ..., 6 = sábado

            // Si es domingo (0), empezar nueva semana
            if ($dayOfWeek === 0 && !empty($currentWeek)) {
                $this->weeks['S' . $weekNumber] = $currentWeek;
                $currentWeek = [];
                $weekNumber++;
            }

            // Solo agregar días de Lunes a Sábado (1-6)
            if ($dayOfWeek >= 1 && $dayOfWeek <= 6) {
                $currentWeek[] = $date;
            }
        }

        // Agregar la última semana si tiene datos
        if (!empty($currentWeek)) {
            $this->weeks['S' . $weekNumber] = $currentWeek;
        }
    }

    /**
     * Obtiene el título del mes basado en el rango de fechas
     */
    protected function getMonthTitle(): string
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->reportData['dates'][0]);
        $endDate = Carbon::createFromFormat('Y-m-d', end($this->reportData['dates']));
        
        $months = [
            1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
            5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
            9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
        ];

        $startMonth = $months[$startDate->month];
        $endMonth = $months[$endDate->month];

        if ($startDate->month === $endDate->month && $startDate->year === $endDate->year) {
            return 'ASISTENCIA DEL MES DE ' . $startMonth . ' ' . $startDate->year;
        }

        return 'ASISTENCIA DEL ' . $startDate->format('d/m/Y') . ' AL ' . $endDate->format('d/m/Y');
    }

    /**
     * Calcula las estadísticas de resumen para un niño
     */
    protected function calculateSummary($child): array
    {
        $faltas = 0;
        $tardanzas = 0;
        $justificadas = 0;
        $asistencias = 0;

        foreach ($this->reportData['dates'] as $date) {
            $attendance = $child['attendance'][$date] ?? null;
            $status = $attendance['status'] ?? null;

            if ($status) {
                switch ($status) {
                    case 'presente':
                        $asistencias++;
                        break;
                    case 'retraso':
                        $tardanzas++;
                        $asistencias++; // Retraso cuenta como asistencia
                        break;
                    case 'falta':
                        $faltas++;
                        break;
                    case 'justificado':
                        $justificadas++;
                        break;
                }
            } else {
                $faltas++;
            }
        }

        $totalDays = count($this->reportData['dates']);
        $percentage = $totalDays > 0 ? round(($asistencias / $totalDays) * 100, 1) : 0;

        return [
            'faltas' => $faltas,
            'tardanzas' => $tardanzas,
            'justificadas' => $justificadas,
            'asistencias' => $asistencias,
            'porcentaje' => $percentage,
        ];
    }

    /**
     * Obtiene el símbolo/marcador para el estado de asistencia
     */
    protected function getStatusMarker($status): string
    {
        if (!$status) {
            return '';
        }

        if ($this->reportType === 'simplified') {
            if ($status === 'presente' || $status === 'retraso') {
                return '1';
            } elseif ($status === 'falta' || $status === 'justificado') {
                return '0';
            }
            return '';
        }

        // Reporte completo - usar símbolos simples que se rendericen bien en Excel
        switch ($status) {
            case 'presente':
                return 'P';
            case 'retraso':
                return 'R';
            case 'falta':
                return 'F';
            case 'justificado':
                return 'J';
            default:
                return '';
        }
    }

    public function array(): array
    {
        $data = [];

        // Fila 1: Título (se fusionará más tarde)
        $titleRow = [$this->getMonthTitle()];
        $data[] = $titleRow;

        // Fila 2: Encabezados de semanas (se fusionarán más tarde)
        $weekHeadersRow = ['#', 'NOMBRES Y APELLIDOS'];
        foreach ($this->weeks as $weekLabel => $weekDates) {
            // Agregar espacios para las columnas de esta semana
            for ($i = 0; $i < count($weekDates); $i++) {
                $weekHeadersRow[] = ($i === 0) ? $weekLabel : '';
            }
        }
        // Agregar columnas de resumen
        $weekHeadersRow[] = 'FALTAS';
        $weekHeadersRow[] = 'TARDANZAS';
        $weekHeadersRow[] = 'JUSTIFICADAS';
        $weekHeadersRow[] = 'ASISTENCIAS';
        $weekHeadersRow[] = '% DE ASISTENCIA';
        $data[] = $weekHeadersRow;

        // Fila 3: Iniciales de días (L M M J V S)
        $dayInitialsRow = ['', ''];
        foreach ($this->weeks as $weekDates) {
            foreach ($weekDates as $date) {
                $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
                $dayOfWeek = $carbonDate->dayOfWeek;
                $initials = ['', 'L', 'M', 'M', 'J', 'V', 'S'];
                $dayInitialsRow[] = $initials[$dayOfWeek] ?? '';
            }
        }
        // Columnas de resumen vacías
        $dayInitialsRow[] = '';
        $dayInitialsRow[] = '';
        $dayInitialsRow[] = '';
        $dayInitialsRow[] = '';
        $dayInitialsRow[] = '';
        $data[] = $dayInitialsRow;

        // Fila 4: Números de día
        $dayNumbersRow = ['', ''];
        foreach ($this->weeks as $weekDates) {
            foreach ($weekDates as $date) {
                $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
                $dayNumbersRow[] = $carbonDate->day;
            }
        }
        // Columnas de resumen vacías
        $dayNumbersRow[] = '';
        $dayNumbersRow[] = '';
        $dayNumbersRow[] = '';
        $dayNumbersRow[] = '';
        $dayNumbersRow[] = '';
        $data[] = $dayNumbersRow;

        // Filas de datos
        $rowNumber = 1;
        foreach ($this->reportData['children'] as $child) {
            $summary = $this->calculateSummary($child);
            $dataRow = [
                $rowNumber++,
                $child['child_name'],
            ];

            // Agregar marcadores de asistencia para cada fecha
            foreach ($this->weeks as $weekDates) {
                foreach ($weekDates as $date) {
                    $attendance = $child['attendance'][$date] ?? null;
                    $status = $attendance['status'] ?? null;
                    $dataRow[] = $this->getStatusMarker($status);
                }
            }

            // Agregar columnas de resumen
            $dataRow[] = $summary['faltas'];
            $dataRow[] = $summary['tardanzas'];
            $dataRow[] = $summary['justificadas'];
            $dataRow[] = $summary['asistencias'];
            $dataRow[] = $summary['porcentaje'];

            $data[] = $dataRow;
        }

        return $data;
    }

    public function title(): string
    {
        return 'Asistencia';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                $lastColumnIndex = Coordinate::columnIndexFromString($lastColumn);

                // Fusionar celdas del título (fila 1)
                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->setCellValue('A1', $this->getMonthTitle());
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Fusionar celdas de encabezados de semanas (fila 2)
                $currentColumn = 3; // Empezar desde la columna C (después de # y NOMBRES)
                foreach ($this->weeks as $weekLabel => $weekDates) {
                    $weekColumnCount = count($weekDates);
                    if ($weekColumnCount > 0) {
                        $startColumn = Coordinate::stringFromColumnIndex($currentColumn);
                        $endColumn = Coordinate::stringFromColumnIndex($currentColumn + $weekColumnCount - 1);
                        $sheet->mergeCells($startColumn . '2:' . $endColumn . '2');
                        $sheet->getStyle($startColumn . '2')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 11,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'E3F2FD',
                                ],
                            ],
                        ]);
                        $currentColumn += $weekColumnCount;
                    }
                }

                // Formatear filas de encabezados (filas 2, 3, 4)
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E3F2FD',
                        ],
                    ],
                ];

                // Fila 2 (encabezados de semanas y resumen)
                $sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray($headerStyle);
                
                // Fila 3 (iniciales de días)
                $sheet->getStyle('A3:' . $lastColumn . '3')->applyFromArray($headerStyle);
                
                // Fila 4 (números de día)
                $sheet->getStyle('A4:' . $lastColumn . '4')->applyFromArray($headerStyle);

                // Formatear columna de nombres
                $sheet->getColumnDimension('A')->setWidth(6); // Columna #
                $sheet->getColumnDimension('B')->setWidth(35); // Columna NOMBRES Y APELLIDOS

                // Ajustar ancho de las columnas de fechas
                $dateColumnStart = 3;
                foreach ($this->weeks as $weekDates) {
                    foreach ($weekDates as $date) {
                        $columnLetter = Coordinate::stringFromColumnIndex($dateColumnStart);
                        $sheet->getColumnDimension($columnLetter)->setWidth(5.5);
                        $dateColumnStart++;
                    }
                }

                // Formatear columnas de resumen (últimas 5 columnas)
                $summaryColumns = ['FALTAS', 'TARDANZAS', 'JUSTIFICADAS', 'ASISTENCIAS', '% DE ASISTENCIA'];
                $summaryStartColumn = $lastColumnIndex - 4;
                for ($i = 0; $i < 5; $i++) {
                    $columnLetter = Coordinate::stringFromColumnIndex($summaryStartColumn + $i);
                    $sheet->getColumnDimension($columnLetter)->setWidth(12);
                }

                // Aplicar bordes a las columnas de resumen
                $summaryStartLetter = Coordinate::stringFromColumnIndex($summaryStartColumn);
                $sheet->getStyle($summaryStartLetter . '2:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Centrar números de día y marcadores
                $dataStartRow = 5; // Fila 5 en adelante son datos
                $dateColumnsEnd = Coordinate::stringFromColumnIndex($dateColumnStart - 1);
                $sheet->getStyle('C' . $dataStartRow . ':' . $dateColumnsEnd . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Centrar columnas de resumen
                $sheet->getStyle($summaryStartLetter . $dataStartRow . ':' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Congelar paneles (primera fila de datos y primeras dos columnas)
                $sheet->freezePane('C5');
            },
        ];
    }
}
