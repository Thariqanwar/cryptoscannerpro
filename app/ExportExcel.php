<?php

namespace App;


use App\Invoice;
use App\AlertLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportExcel implements FromView
{
    public function view(): View
    {
        return view('admin.alert_log_table', [
            'alerts' =>AlertLog::orderBy('id','DESC')->get()
        ]);
    }
}
