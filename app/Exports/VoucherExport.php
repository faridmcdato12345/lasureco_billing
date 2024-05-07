<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
// use App\Models\Voucher;
use Carbon\Carbon;
// use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VoucherExport implements WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id)
    {
        $this->voucher_id = $id;
        // dd($id);
    }
    
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                
                $templateFile = new LocalTemporaryFile(storage_path('app/public/voucher_data.xlsx'));
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
        $signatoryQuery = $this->signatoryQuery();
        // $currentRow1 = 44;
        foreach($signatoryQuery as $sQ){
            $sQTitle_id = $sQ->signatory_title_id;
            if($sQTitle_id == "1"){
                $sheet->setCellValue('A37',$sQ->name);
            }
            else if($sQTitle_id == "4"){
                $sheet->setCellValue('D37',$sQ->name);
            }
            else if($sQTitle_id == "5"){
                $sheet->setCellValue('G36',$sQ->name);
            }
            else if($sQTitle_id == "3"){
                $sheet->setCellValue('A42',$sQ->name);
            }
            else if($sQTitle_id == "2"){
                $sheet->setCellValue('D42',$sQ->name);
            }
            else if($sQTitle_id == "6"){
                $sheet->setCellValue('G41',$sQ->name);
            }
            
        }
        $engWord = $this->convertCurrencyToWords($details->amount);
        $sheet->getColumnDimension('B')->setWidth(12);
        if($details->funds_id === 2){
            $sheet->setCellValue('D5','CASH VOUCHER');
        }else{
            $sheet->setCellValue('D5','CHEQUE VOUCHER');
        }
        $sheet->setCellValue('B6',$details->voucher_code);
        $sheet->setCellValue('C8',$details->ptto);
        $sheet->setCellValue('B10',strtoupper($engWord));
        $variable = Carbon::parse($details->created_at)->format('M d, Y');
        $sheet->setCellValue('A15',$variable);
        $sheet->setCellValue('D14',$details->particular);
        
        $mergedRange = 'D14:H16';
        // $mergedCells = $sheet->getMergeCells();
        $cellRange = Coordinate::rangeBoundaries($mergedRange);
        $endingCell = $cellRange[1];
        $highestRow = $endingCell[1];
        $highestRow1 = intval($highestRow) - 1; 
        $sheet->setCellValue('K' . $highestRow1, $details->amount);

        $acctcodes = $this->accounting_codes();
        $currentRow = 32; // Start from row 32

        foreach ($acctcodes as $acctcode) {
            // Move existing row down
            $sheet->insertNewRowBefore($currentRow);
            
            $gCode = json_decode($acctcode->g_code, true);
            $joinedValues = implode('-', $gCode);
            
            $sheet->setCellValue('A'.$currentRow, $acctcode->name);
            $sheet->setCellValue('D'.$currentRow, $joinedValues);
            
            if ($acctcode->debit == 1) {
                $sheet->setCellValue('G'.$currentRow, floatval($acctcode->amount));
            } else {
                $sheet->setCellValue('K'.$currentRow, floatval($acctcode->amount));
            }
            $cellRange = 'A'.$currentRow.':C'.$currentRow;
            $sheet->mergeCells($cellRange);
            $cellRange2 = 'D'.$currentRow.':F'.$currentRow;
            $sheet->mergeCells($cellRange2);
            $cellRange3 = 'G'.$currentRow.':J'.$currentRow;
            $sheet->mergeCells($cellRange3);
            $style = $sheet->getStyle($cellRange);
            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            $currentRow++; // Increment the row number for the next iteration
        }
        
        
    }
    public function query(){
        
        $query = DB::table('vouchers as v')
        ->where('v.id',$this->voucher_id)
        ->first();
        
        return $query;

    }

    public function signatoryQuery(){
        $signatoryQuery = DB::table('signatory_voucher as sv')
        ->join('signatory as s','sv.signatory_id', '=', 's.id')
        ->where('sv.voucher_id',$this->voucher_id)
        ->get();

        return $signatoryQuery;
    }
    public function accounting_codes(){
        $query = DB::table('accounting_code_voucher')
        ->join('accounting_codes', 'accounting_code_voucher.accounting_code_id', '=', 'accounting_codes.id')
        ->where('accounting_code_voucher.voucher_id', $this->voucher_id)
        ->get();

        return $query;
    }
public function convertCurrencyToWords($amount)
{
    $number = number_format($amount, 2, '.', '');
    $decimalPart = intval(substr($number, -2));
    
    $decimalWords = '';
    if ($decimalPart > 0) {
        $decimalWords = 'and ' . $this->convertNumberToWords($decimalPart) . ' cents';
    }
    
    $integerPart = intval($number);
    $integerWords = $this->convertNumberToWords($integerPart);
    
    return ucfirst($integerWords) . ' pesos ' . $decimalWords;
}

public function convertNumberToWords($number)
{
    $units = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    $teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    $thousands = ['', 'thousand', 'million', 'billion', 'trillion', 'quadrillion'];
    
    if ($number == 0) {
        return 'zero';
    }
    
    $parts = explode('.', $number);
    $integerPart = intval($parts[0]);
    $decimalPart = isset($parts[1]) ? intval($parts[1]) : 0;
    
    $integerWords = [];
    $thousandCount = 0;
    
    while ($integerPart > 0) {
        $chunk = $integerPart % 1000;
        if ($chunk != 0) {
            $chunkWords = [];
            
            if ($chunk >= 100) {
                $hundredsDigit = intval($chunk / 100);
                $chunkWords[] = $units[$hundredsDigit] . ' hundred';
                $chunk %= 100;
            }
            
            if ($chunk >= 10 && $chunk <= 19) {
                $chunkWords[] = $teens[$chunk - 10];
            } else {
                $tensDigit = intval($chunk / 10);
                if ($tensDigit > 0) {
                    $chunkWords[] = $tens[$tensDigit];
                }
                
                $unitsDigit = $chunk % 10;
                if ($unitsDigit > 0) {
                    $chunkWords[] = $units[$unitsDigit];
                }
            }
            
            if ($thousandCount > 0) {
                $chunkWords[] = $thousands[$thousandCount];
            }
            
            $integerWords[] = implode(' ', $chunkWords);
            }
            
            $integerPart = intval($integerPart / 1000);
            $thousandCount++;
        }
        
        $integerWords = array_reverse($integerWords);
        $result = implode(' ', $integerWords);
        
        if ($decimalPart > 0) {
            $result .= ' and' . $this->convertNumberToWords($decimalPart) . ' cents';
        }
        
        return $result;
    }
}