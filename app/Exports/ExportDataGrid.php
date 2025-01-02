<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ExportDataGrid implements FromCollection, WithStyles, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    protected $model, $headers, $column_formats, $set_columns_headers;

    public function __construct($headers = [], $model = [], $column_formats = [], $columns = '')
    {
        $this->model = $model;
        $this->headers = $headers;
        $this->column_formats = $column_formats;
        $this->set_columns_headers = $columns;
    }

    public function collection()
    {
        return $this->model;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->set_columns_headers != ''){
            return [
                $this->set_columns_headers => [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'ff7900',
                        ],
                    ],
                ]
            ];
        }

        return [];
    }

    public function columnFormats(): array
    {
        return $this->column_formats;
    }
}
