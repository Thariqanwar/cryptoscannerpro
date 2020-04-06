<?php

namespace App;



use App\AlertLog;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportExcel implements FromQuery
{
    use Exportable;

    public function query()
    {
        return AlertLog::orderBy('id','DESC')->get();
    }
}