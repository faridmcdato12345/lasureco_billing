<?php

namespace App\Http\Controllers\Api;


use App\Models\Consumer;
use App\Models\RouteCode;
use App\Models\MeterMaster;
use App\Models\ConsumerType;
use Illuminate\Http\Request;
use App\Services\ConsumerModifyService;
use Illuminate\Support\Facades\DB;
use App\Services\AuditTrailService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ConsumerResource;
use App\Http\Requests\StoreConsumerRequest;
use App\Http\Requests\OnlineConsumerRequest;
use App\Models\EWALLET;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class ConsumerController extends Controller
{

    public $userId;
    public function index()
    {
        return ConsumerResource::collection(
            DB::table('cons_master')
            ->whereNull('deleted_at')
            ->paginate(10));
    }
    public function show($id)
    {
        return ConsumerResource::collection(
            DB::table('cons_master')
            ->where('cm_id', $id)
            ->whereNull('deleted_at')
            ->get());
    }
    public function store(StoreConsumerRequest $request)
    {
        //$user = Auth::user();
        // if($user->hasRole('teller')){
        //     $request->merge([
        //         'pending' => 1,
        //     ]);
        // }
        $data = Consumer::create($request->all());
        if($data){
        	Consumer::where('cm_id',$data->cm_id)->update(['temp_cons_id'=>$data->cm_id]);
            $ewallet = new EWALLET();
            $ewallet->cm_id = $data->cm_id;
            $ewallet->ew_total_amount = 0;
            $ewallet->save();
            if($ewallet){
                return response($data,201);
            }
        }
        
    }
    public function getConsCountMeterRead($request)
    {
        $data = DB::table('cons_master AS cm')
            ->join('route_code AS rc', 'cm.rc_id', '=', 'rc.rc_id')
            ->select('rc.rc_id','rc.rc_desc','cm.cm_con_status','cm.pending', DB::raw('count(*) AS count'))
            ->groupBy('rc.rc_id')
            ->where('rc.rc_id',$request)
            // ->where('cm.cm_con_status','!=',3)
            // ->where('cm.pending','!=',1)
            ->first();
        if(!$data)
        {
            return response(['Message'=>'No Consumer Found'],422);
        }
        $meterReader = DB::table('emp_master')
            ->select('em_id','gas_fnamesname')
            ->where('ebs_designation','meterreader')
            ->get();
        $check = $meterReader->first();
        if(!$check)
        {
            return response(['Message'=>'No Meter Reader Found'],422);
        }

        return response(['data'=>$data,'meter_reader'=>$meterReader],200);
    }
    
    public function MRStoPDF(Request $req){
        $temp = str_replace("-","",$req->date);
        $checkBox = $req->checkBox;
        $checkConsumer = DB::table('cons_master')
            ->select('cm_id')
            ->where('rc_id',$req->routeID)
            ->whereNull('deleted_at')
            ->first();
        if(!$checkConsumer)
        {
            return response (['Message'=> 'No Consumer to display'], 404);
        }

        $data = DB::table('cons_master as cm')
            ->join('cons_type as ct', 'cm.ct_id', '=', 'ct.ct_id')
            ->leftJoin('meter_master as mm', 'cm.mm_id', '=', 'mm.mm_id')
            ->select('cm.cm_id','cm.cm_con_status','cm.cm_seq_no','cm.cm_account_no','ct.ct_code','cm.cm_full_name',
            'mm.mm_serial_no','cm.cm_kwh_mult')
            ->where('cm.rc_id',$req->routeID)
            ->whereNull('cm.deleted_at')
            // ->where('cm.cm_con_status','!=',3)
            // ->where('cm.pending',0)
            ->orderBy('cm.cm_seq_no','asc')
            ->get();
        $mappedData = $data->map(function($item)use($temp){
            $presPrevQuery = 
                DB::table('meter_reg')
                ->select('mr_pres_reading','mr_prev_reading','cm_id')
                ->where('cm_id',$item->cm_id)
                ->where('mr_date_year_month','<',$temp)
                ->orderBy('mr_date_year_month','desc')
                ->first();
                if(!$presPrevQuery){
                    $pres = 0;
                    $prev = 0;
                }else{
                    $pres = $presPrevQuery->mr_pres_reading;
                    $prev = $presPrevQuery->mr_prev_reading;
                }
            return[
                'cm_con_status'=>($item->cm_con_status == 1)?'Active':'Deactivated',
                'cm_seq_no'=>$item->cm_seq_no,
                'cm_account_no'=>$item->cm_account_no,
                'ct_code'=>$item->ct_code,
                'cm_full_name'=>$item->cm_full_name,
                'mr_pres_reading'=>$pres,
                'mr_prev_reading'=>$prev,
                'cm_kwh_mult'=>$item->cm_kwh_mult,
                'mm_serial_no'=>$item->mm_serial_no 
            ];
        });

        $route = RouteCode::with('townCode.areaCode')->where('rc_id',$req->routeID)->get();
        $newDate = date("F Y", strtotime($req->date));
        // $pdf = PDF::loadView('Layout.mrs_pdf_layout', $data, $route, $newDate);
        // return $pdf->stream('mrsPrint.pdf');
        return response (['route'=>$route,'check'=>$checkBox, 'date'=>$newDate, 'data'=>$mappedData->values()->all(),'Count'=>$mappedData->count('cm_account_no')], 200);
        // return view('Layout.mrs_pdf_layout', $data);
    }
    public function showListSOA($rcid,$billingPeriod,$Mreader)
    {
        $date = date("F Y", strtotime($billingPeriod));
        $consumers = DB::table('cons_master')
            ->select('cm_id')
            ->where('rc_id',$rcid)
            ->whereNull('deleted_at')
            ->first();
        if(!$consumers)
        {
            return response (['Message'=> 'No Consumer to display'], 404);
        }
        $consumers = DB::table('cons_master AS cm')
            ->join('route_code AS rc','rc.rc_id','=','cm.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->select('cm.cm_full_name','cm.cm_con_status','cm.cm_account_no','ac.ac_id','tc.tc_code','rc.rc_code',
                'rc.rc_desc','tc.tc_name','ac.ac_name')
            ->where('rc.rc_id',$rcid)
            ->orderBy('cm.cm_account_no','asc')
            ->get();


        return response (['Meter_Reader'=>$Mreader,'Date'=>$date,'Consumers'=>$consumers], 200);
    }
    public function consAcknRcptSOA($rcid,$billingPeriod,$mReader)
    {
        $date = date("F Y", strtotime($billingPeriod));
        $consumers = DB::table('cons_master')
            ->select('cm_id')
            ->where('rc_id',$rcid)
            ->whereNull('deleted_at')
            ->first();
        if(!$consumers)
        {
            return response (['Message'=> 'No Consumer to display'], 404);
        }
        $consumers = DB::table('cons_master AS cm')
            ->join('route_code AS rc','rc.rc_id','=','cm.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->join('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            ->select('cm.cm_full_name','cm.cm_con_status','cm.cm_account_no','tc.tc_code','rc.rc_code',
                'rc.rc_desc','tc.tc_name','mm.mm_serial_no','ct.ct_code','cm.cm_seq_no')
            ->where('rc.rc_id',$rcid)
            ->orderBy('cm.cm_account_no','asc')
            ->get();

        return response (['Meter_Readers'=>$mReader,'Date'=>$date,'Consumers'=>$consumers], 200);
    }
    public function selectConsAcc()
    {
        return DB::table('cons_master AS cm')
            ->join('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            ->select('cm.cm_id','cm.cm_full_name','cm.cm_account_no','cm.cm_address','cm.cm_seq_no')
            ->whereNull('cm.deleted_at')
            ->orderBy('cm_full_name','asc')
            ->paginate(5);
    }
    public function showConsTypeAddress($id)
    {
        return DB::table('cons_master AS cm')
            ->join('cons_type AS ct','ct.ct_id','=','cm.ct_id')
            ->join('route_code AS rc','rc.rc_id','=','cm.rc_id')
            ->join('town_code AS tc','tc.tc_id','=','rc.tc_id')
            ->join('area_code AS ac','ac.ac_id','=','tc.ac_id')
            ->select('ct.ct_code','ct.ct_desc','ac.ac_id','ac.ac_name','tc.tc_code','tc.tc_name',
                'rc.rc_code','rc.rc_desc','cm.cm_con_status')
            ->where('cm.cm_id',$id)
            ->whereNull('cm.deleted_at')
            ->get();
    }
    public function searchConsByName($request)
    {
        
        if(is_numeric($request)){
            
            // return DB::table('cons_master')
            // ->select('cm_id','cm_account_no','cm_full_name','cm_address')
            // ->where('cm_account_no','LIKE', '%'.$request.'%')
            // ->whereNull('deleted_at')
            // ->get();
            if(is_numeric($request)){
                return DB::table('cons_master AS cm')
                ->join('cons_type as ct','cm.ct_id','ct.ct_id')
                ->leftjoin('meter_master AS mm','mm.mm_id','=','cm.mm_id')
                ->select('cm.cm_id','cm.cm_account_no','cm.cm_full_name','cm.cm_address','mm.mm_serial_no','cm.cm_seq_no','cm.cm_con_status','ct.ct_desc')
                ->whereNull('cm.deleted_at')
                ->where('cm.cm_account_no','LIKE', '%'.$request.'%')
                ->orWhere('mm.mm_serial_no','LIKE', '%'.$request.'%')
                ->orWhere('cm.mm_master','LIKE', '%'.$request.'%')
                
                ->get();
            }
        }
            return DB::table('cons_master AS cm')
            ->leftjoin('meter_master AS mm','mm.mm_id','=','cm.mm_id')
            ->select('cm.cm_id','cm.cm_account_no','cm.cm_full_name','cm.cm_address','mm.mm_serial_no','cm.cm_seq_no','cm.cm_con_status')
            ->whereNull('cm.deleted_at')
            ->where('cm_full_name','LIKE', '%'.$request.'%')
            ->orWhere('cm.cm_account_no','LIKE', '%'.$request.'%')
            ->orWhere('mm.mm_serial_no','LIKE', '%'.$request.'%')
            ->orWhere('cm.mm_master','LIKE', '%'.$request.'%')
            ->get();
            // return DB::table('cons_master')
            // ->select('cm_id','cm_full_name','cm_account_no','cm_address')
            // ->where('cm_full_name','LIKE', '%'.$request.'%')
            // ->orWhere('mm.mm_serial_no','LIKE', '%'.$request.'%')
            // ->orWhere('cm.mm_master','LIKE', '%'.$request.'%')
            // ->whereNull('deleted_at')
            // ->get();
    }
    
    // public function datatableConsumer($id=null){
    //     $this->userId = $id;
    //     $query = Consumer::select('cm_id','ct_id','cm_account_no','cm_full_name','cm_address','cm_con_status')->where('pending',0);
    //     return datatables($query)
    //         ->addIndexColumn()
    //         ->addColumn('action', function($row){
    //             $typeName = ConsumerType::where('ct_id',$row->ct_id)->first();
    //             $btn = '<button class="modify btn btn-info btn-sm action-bttn" value="'.$row->cm_id.'" id="role-view" data-toggle="modal" data-target="#addRole">Modify</button>';
    //             $btn = $btn.'<button class="add-meter btn btn-danger btn-sm action-bttn" value="'.$row->cm_id.'">Add Meter</button>';
    //             $btn = $btn.'<button class="change-name btn btn-warning btn-sm action-bttn" value="'.$row->cm_id.'" id="'.$row->cm_full_name.'" data-toggle="modal" data-target="#addRole">Change name</button>';
    //             $btn = $btn.'<button class="change-type btn btn-primary btn-sm action-bttn" value="'.$row->cm_id.'" id="'.$typeName->ct_desc.'" data-toggle="modal" data-target="#addRole">Change type</button>';
    //             $btn = $btn.'<button class="change-meter btn btn-success btn-sm action-bttn" value="'.$row->cm_id.'">Change Meter</button>';
    //             $btn = $btn.'<button class="change-multiplier btn btn-info btn-sm action-bttn" value="'.$row->cm_id.'">Change Multiplier</button>';
    //             $btn = $btn.'<button onclick="remarks('.$row->cm_id.')" class="btn btn-primary btn-sm action-bttn" value="'.$row->cm_id.'">Remarks</button>';
    //             return $btn;
    //         })
    //         ->addColumn('status', function($row){
    //             $status = $row->cm_con_status;
    //             if(!$status){
    //                 $btn = '<div class="custom-control custom-switch">';
    //                 $btn = $btn . '<input type="checkbox" class="custom-control-input ad_switch" id="'.$row->cm_id.'">';
    //                 $btn = $btn . '<label class="custom-control-label status_label" for="'.$row->cm_id.'">Inactive</label>';
    //                 $btn = $btn . '<input type="hidden" id="status_hidden" value="0"></div>';
    //             }
    //             else{
    //                 $btn = '<div class="custom-control custom-switch">';
    //                 $btn = $btn . '<input type="checkbox" class="custom-control-input ad_switch" id="'.$row->cm_id.'" checked="">';
    //                 $btn = $btn . '<label class="custom-control-label status_label" for="'.$row->cm_id.'">Active</label>';
    //                 $btn = $btn . '<input type="hidden" id="status_hidden" value="1"></div>';
    //             }
    //             return $btn;
    //         })
    //         ->rawColumns(['action','status'])
    //         ->make(true);
    // }
    public function datatableConsumer($id=null){
        $this->userId = $id;
        $query = Consumer::select('cm_id','ct_id','cm_account_no','cm_full_name','cm_address','cm_con_status')->where('pending',0)->whereNotNull('cm_account_no');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $typeName = ConsumerType::where('ct_id',$row->ct_id)->first();
                $btn = '<button class="modify btn btn-info btn-sm action-bttn" value="'.$row->cm_id.'" id="role-view" data-toggle="modal" data-target="#addRole">Modify</button>';
                $btn = $btn.'<button class="add-meter btn btn-danger btn-sm action-bttn" value="'.$row->cm_id.'">Add Meter</button>';
                $btn = $btn.'<button class="change-name btn btn-warning btn-sm action-bttn" value="'.$row->cm_id.'" id="'.$row->cm_full_name.'" data-toggle="modal" data-target="#addRole">Change name</button>';
                $btn = $btn.'<button class="change-type btn btn-primary btn-sm action-bttn" value="'.$row->cm_id.'" id="'.$typeName->ct_desc.'" data-toggle="modal" data-target="#addRole">Change type</button>';
                $btn = $btn.'<button class="change-meter btn btn-success btn-sm action-bttn" value="'.$row->cm_id.'">Change Meter</button>';
                $btn = $btn.'<button class="history-log btn btn-secondary btn-sm action-bttn" value="'.$row->cm_id.'"> History Log </button>';
                $btn = $btn.'<button onclick="remarks('.$row->cm_id.')" class="btn btn-primary btn-sm action-bttn" value="'.$row->cm_id.'">Remarks</button>';
                return $btn;
            })
            ->addColumn('status', function($row){
                $status = $row->cm_con_status;
                    $btn = '<div class="custom-control custom-switch">';
                    $btn = '<input type="hidden" id="'.$row->cm_id.'a'.'" value = "'.$status.'" hidden>';
                    $btn = $btn . '<select onchange="status123(this)" id="'.$row->cm_id.'">';
                    if($status == 0 || $status == ''){
                    $btn = $btn .  '<option value="1">Active</option> 
                                    <option value="3">Inoperative</option>
                                    <option value="0" selected>Disconnected</option>';
                    }
                    if($status == 1){
                        $btn = $btn .  '<option value="1" selected>Active</option> 
                                        <option value="3">Inoperative</option>
                                        <option value="0">Disconnected</option>';
                        }
                    if($status == 3){
                            $btn = $btn .  '<option value="1">Active</option> 
                                            <option value="3" selected>Inoperative</option>
                                            <option value="0">Disconnected</option>';
                            }
                    $btn = $btn . '</select>';
                    $btn = $btn . '</div>';
                return $btn;
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }
    public function mainAccount(){
        $query = Consumer::select('cm_id','cm_account_no','cm_full_name','cm_address');    
        return datatables($query)
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-primary btn-sm mAselect" value="'.$row->cm_id.'">Select</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function select(Consumer $consumer){
        return response()->json($consumer,200);
    }
    public function updateStatus(Consumer $consumer, Request $request){
        //get Consumer Old Status
        $oldStatus = $consumer['cm_con_status'];

        $consumer['cm_con_status'] = $request->status;
        if($request->status == 1){
            $consumer->cm_date_createdat = Carbon::now();
        }
        if($consumer['pending'] == 1){
            $consumer['pending'] = 0;
        }
        $__conSave = $consumer->save();
        if($__conSave){
            DB::table('cons_status_mod')->insert([
                'cm_id' => $consumer->cm_id,
                'csm_old' => $oldStatus,
                'csm_new' => $consumer->cm_con_status,
                'csm_remarks' => $request->remarks,
                'csm_date' => Carbon::now()->toDateTimeString(),
            ]);
        }
        
        return response()->json($consumer,200);
    }
    public function uploadeImage(Request $request){
        $consumer = DB::table('cons_master')->orderByRaw('cm_id DESC')->first();
        $file = $request->file('file');
        if($file != ''){
            $picture = time().'_'.$file->getClientOriginalName();
            $request->file->move('storage/consumer/profile/', $picture);
            DB::table('cons_master')->where('cm_id', $consumer->cm_id)->update(['cm_image_url' => $picture]);
            return response()->json($consumer,200);
        }
    }
    public function meterChange(MeterMaster $meterMaster, Request $request){
        //For Audit Trail
        // $at_old_value = $consumer['mm_id'];
        // $at_new_value = $meterMaster->mm_id;
        // $at_action = 'Update';
        // $at_table = 'meter_master';
        // $at_auditable = 'Meter';
        // $user_id = '';
        // $id = $consumer->cm_id;
        // $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);
        $mrDate = str_replace("-","",$request->mr_date);
        $consumer = Consumer::select('cm_id','mm_id','cm_account_no','cm_kwh_mult')->where('cm_id',$request->cons_id)->first();
        $kwhMult = $consumer->cm_kwh_mult;
        if($consumer->cm_kwh_mult == 0){
            $check = MeterMaster::where('mm_id',$consumer->mm_id)->first();
            $kwhMult = $check->mm_kwh_multiplier;
        }
        $addEnergy = (float)$request->add_energy * (float)$kwhMult;
        $meter_reg = DB::table('meter_reg')->insert([
            'cm_id' => $consumer->cm_id,
            'cons_account' => $consumer->cm_account_no,
            'mr_add_energy' => $addEnergy,
            'mr_prev_reading' => $request->prev_reading,
            'mr_date_year_month' => $mrDate
        ]);
        $consModIns = DB::table('cons_meter_mod')->insert([
            'cm_id' => $consumer->cm_id,
            'cmm_old_meter_serial' => $consumer->mm_id,
            'cmm_new_meter_serial' => $meterMaster->mm_id,
            'cmm_remarks' => $request->remarks,
            'cmm_om_final_read' => $request->add_energy,
            'cmm_date' => Carbon::now()->toDateTimeString(),
        ]);
        $consumer['mm_id'] = $meterMaster->mm_id;
        $consumer->save();
        return response()->json($consModIns,201);
    }
    public function changeName(Consumer $consumer,Request $request){

        //For Audit Trail
        // $at_old_value = $consumer->cm_full_name;
        // $at_new_value = $request->first_name .' '. $request->middle_name .' '. $request->last_name;
        // $at_action = 'Update';
        // $at_table = 'cons_master';
        // $at_auditable = 'Name';
        // $user_id = '';
        // $id = $consumer->cm_id;
        // $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);
        
        $consumer->cm_first_name = $request->first_name;
        $consumer->cm_middle_name = $request->middle_name;
        $consumer->cm_last_name = $request->last_name;
        $consumer->cm_full_name = $request->first_name .' '. $request->middle_name .' '. $request->last_name;
        $consumer->save();

        return response()->json($consumer,200);
    }
    public function changeType(Consumer $consumer, Request $request){
        //For Audit Trail
        // $at_old_value = $consumer->ct_id;
        // $at_new_value = $request->ct_id;
        // $at_action = 'Update';
        // $at_table = 'cons_type';
        // $at_auditable = 'consumer type';
        // $user_id = '';
        // $id = $consumer->cm_id;
        // $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);
        // Get old Constype CT Code
        $ctCodeOld = DB::table('cons_type')
        ->select('ct_code')
        ->where('ct_id',$consumer->ct_id)
        ->first();
        // Get new Constype CT Code
        $ctCodeNew = DB::table('cons_type')
            ->select('ct_code')
            ->where('ct_id',$request->ct_id)
            ->first();

        $consumer->ct_id = $request->ct_id;
        $consumer->save();
        if($consumer){
            // Add data to Cons Type Mod table
            $current_date_time = Carbon::now()->toDateTimeString();
            DB::table('cons_type_mod')
            ->insert([
                'cm_id'=>$consumer->cm_id,
                'ctm_old'=>$ctCodeOld->ct_code,
                'ctm_new'=>$ctCodeNew->ct_code,
                'ctm_date'=>$current_date_time,
                'ctm_remarks'=>$request->remarks,
            ]);
        }
        return response()->json($consumer,200);
    }
    public function getActiveConsumers(){
        $consumersActive = number_format(Consumer::where('cm_con_status',1)->count());
        return response()->json($consumersActive,200);
    }
    public function getInActiveConsumers(){
        $consumersInActive = number_format(Consumer::whereNull('cm_con_status')->orWhere('cm_con_status',0)->count());
        return response()->json($consumersInActive,200);
    }
    public function getRouteAccounts($id){
        $routeAccounts = collect(
            DB::table('cons_master as cm')
            ->where('rc_id',$id)
            ->whereNull('deleted_at')
            ->get()
        );

        if($routeAccounts->isEmpty()){
            return response(['Message'=>'No Records'],422);
        }

        $mapped = $routeAccounts->map(function($item){
            return[
                'Account_No'=>$item->cm_account_no,
                'Consumer_Name'=>$item->cm_full_name,
            ];
        })->sortBy('Account_No');

        return response(['Account_Details'=>$mapped],200);
    }
    public function modify(Consumer $consumer,Request $request){
        // MODS LOG STARTS HERE
        if($request->cm_first_name.''.$request->cm_middle_name.''.$request->cm_last_name != $consumer->cm_first_name.''.$consumer->cm_middle_name.''.$consumer->cm_last_name){
            $type = 'name';
            // if($request->cm_first_name == ''){
            //     $request->cm_first_name 
            // }
            // if($request->cm_middle_name == ''){

            // }
            // if($request->cm_last_name == ''){

            // }
            $data = (new ConsumerModifyService($type,$consumer->cm_id,$consumer->cm_first_name.' '.$consumer->cm_middle_name.' '.$consumer->cm_last_name,$request->cm_first_name.' '.$request->cm_middle_name.' '.$request->cm_last_name,$request->modify_by))->modify();
        }
        if($consumer->cm_address != $request->cm_address){
            $type = 'address';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->cm_address == NULL) ? 0 :$consumer->cm_address,$request->cm_address,$request->modify_by))->modify();
            // dd(1);
        }
        if($consumer->cm_birthdate != $request->cm_birthdate && $request->cm_birthdate != 0){
            $type = 'birth date';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->cm_birthdate == NULL) ?'No Info' :$consumer->cm_birthdate,($request->cm_birthdate == NULL) ? '0' :$request->cm_birthdate ,$request->modify_by))->modify();
        }
        if($consumer->employee != $request->employee){
            $type = 'employee';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->employee == NULL) ? 0 : 1,($request->employee == 1) ? 1 :  0,$request->modify_by))->modify();
        }
        if($consumer->senior_citizen != $request->senior_citizen){
            $type = 'senior citizen';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,$consumer->senior_citizen,$request->senior_citizen,$request->modify_by))->modify();
        }
        if($consumer->metered != $request->metered){
            $type = 'meter';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,$consumer->metered,$request->metered,$request->modify_by))->modify();
        }
        if($consumer->large_load != $request->large_load){
            $type = 'large load';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,$consumer->large_load,$request->large_load,$request->modify_by))->modify();
        }
        if($consumer->mm_id != $request->mm_id){
            $type = 'meter id';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->mm_id == NULL) ? 'No Info' : $consumer->mm_id ,($request->mm_id == NULL || $request->mm_id == 0) ? '0' : $request->mm_id ,$request->modify_by))->modify();
        }
        if($consumer->cm_account_no != $request->cm_account_no){
            $type = 'account no';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,$consumer->cm_account_no,$request->cm_account_no,$request->modify_by))->modify();
        }
        if($consumer->cm_seq_no != $request->cm_seq_no){
            $type = 'sequence no';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->cm_seq_no == NULL) ? '0' : $consumer->cm_seq_no,($request->cm_seq_no == NULL) ? '0' : $request->cm_seq_no,$request->modify_by))->modify();
        }
        if($consumer->cm_lgu5 != $request->cm_lgu5){
            $type = 'lgu 5';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->cm_lgu5 == NULL) ? 0 : $consumer->cm_lgu5,($request->cm_lgu5 == NULL) ? 0 : $request->cm_lgu5,$request->modify_by))->modify();
        }
        if($consumer->cm_lgu2 != $request->cm_lgu2){
            $type = 'lgu 2';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->cm_lgu2 == NULL) ? 0 : $consumer->cm_lgu2,($request->cm_lgu2 == NULL) ? 0 : $request->cm_lgu2,$request->modify_by))->modify();
        }
        if($consumer->rc_id != $request->rc_id){
            $type = 'route id';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->rc_id == NULL) ? 'No Route' :$consumer->rc_id ,$request->rc_id,$request->modify_by))->modify();
        }
        if($consumer->ct_id != $request->ct_id){
            $type = 'constype id';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,$consumer->ct_id,$request->ct_id,$request->modify_by))->modify();
        }
        if($consumer->tin != $request->tin){
            $type = 'tin';
            $data = (new ConsumerModifyService($type,$consumer->cm_id,($consumer->tin == Null) ? '0' :$consumer->tin,($request->tin == Null) ? '0' : $request->tin,$request->modify_by))->modify();
        }

        // MODS LOG ENDS HERE
        $consumer->rc_id = $request->rc_id;
        $consumer->ct_id = $request->ct_id;
        $consumer->tin = $request->tin;
        $consumer->cm_last_name = $request->cm_last_name;
        $consumer->cm_first_name = $request->cm_first_name;
        $consumer->cm_middle_name = $request->cm_middle_name;
        $consumer->cm_full_name = $request->cm_full_name;
        $consumer->cm_address = $request->cm_address;
        $consumer->cm_birthdate = $consumer->cm_birthdate;
        $consumer->employee = $request->employee;
        $consumer->temp_connect =  $request->temp_connect; // no need Log
        $consumer->senior_citizen = $request->senior_citizen; 
        $consumer->institutional = $request->institutional; // no need log
        $consumer->metered = $request->metered;
        $consumer->govt = $request->govt; // no need log
        $consumer->main_accnt = $request->main_accnt;  //no need log
        $consumer->large_load = $request->large_load;
        $consumer->nearest_cons = $request->nearest_cons; // no need log
        $consumer->occupant = $request->occupant; // no need log
        $consumer->board_res = $request->board_res; // no need log
        $consumer->mm_id = $request->mm_id; 
        $consumer->cm_account_no = $request->cm_account_no; 
        $consumer->main_accnt = $request->main_accnt; // no need log
        $consumer->cm_seq_no = $request->cm_seq_no; 
        $consumer->cm_lgu5 = $request->cm_lgu5;
        $consumer->cm_lgu2 = $request->cm_lgu2;
        $consumer->extension_name = $request->extension_name; // no need log
        $consumer->block_no = $request->block_no; // no need log
        $consumer->purok_no = $request->purok_no; // no need log
        $consumer->lot_no = $request->lot_no; // no need log
        $consumer->sitio = $request->sitio; // no need log
        $consumer->mm_master = $request->mm_master; // no need log
        $consumer->modify_by = $request->modify_by; // no need log
        $consumer->save();
        if($consumer){
            return response()->json($consumer,200);
        }
        // $consumer->rc_id = $request->rc_id;
        // $consumer->ct_id = $request->ct_id;
        // $consumer->tin = $request->tin;
        // $consumer->cm_last_name = $request->cm_last_name;
        // $consumer->cm_first_name = $request->cm_first_name;
        // $consumer->cm_middle_name = $request->cm_middle_name;
        // $consumer->cm_full_name = $request->cm_full_name;
        // $consumer->cm_address = $request->cm_address;
        // $consumer->cm_birthdate = $consumer->cm_birthdate;
        // $consumer->employee = $request->employee;
        // $consumer->temp_connect =  $request->temp_connect;
        // $consumer->senior_citizen = $request->senior_citizen;
        // $consumer->institutional = $request->institutional;
        // $consumer->metered = $request->metered;
        // $consumer->govt = $request->govt;
        // $consumer->main_accnt = $request->main_accnt;
        // $consumer->large_load = $request->large_load;
        // $consumer->nearest_cons = $request->nearest_cons;
        // $consumer->occupant = $request->occupant;
        // $consumer->board_res = $request->board_res;
        // $consumer->mm_id = $request->mm_id;
        // $consumer->cm_account_no = $request->cm_account_no;
        // $consumer->main_accnt = $request->main_accnt;
        // $consumer->cm_seq_no = $request->cm_seq_no;
        // $consumer->cm_lgu5 = $request->cm_lgu5;
        // $consumer->cm_lgu2 = $request->cm_lgu2;
        // $consumer->extension_name = $request->extension_name;
        // $consumer->block_no = $request->block_no;
        // $consumer->purok_no = $request->purok_no;
        // $consumer->lot_no = $request->lot_no;
        // $consumer->sitio = $request->sitio;
        // $consumer->mm_master = $request->mm_master;
        // $consumer->modify_by = $request->modify_by;
        // $consumer->save();
        // if($consumer){
        //     return response()->json($consumer,200);
        // }
    }
    public function getConsumerNames(Request $request){
        $consumer = Consumer::where('cm_full_name',$request->cm_full_name)->first();
        if(is_null($consumer)){
            return response()->json(['message'=>'Not a LASURECO consumer!'],404);
        }
        return response()->json($consumer,200);
    }
    public function getConsumerAccountNo(Request $request){
        $consumer = Consumer::where('cm_account_no',$request->cm_account_no)->first();
        if(is_null($consumer)){
            return response()->json(['message'=>'Not a LASURECO consumer!'],404);
        }
        return response()->json($consumer,200);
    }
    public function checkConsumerInputValidation(Request $request){
        $consumer = Consumer::where('cm_full_name',$request->cm_full_name)->where('cm_account_no',$request->cm_account_no)->first();
        if(is_null($consumer)){
            return response()->json(['message'=>'Error'],404);
        }
        return response()->json($consumer,200);
    }
    public function addConsumerMeter(Consumer $consumer, Request $request){
        $meterDesc = DB::table('meter_master')->select('mm_id','mm_serial_no')
        ->where('mm_id',$request->meter)
        ->first();
        $cons = Consumer::where('cm_id',$consumer->cm_id)
        ->update([
            'mm_id' => $meterDesc->mm_id,
            'mm_master' => $meterDesc->mm_serial_no
        ]);
        return response()->json($cons,200);
    }
    public function consNotify(Request $request)
    {
        $consModIns = DB::table('cons_notify')->insert([
            'cm_id' => $request->cons_id,
            'cn_remarks' => $request->remarks,
            'cn_date' => Carbon::now()->toDateTimeString(),
        ]);

        return response(['Message'=> 'Succesfully Added Consumer Remarks'],200);
    }
    public function removeNotified($id){
        $del = DB::table('cons_notify')
        ->where('cn_id',$id)
        ->delete();

        if($del){
            return response(['Message'=>'Successfuly Deleted'],200);
        }else{
            return response(['Message'=>'Something Went Wrong!'],422);
        }

    }
    public function getConsumerMult($id){
        $consumer = Consumer::select('cm_id','cm_kwh_mult')->where('cm_id',$id)->get();
        return response()->json($consumer,200);
    }

    public function changeConsMult(Request $request,Consumer $consumer){
        try {
            $consumer->update([
                'cm_kwh_mult' => $request->kwh_mult
            ]);
            return response()->json([
                'message' => 'Consumer multiplier has updated.'
            ],Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
        
    }
    public function consUpdateStatus(Request $request){
        //get Consumer Old Status
        $oldStatus = $request->old_status;
        //  Disco = 0,Active = 1, Inoperative = 3
        $data = Consumer::find($request->id);
        if(!isset($request->new_status)){
            return response(['info'=>'Error! Missing Request Status'],422);
        }else if($request->new_status == 2 || $request->new_status > 3 || $request->new_status < 0){
            return response(['info'=>'Invalid Status'],422);
        }

        if(!isset($request->remarks) || $request->remarks == NULL){
            return response(['info'=>'Error! Missing Request Remarks'],422);
        }
        
        $data->cm_con_status = $request->new_status;
        $data->updated_at = Carbon::now()->toDateTimeString();
        if($data->save()){
            DB::table('cons_status_mod')->insert([
                'cm_id' => $request->id,
                'csm_old' => $oldStatus,
                'csm_new' => $request->new_status,
                'csm_remarks' => $request->remarks,
                'csm_date' => Carbon::now()->toDateTimeString(),
            ]);
        }
        
        return response(['info'=>'Succesfully Updated'],200);
    }
    public function consHistoryLog($cmId){
        $conOtherMod = collect(DB::table('cons_other_mod')
        ->where('cm_id', $cmId)
        ->get());

        $status = collect(
            DB::table('cons_status_mod')
            ->where('cm_id',$cmId)
            ->get()
        );
        $statusMap = $status->map(function($item){
            if(is_null($item->csm_old)){
                $item->csm_old = 'Pending';
            }else if($item->csm_old == 0){
                $item->csm_old = 'Disconnected';
            }else if($item->csm_old == 3){
                $item->csm_old = 'Inoperative';
            }else{
                $item->csm_old = 'Active';
            }
            if(is_null($item->csm_new)){
                $item->csm_new = 'Pending';
            }else if($item->csm_new == 0){
                $item->csm_new = 'Disconnected';
            }else if($item->csm_new == 3){
                $item->csm_new = 'Inoperative';
            }else{
                $item->csm_new = 'Active';
            }

            $getDate = Carbon::parse($item->csm_date);
            $date = $getDate->format('Y m d');
            return[
                'com_type'=> 'Status',
                'com_old_info'=>$item->csm_old,
                'com_new_info'=>$item->csm_new,
                'com_date'=>$date
            ];
        });

        

        $map = $conOtherMod->map(function($item) use($cmId){
            //check if cons have status modification
            
            if($item->com_type == "meter"){
                $item->com_type = "Metered";
                $item->com_old_info = ($item->com_old_info == 1) ? 'Yes' : 'No';
                $item->com_new_info = ($item->com_new_info == 1) ? 'Yes' : 'No';
            }

            if($item->com_type == "route id"){
                $item->com_type = "Route";

                if($item->com_old_info == 'No Route'){
                    $oldRoute = 'No Route';
                }else{
                    $oldRoute = collect(DB::table('route_code')
                    ->select('rc_desc')
                    ->where('rc_id',$item->com_old_info)
                    ->first());
                }
                

                $item->com_old_info = ($item->com_old_info == 'No Route') ? $oldRoute : $oldRoute['rc_desc'];

                $newRoute = collect(DB::table('route_code')
                ->select('rc_desc')
                ->where('rc_id',$item->com_new_info)
                ->first());

                $item->com_new_info = $newRoute['rc_desc'];
            }

            if($item->com_type == "meter id"){
                $item->com_type = "Meter Serial No.";
                
                if($item->com_old_info != "No Info") {
                    $oldMeter = collect(DB::table('meter_master')
                    ->select('mm_serial_no')
                    ->where('mm_id',$item->com_old_info)
                    ->first());

                    $item->com_old_info = $oldMeter['mm_serial_no'];
                }
                

                if($item->com_new_info != "No Info") {
                    $newMeter = collect(DB::table('meter_master')
                    ->select('mm_serial_no')
                    ->where('mm_id',$item->com_new_info)
                    ->first());

                    $item->com_new_info = $newMeter['mm_serial_no'];
                }
            }

            if($item->com_type == "constype id"){
                $item->com_type = "Consumer Type";

                $oldConstype = collect(DB::table('cons_type')
                ->select('ct_desc')
                ->where('ct_id',$item->com_old_info)
                ->first());

                $item->com_old_info = $oldConstype['ct_desc'];

                $newConstype = collect(DB::table('cons_type')
                ->select('ct_desc')
                ->where('ct_id',$item->com_new_info)
                ->first());

                $item->com_new_info = $newConstype['ct_desc'];
            }

            return[
                'com_type'=>ucfirst($item->com_type),
                'com_old_info'=>$item->com_old_info,
                'com_new_info'=>$item->com_new_info,
                'com_date'=>$item->com_date
            ];
        });

        $collection = $map->concat($statusMap);

        return datatables($collection)
            ->addIndexColumn()
            ->make(true);
    }
    public function ConsRegOnline(Request $request)
    {
        $data_id = DB::table('cons_master')->insertGetId([
            'cm_last_name' => $request->cm_last_name,
            'cm_first_name' => $request->cm_first_name,
            'cm_middle_name' => $request->cm_middle_name,
            'cm_full_name' => $request->cm_first_name.' '.$request->cm_middle_name.' '.$request->cm_last_name,
            'cm_address' => $request->cm_address1,
            'cm_birthdate' => $request->cm_birthdate,
            'ct_id' => 8,
            'pending' => 1,
            'cm_remarks' => "Online",
            'cm_coordinates' => $request->cm_address,
            'cm_email' => $request->cm_email,
            'cm_contact_num' => $request->cm_contactnum,

        ]);
        $cm_id = $data_id;
        if($cm_id){
            Consumer::where('cm_id',$cm_id)->update(['temp_cons_id'=>$cm_id]);
            $ewallet = new EWALLET();
            $ewallet->cm_id = $cm_id;
            $ewallet->ew_total_amount = 0;
            $ewallet->save();
            if($ewallet){
                // files from Online
                $ca_brgy_cert = $request->ca_brgy_cert; 
                $ca_fire_permit = $request->ca_fire_permit;
                $ca_pic = $request->ca_pic;
                $ca_pvs = $request->ca_pvs;
                $ca_elec_plan = $request->ca_elec_plan;
                $ca_affidavit = $request->ca_affidavit;
                $ca_gov_id = $request->ca_gov_id;
                $ca_build_permit = $request->ca_build_permit;
                // Naming The files
                $ca_brgy_cert_db = time() . '_' . $ca_brgy_cert->getClientOriginalName();
                $ca_fire_permit_db = time() . '_' . $ca_fire_permit->getClientOriginalName(); 
                $ca_pic_db = time() . '_' . $ca_pic->getClientOriginalName(); 
                $ca_pvs_db = time() . '_' . $ca_pvs->getClientOriginalName(); 
                $ca_elec_plan_db = time() . '_' . $ca_elec_plan->getClientOriginalName(); 
                $ca_affidavit_db = time() . '_' . $ca_affidavit->getClientOriginalName(); 
                $ca_gov_id_db = time() . '_' . $ca_gov_id->getClientOriginalName(); 
                $ca_build_permit_db = time() . '_' . $ca_build_permit->getClientOriginalName(); 
                // Moving The Files to specific folders
                // $request->ca_brgy_cert->move(public_path('file_attachment/brgy_certificate'), $ca_brgy_cert_db); // move file 1  public to images
                // $request->ca_fire_permit->move(public_path('file_attachment/fire_permit'), $ca_fire_permit_db);
                // $request->ca_pic->move(public_path('file_attachment/picture'), $ca_pic_db);
                // $request->ca_pvs->move(public_path('file_attachment/perspective_view_of_structure'), $ca_pvs_db);
                // $request->ca_elec_plan->move(public_path('file_attachment/electrical_plan'), $ca_elec_plan_db);
                // $request->ca_affidavit->move(public_path('file_attachment/affidavit_of_ownership'), $ca_affidavit_db);
                // $request->ca_gov_id->move(public_path('file_attachment/gov_id'), $ca_gov_id_db);
                // $request->ca_build_permit->move(public_path('file_attachment/building_permit'), $ca_build_permit_db);
                
                $destinationPath = 'online_app';
                $newFileName = $ca_brgy_cert_db; // The desired filename
                Storage::disk('disk_d')->putFileAs($destinationPath.'/brgy_certificate/', $ca_brgy_cert, $ca_brgy_cert_db);
                Storage::disk('disk_d')->putFileAs($destinationPath.'/fire_permit/', $ca_fire_permit, $ca_fire_permit_db);
                Storage::disk('disk_d')->putFileAs($destinationPath.'/picture/', $ca_pic, $ca_pic_db);
                Storage::disk('disk_d')->putFileAs($destinationPath.'/perspective_view_of_structure/', $ca_pvs, $ca_pvs_db);
                Storage::disk('disk_d')->putFileAs($destinationPath.'/electrical_plan/', $ca_elec_plan, $ca_elec_plan_db);
                Storage::disk('disk_d')->putFileAs($destinationPath.'/affidavit_of_ownership/', $ca_affidavit, $ca_affidavit_db);
                Storage::disk('disk_d')->putFileAs($destinationPath.'/gov_id/', $ca_gov_id, $ca_gov_id_db);
                Storage::disk('disk_d')->putFileAs($destinationPath.'/building_permit/', $ca_build_permit, $ca_build_permit_db);

                $attach = DB::table('cons_attachment')->insert([
                    'ca_brgy_cert' => $ca_brgy_cert_db,
                    'ca_fire_permit' => $ca_fire_permit_db,
                    'ca_pic' => $ca_pic_db,
                    'ca_pvs' => $ca_pvs_db,
                    'ca_elec_plan' => $ca_elec_plan_db,
                    'ca_affidavit' => $ca_affidavit_db,
                    'ca_gov_id' => $ca_gov_id_db,
                    'ca_build_permit' => $ca_build_permit_db,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'cm_id' => $cm_id,
                ]);
                if($attach){
                    $approvalData = [
                        'title' => 'Dear' . ' ' . $request->cm_last_name . ':',
                        'body' => 'Assalamo Alaikom.'
                    ];
                    $notificationData = [];
                    Mail::to($request->cm_email)->send(new NotificationEmail($notificationData,$approvalData));
                    return response(['info'=>'Succesfully Created'],200);
                }
                return response(['info'=>'Something Went Wrong (Attach File)!!!'],422);
            }
            return response(['info'=>'Something Went Wrong (Ewallet)!!!'],422);
        }
        return response(['info'=>'Something Went Wrong (Consumer)!!!'],422);

    }
    public function modifyToOld(Request $request)
    {
        $query = DB::table('cons_master as cm')
        ->where('cm.cm_account_no',$request->newAcc)
        ->get();
        
        if($query->isNotEmpty()){
            return redirect('/user/maintenance/consumer/modify/to/old/index')->with('status1', 'Account Number: '.$request->newAcc.'  Already Exist');
        }

        $query2 = DB::table('cons_master as cm')
        ->where('cm.cm_account_no',$request->currentAcc)
        ->first();

        if(strlen($request->newAcc) < 10 || strlen($request->newAcc) > 10){
            return redirect('/user/maintenance/consumer/modify/to/old/index')->with('status2', 'Account Number: '.$request->newAcc.'  Invalid Account Format, Account Number Count Should be 10');
        }else{
            $routeCode = substr($request->newAcc,2,-4);

            $query3 = DB::table('route_code as rc')
            ->where('rc.rc_code',$routeCode)
            ->first();
            // dd($routeCode,$query3);
            if(!$query3){
                return redirect('/user/maintenance/consumer/modify/to/old/index')->with('status3', 'Route Code: '.$routeCode.'  Does not Exist');
            }
        }
        Consumer::where('cm_account_no',$request->currentAcc)
        ->update([
            'cm_account_no'=>$request->newAcc,
            'rc_id'=>$query3->rc_id
        ]);
        
        $data = (new ConsumerModifyService('account no',$query2->cm_id,$request->currentAcc,$request->newAcc,Auth::id()))->modify();
        $data2 = (new ConsumerModifyService('route id',$query2->cm_id,($query2->rc_id == NULL) ? 'No Route' :$query2->rc_id ,$query3->rc_id,Auth::id()))->modify();
        
        return redirect('/user/maintenance/consumer/modify/to/old/index')->with('status', 'Successfully Updated');
    }

}
