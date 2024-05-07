<?php

namespace App\Services;

use App\Models\Consumer;
use App\Models\Sales;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ConsumersExport implements FromQuery, WithMapping, WithHeadings, WithTitle {

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
        $consumers = Consumer::query()->whereDate('created_at','>=',$this->fromDate)->whereDate('created_at','<=',$this->toDate)
        ->where('teller_user_id',$this->userId);
        return $consumers;
    }
    public function headings(): array
    {
        return [
            'cm_id',
            'rc_id',
            'ct_id',
            'mm_id',
            'cm_account_no',
            'mm_master',
            'cm_seq_no',
            'deleted_at',
            'cm_first_name',
            'cm_middle_name',
            'cm_last_name',
            'cm_full_name',
            'cm_address',
            'cm_voting_address',
            'cm_birthdate',
            'cm_con_status',
            'cm_deceased',
            'cm_kwh_mult',
            'cm_lgu2',
            'cm_lgu5',
            'cm_discount_name',
            'cm_discount_percent',
            'cm_marital_status',
            'cm_image_url',
            'cm_date_createdat',
            'cm_remarks',
			'employee',
            'temp_connect',
            'senior_citizen',
            'institutional',
            'metered',
            'govt',
            'main_accnt',
            'large_load',
            'nearest_cons',
            'occupant',
            'board_res',
            'tin',
            'temp_area_code',
            'temp_town_code',
            'temp_route_code',
            'temp_cons_type',
            'created_at',
            'updated_at',
            'block_no',
            'purok_no',
            'lot_no',
            'extension_name',
            'special_account_type',
            'pending',
            'sitio',
            'teller_user_id',
            'temp_cons_id'
        ];
    }
    public function map($consumers): array{
        return [
            Crypt::encryptString($consumers->cm_id),
            Crypt::encryptString($consumers->rc_id),
            Crypt::encryptString($consumers->ct_id),
            Crypt::encryptString($consumers->mm_id),
            Crypt::encryptString($consumers->cm_account_no),
            Crypt::encryptString($consumers->mm_master),
            Crypt::encryptString($consumers->cm_seq_no),
            Crypt::encryptString($consumers->deleted_at),
            Crypt::encryptString($consumers->cm_first_name),
            Crypt::encryptString($consumers->cm_middle_name),
            Crypt::encryptString($consumers->cm_last_name),
            Crypt::encryptString($consumers->cm_full_name),
            Crypt::encryptString($consumers->cm_address),
            Crypt::encryptString($consumers->cm_voting_address),
            Crypt::encryptString($consumers->cm_birthdate),
            Crypt::encryptString($consumers->cm_con_status),
            Crypt::encryptString($consumers->cm_deceased),
            Crypt::encryptString($consumers->cm_kwh_mult),
            Crypt::encryptString($consumers->cm_lgu2),
            Crypt::encryptString($consumers->cm_lgu5),
            Crypt::encryptString($consumers->cm_discount_name),
            Crypt::encryptString($consumers->cm_discount_percent),
            Crypt::encryptString($consumers->cm_marital_status),
            Crypt::encryptString($consumers->cm_image_url),
            Crypt::encryptString($consumers->cm_date_createdat),
			Crypt::encryptString($consumers->cm_remarks),
            Crypt::encryptString($consumers->employee),
            Crypt::encryptString($consumers->temp_connect),
            Crypt::encryptString($consumers->senior_citizen),
            Crypt::encryptString($consumers->institutional),
            Crypt::encryptString($consumers->metered),
            Crypt::encryptString($consumers->govt),
            Crypt::encryptString($consumers->main_accnt),
            Crypt::encryptString($consumers->large_load),
            Crypt::encryptString($consumers->nearest_cons),
            Crypt::encryptString($consumers->occupant),
            Crypt::encryptString($consumers->board_res),
            Crypt::encryptString($consumers->tin),
            Crypt::encryptString($consumers->temp_area_code),
            Crypt::encryptString($consumers->temp_town_code),
            Crypt::encryptString($consumers->temp_route_code),
            Crypt::encryptString($consumers->temp_cons_type),
            Crypt::encryptString($consumers->created_at),
            Crypt::encryptString($consumers->updated_at),
            Crypt::encryptString($consumers->block_no),
            Crypt::encryptString($consumers->purok_no),
            Crypt::encryptString($consumers->lot_no),
            Crypt::encryptString($consumers->extension_name),
            Crypt::encryptString($consumers->special_account_type),
            Crypt::encryptString($consumers->pending),
            Crypt::encryptString($consumers->sitio),
            Crypt::encryptString($consumers->teller_user_id),
            Crypt::encryptString($consumers->temp_cons_id),
        ];
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Consumers';
    }
}