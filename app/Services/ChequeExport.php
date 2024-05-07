<?php

namespace App\Services;

use App\Models\Cheque;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ChequeExport implements FromQuery, WithMapping, WithHeadings, WithTitle {

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
        // $cheques = Cheque::query()->whereBetween('cheque_date',[$this->fromDate,$this->toDate])
        // ->where('teller_user_id',$this->userId);

        $cheques =  Cheque::query()->whereDate('cheque_date','>=',$this->fromDate)->whereDate('cheque_date','<=',$this->toDate);
        
        return $cheques;
    }
    public function headings(): array
    {
        return [
            'cheque_id',
            's_or',
            'cheque_no',
            'cheque_amount',
            'cheque_bank_acc',
            'cheque_acc_name',
            'cheque_bank',
            'cheque_bank_branch',
            'cheque_date',
            'cheque_status',
            'cheque_posted',
            'deleted_at',
            'created_at',
            'updated_at',
            'teller_user_id',
            'temp_cheque_id'
        ];
    }
    public function map($cheques): array{
        return [
            Crypt::encryptString($cheques->cheque_id),
            Crypt::encryptString($cheques->s_or),
            Crypt::encryptString($cheques->cheque_no),
            Crypt::encryptString($cheques->cheque_amount),
            Crypt::encryptString($cheques->cheque_bank_acc),
            Crypt::encryptString($cheques->cheque_acc_name),
            Crypt::encryptString($cheques->cheque_bank),
            Crypt::encryptString($cheques->cheque_bank_branch),
            Crypt::encryptString($cheques->cheque_date),
            Crypt::encryptString($cheques->cheque_status),
            Crypt::encryptString($cheques->cheque_posted),
            Crypt::encryptString($cheques->deleted_at),
            Crypt::encryptString($cheques->created_at),
            Crypt::encryptString($cheques->updated_at),
            Crypt::encryptString($cheques->teller_user_id),
            Crypt::encryptString($cheques->temp_cheque_id),
        ];
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Cheques';
    }
}