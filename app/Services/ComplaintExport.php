<?php

namespace App\Services;

use App\Models\Cheque;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ComplaintExport implements FromQuery, WithMapping, WithHeadings, WithTitle {

    use Exportable;

    private $fromDate;
    private $toDate;

    public function __construct($fromDate,$toDate){
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
    public function query()
    {
        $complaint = DB::table('complaint_list')
        ->join('cons_master as cm', 'complaint_list.cmid','=','cm.cm_id')
        ->select('complaint_no','category','finding','recommendation','sub_category','cm.cm_full_name as ffname','cm.cm_account_no as accno')
        ->whereDate('complaint_list.created_at','>=',$this->fromDate)
        ->whereDate('complaint_list.created_at','<=',$this->toDate)
        ->orderByDesc('complaint_no');
        return $complaint;
    }
    public function headings(): array
    {
        return [
            'Name',
            'Account No',
            'complaint_no',
            'category',
            'sub_category',
            'Finding',
            'Recommendation'
        ];
    }
    public function map($complaint): array{
        return [
            $complaint->ffname,
            $complaint->accno,
            $complaint->complaint_no,
            $complaint->category,
            $complaint->sub_category,
            $complaint->finding,
            $complaint->recommendation,
        ];
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Complaint';
    }
}