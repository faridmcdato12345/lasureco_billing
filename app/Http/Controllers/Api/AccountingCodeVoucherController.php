<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountingCodeRequest;
use App\Http\Requests\AccountingCodeVoucherRequest;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountingCodeVoucherController extends Controller
{
    public function viewVoucher($id)
    {
        $data = collect(Voucher::with('accountingCodes')->where('id', $id)->get());
        // $accountingCodesAttributes = $data->first()->accountingCodes
        // $accountingCodesAttributes = $data->first()->accountingCodes->map(function ($accountingCode) {
        //     return $accountingCode->getOriginal();
        // })->toArray();

        $voucher = $data->first();
        $pivotData = $voucher->accountingCodes->first()->pivot->getOriginal();
        dd($voucher);
        
// return response()->json($response, 200);
    }
    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent(),true);
        // dd($data);
        $datas = $data['acc_code'];
        // dd($data['acc_code']);
        DB::table('vouchers as v')
        ->where('v.id',$data['voucher_id'])
        ->update([
            'particular' => $data['particular'],
            'amount' => $data['amount']
        ]);
        DB::table('accounting_code_voucher')
        ->where('voucher_id',$id)
        ->delete();
        // dd(count($data['acc_codes']));
        foreach($datas as $key =>$value){          
        DB::table('accounting_code_voucher')
            ->insert([
                'voucher_id' => $id,
                'accounting_code_id' => $value['acc_id'],
                'debit' => $value['debit'],
                'credit' => $value['credit'],
                'amount' => $value['amount']
            ]);
        }

        return response(['info'=>'Succesfully Updated'],200);
    }
}
