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

// secret key
class InspectionExport implements WithEvents
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
                $templateFile = new LocalTemporaryFile(storage_path('export/Inspection_Report.xlsx'));
                // $templateFile = new LocalTemporaryFile(storage_path('app/public/files/mytemplate.xlsx'))
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $this->populateSheet($sheet);
                
                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }
    private function populateSheet($sheet)
    {
        // Populate the sheet
        $details = $this->query();
        // $sheet->insertNewRowBefore($i);
        $sheet->setCellValue('C15',$details->cm_full_name);
        $sheet->setCellValue('C16',$details->cm_address);
        // $sheet->insertNewCheckbox('C17', true);
        $sheet->mergeCells('J12:K12');
        $sheet->setCellValue('J12',Carbon::now()->format('M d, Y'));

        // Add a checkbox effect in cell C17
        $sheet->setCellValue('C17', '☐');
        $sheet->setCellValue('F17', '☐');
        $sheet->setCellValue('I17', '☐');
        $sheet->setCellValue('I17', '☐');
        $sheet->setCellValue('C19', '☐');
        $sheet->setCellValue('f19', '☐');
        $sheet->getStyle('C17')->getFont()->setSize(14);
        $sheet->getStyle('F17')->getFont()->setSize(14);
        $sheet->getStyle('I17')->getFont()->setSize(14);
        $sheet->getStyle('I17')->getFont()->setSize(14);
        $sheet->getStyle('C19')->getFont()->setSize(14);
        $sheet->getStyle('f19')->getFont()->setSize(14);
        $sheet->getStyle('C17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('F17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('I17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('I17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('C19')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('f19')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        // $sheet->getStyle('C17')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Center align the merged cell
        $cellRange = 'J12:K12'; // Specify the merged cell range
        $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

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
