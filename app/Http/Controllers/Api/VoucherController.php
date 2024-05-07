<?php

namespace App\Http\Controllers\Api;

use App\Exports\VoucherExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use App\Models\Signatory;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Maatwebsite\Excel\Facades\Excel;

class VoucherController extends Controller
{
    public function index()
    {
        $signatory = Voucher::with(['signatorys','funds','accountingCodeVoucher'])->get();
        
        return response($signatory);
    }
    public function store(VoucherRequest $request)
    {
        // For Voucher Code Sample
        $vc = Carbon::now();
        $replace = str_replace(':','',$vc->toTimeString());
        $newVC = $vc->year.''.$vc->day.''.$replace;
        
        $voucher = new Voucher();
        $voucher->particular = $request->particular;
        $voucher->ptto = $request->ptto;
        $voucher->amount = $request->amount;
        $voucher->voucher_code = $newVC;
        $voucher->user_id = $request->user_id;
        $voucher->funds_id = $request->funds_id;
        if($voucher->save()){
            // For All  Active Signatory To Voucher
            $signatory = Signatory::where('status',1)->get();
            foreach($signatory as $key=>$value)
            {
                $voucher->signatorys()->attach($voucher->id,[
                    'signatory_id' => $value['id'],
                    'voucher_id' => $voucher->id
                ]);
            }
            // Add to Pivot Table (accounting Code Voucher)
            $data = json_decode($request->getContent(),true);
            $datas = $data['acc_code'];
            foreach($datas as $key =>$value)
            {
                $voucher->accountingCodeVoucher()->attach($voucher->id,[
                    'accounting_code_id' => $value['acc_id'],
                    'voucher_id' => $voucher->id,
                    'debit' => $value['debit'],
                    'credit' => $value['credit'],
                    'amount' => $value['amount']
                ]);
            }
        }
        $filename = 'voucher_data.xlsx';
        
        $filePath = storage_path('app/public/' . $filename);
        $export = new VoucherExport($voucher->id);
        
        return Excel::download($export, $filename)
            ->deleteFileAfterSend(true)
            ->setStatusCode(201);
    }

    public function viewVoucher($voucherID)
    {
        $query = collect(DB::table('vouchers')
        ->select('*')
        ->where('id',$voucherID)
        ->get()
    );
        
        // dd($query);
        return response($query,200);
    }

    public function viewACV($voucherID)
    {
        $query = collect(DB::table('accounting_code_voucher as acv')
        ->select('*')
        ->join('accounting_codes as ac', 'ac.id', '=','acv.accounting_code_id')
        ->where('voucher_id',$voucherID)
        ->get()
    );
        
        $data = $query->map(function($item){
            return[
                'accounting_code_id' => $item->accounting_code_id,
                'gcode' => $item->g_code,
                'accounting_description' => $item->name,
                'debit' => $item->debit,
                'credit' => $item->credit,
                'amount' => $item->amount,
                'voucher_id' => $item->voucher_id,
            ];
        })->groupBy(['voucher_id']);
        // dd($data);

        return response($data,200);
    }
    public function updateACV(Request $request,$id){
        $data = $request->arrdata;
        $data1 = $request->voucherData;
        // $arrpush = [];
        foreach($data as $arrdata){
        $existingItems = DB::table('accounting_code_voucher')
        ->where('accounting_code_id', $arrdata['accounting_code_id'])
        ->where('voucher_id', $id)
        ->get()
        ->toArray();

        if (empty($existingItems)) {
            DB::table('accounting_code_voucher')->insert([
                'accounting_code_id' => $arrdata['accounting_code_id'],
                'voucher_id' => $id,
                'debit' => intval($arrdata['debit']),
                'credit' => intval($arrdata['credit']),
                'amount' => $arrdata['amount'],
            ]);
        } else {
            DB::table('accounting_code_voucher')
                ->where('accounting_code_id', $arrdata['accounting_code_id'])
                ->where('voucher_id', $id)
                ->update([
                    'debit' => intval($arrdata['debit']),
                    'credit' => intval($arrdata['credit']),
                    'amount' => $arrdata['amount'],
                ]);
        }
        }
        foreach($data1 as $voucherData){
             DB::table('accounting_code_voucher')->insert([
                'accounting_code_id' => $voucherData['acc_id'],
                'voucher_id' => $id,
                'debit' => intval($voucherData['debit']),
                'credit' => intval($voucherData['credit']),
                'amount' => $voucherData['amount'],
            ]);
        }
        return response(['data'=>'yes'],200);
    }
    public function reprint($id)
    {

        
        $filename = 'voucher_data.xlsx';
        $filePath = storage_path('app/public/' . $filename);
        $export = new VoucherExport($id);
        
        return Excel::download($export, $filename)
            ->deleteFileAfterSend(true)
            ->setStatusCode(201);
    }
    public function removeACV(Request $request,$id){
        $accid = $request->acc_id;
        DB::table('accounting_code_voucher')
        ->where('accounting_code_id', $accid) 
        ->where('voucher_id',$id)
        ->delete();
        
        return response(['info'=>'deleted'],200);
    }
    public function updateVoucher(Request $request,$id){
        DB::table('vouchers')
        ->where('id', $id)
        ->update([
            'particular' => $request->particular,
            'amount' => $request->amount,
            'ptto' => $request->ptto
        ]);
        // ->save();

        return response(['info'=>'successfully updated'],200);
    }
    public function deleteVoucher($id){
        
        $query = DB::table('accounting_code_voucher')
        ->where('voucher_id',$id)
        ->delete();
        if($query){
            $query1 = DB::table('signatory_voucher')
            ->where('voucher_id',$id)
            ->delete();
            if($query1){
                DB::table('vouchers')
                ->where('id',$id)
                ->delete();

                return response(['info'=>'successfully deleted'],200);
            }
            else{
                return response(['info'=>'voucher didnt successfully deleted'],405);
            }
        }
        else{
            return response(['info'=>'signatory voucher didnt successfully deleted'],405);
        }
    }
}
