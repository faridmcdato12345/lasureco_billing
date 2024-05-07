<?php

namespace App\Http\Controllers;

use App\Exports\VoucherExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportToExcel($id)
    {
        $fileName = 'voucher_data.xlsx';

        return Excel::download(new VoucherExport($id), $fileName); 
    }
}
?>