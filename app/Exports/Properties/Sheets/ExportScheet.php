<?php

namespace App\Exports\Properties\Sheets;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportScheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    protected $title_sheet, $data, $headers, $column_formats, $set_columns_headers;

    public function __construct($title_sheet = '', $headers = [], $data = [], $columns = '')
    {
        $this->data = $data;
        $this->headers = $headers;
        $this->title_sheet = $title_sheet;
        $this->set_columns_headers = $columns;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return $this->title_sheet;
    }

    public function collection()
    {
        return $this->data;
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
}
