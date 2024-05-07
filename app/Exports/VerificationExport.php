<?php

namespace App\Exports;

use App\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Drawing\Checkbox;

// secret key 1
class VerificationExport implements WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id)
    {
        $this->cons_id = $id;
    }
    
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('export/Verification_Slip.xlsx'));
                // $templateFile = new LocalTemporaryFile(storage_path('app/public/files/mytemplate.xlsx'))
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);
                $sheet1 = $event->writer->getSheetByIndex(1);

                $this->populateSheet($sheet);
                $this->populateSheet($sheet1);
                
                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }
    private function populateSheet($sheet)
    {
        // Populate the sheet
        $details = $this->query();
        $sheet->mergeCells('W6:AA6');
        $sheet->setCellValue('W6',Carbon::now()->format('M d, Y'));
        $sheet->mergeCells('C8:O8');
        $sheet->setCellValue('C8',$details->cm_full_name);
        $sheet->mergeCells('F9:O9');
        $sheet->setCellValue('F9',$details->cm_contact_num);
        $sheet->mergeCells('D10:W10');
        $sheet->setCellValue('D10',$details->cm_address);

    }
    public function query(){
        
        $query = DB::table('cons_master as cm')
        ->join('cons_attachment as ca','cm.cm_id','=','ca.cm_id')
        ->where('cm.cm_remarks','online')
        ->where('cm.pending',1)
        ->where('cm.cm_id',$this->cons_id)
        ->first();
        
        return $query;

    }

    
    
}
