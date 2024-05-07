<?php
namespace App\Services;

use App\Models\MeterReg;

class GCashConsumerInquiry {
    private $totalAmount;
    private $powerBillNumber = [];
    private $billCount;
    public function getConsumerPowerBills($accountNumber)
    {
        $data = MeterReg::select(
            'cons_account',
            'mr_bill_no',
            'mr_amount',
            'mr_printed')
        ->where('cons_account',$accountNumber)
        ->where('mr_printed',1)
        ->where('mr_status',0)
        ->get();
        if($data->isNotEmpty()){
            if($data->count() >= 2 || $data->count() == 6){
                for($i = 0;$i < count($data);$i++){
                    array_push($this->powerBillNumber,$data[$i]->mr_bill_no);
                }
                $this->totalAmount = round(collect($data->take($data->count()))->sum('mr_amount'),2);
            }elseif($data->count() >= 7){
                $this->billCount = 7;
            }else{
                array_push($this->powerBillNumber,$data[0]->mr_bill_no);
                $this->totalAmount = round(collect($data->take(1))->sum('mr_amount'),2);
            }
            $this->billCount = $data->count();
        }
        return json_encode([
            'data' => [
                'bill_numbers' => $this->powerBillNumber,
                'total_amount' => $this->totalAmount,
                'bill_counts' => $this->billCount
            ]
        ]);
    }
}