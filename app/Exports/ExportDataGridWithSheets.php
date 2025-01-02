<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Properties\Sheets\ExportScheet;

class ExportDataGridWithSheets implements WithMultipleSheets
{
    use Exportable;

    protected $models;

    public function __construct($models = [])
    {
        $this->models = $models;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach($this->models as $model) {
            $sheets[] = new ExportScheet($model['title'], $model['headers'], $model['data']);
        }

        return $sheets;
    }
}
