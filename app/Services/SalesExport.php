<?php

namespace App\Services;

use App\Models\Sales;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesExport implements FromQuery, WithMapping, WithHeadings, WithTitle {

    use Exportable;

    private $fromDate;
    private $toDate;
    private $userId;

    public function __construct($fromDate,$toDate,$userId){
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->userId = $userId;
    }

    public function query()
    {
        $consumers = Sales::query()->whereBetween('s_bill_date',[$this->fromDate,$this->toDate])
        ->where('teller_user_id',$this->userId);
        return $consumers;
    }
    public function headings(): array
    {
        return [
            'mr_id',
            'f_id',
            'ct_id',
            'cons_accountno',
            's_or_num',
            'cm_id',
            's_or_amount',
            'v_id',
            's_bill_no',
            's_bill_amount',
            's_bill_date',
            's_status',
            's_mode_payment',
            'cheque_id',
            's_ref_no',
            'ref_date',
            'teller_user_id',
            's_ack_receipt',
            'ackn_date',
            'ackn_user_id',
            'mr_arrear',
            'e_wallet_applied',
            'e_wallet_added',
            'ap_status',
            'server_added',
			's_cutoff',
			's_bill_date_time'
        ];
    }
    public function map($sales): array{
        return [
            Crypt::encryptString($sales->mr_id),
            Crypt::encryptString($sales->f_id),
            Crypt::encryptString($sales->ct_id),
            Crypt::encryptString($sales->cons_accountno),
            Crypt::encryptString($sales->s_or_num),
            Crypt::encryptString($sales->cm_id),
            Crypt::encryptString($sales->s_or_amount),
            Crypt::encryptString($sales->v_id),
            Crypt::encryptString($sales->s_bill_no),
            Crypt::encryptString($sales->s_bill_amount),
            Crypt::encryptString($sales->s_bill_date),
            Crypt::encryptString($sales->s_status),
            Crypt::encryptString($sales->s_mode_payment),
            Crypt::encryptString($sales->cheque_id),
            Crypt::encryptString($sales->s_ref_no),
            Crypt::encryptString($sales->ref_date),
            Crypt::encryptString($sales->teller_user_id),
            Crypt::encryptString($sales->s_ack_receipt),
            Crypt::encryptString($sales->ackn_date),
            Crypt::encryptString($sales->ackn_user_id),
            Crypt::encryptString($sales->mr_arrear),
            Crypt::encryptString($sales->e_wallet_applied),
            Crypt::encryptString($sales->e_wallet_added),
            Crypt::encryptString($sales->ap_status),
            Crypt::encryptString($sales->server_added),
			Crypt::encryptString($sales->s_cutoff),
			Crypt::encryptString($sales->s_bill_date_time)
        ];
    }
   /**
     * @return string
     */
    public function title(): string
    {
        return 'Sales';
    }
}