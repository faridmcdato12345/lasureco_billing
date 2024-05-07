<?php

namespace App\Http\Controllers;

use App\Exports\InspectionExport;
use App\Exports\VerificationExport;
use App\Models\Consumer;
use App\Models\ConsumerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use Maatwebsite\Excel\Facades\Excel;

class ConsumerPendingController extends Controller
{
    public function getPendingConsumer(){
        $query = Consumer::select('cm_id','ct_id','cm_account_no','cm_full_name','cm_address','cm_con_status','mm_master','created_at')->where('pending',1);    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('status', function($row){
                $status = $row->cm_con_status;
                if(!$status){
                    $btn = '<div class="custom-control custom-switch">';
                    $btn = $btn . '<input type="checkbox" class="custom-control-input ad_switch" id="'.$row->cm_id.'">';
                    $btn = $btn . '<label class="custom-control-label status_label" for="'.$row->cm_id.'">Inactive</label>';
                    $btn = $btn . '<input type="hidden" id="status_hidden" value="0"></div>';
                }
                else{
                    $btn = '<div class="custom-control custom-switch">';
                    $btn = $btn . '<input type="checkbox" class="custom-control-input ad_switch" id="'.$row->cm_id.'" checked="">';
                    $btn = $btn . '<label class="custom-control-label status_label" for="'.$row->cm_id.'">Active</label>';
                    $btn = $btn . '<input type="hidden" id="status_hidden" value="1"></div>';
                }
                return $btn;
            })
            ->rawColumns(['status'])
            ->make(true);
            
    }
    // public function getPendingConsumer(){
    //     $query = Consumer::select('cm_id','ct_id','cm_account_no','cm_full_name','cm_address','cm_con_status','mm_master','created_at')->where('pending',1);    
    //     return datatables($query)
    //         ->addIndexColumn()
    //         ->make(true);
    // }
    public function onlineAppList(){
        //when transfer server use DB on namespace
        $query = collect(DB::table('cons_master as cm')
        ->join('cons_attachment as ca','cm.cm_id','=','ca.cm_id')
        ->where('cm.pending',1)
        ->where('cm.cm_remarks','online')
        ->get());

        // if($query->isEmpty()){
        //     return response(['info'=>'No Record Found'],422);
        // }

        $posObj = (object)array('position' => 0);
        $map = $query->map(function($item) use($posObj){
            return[
                'num'=> ++$posObj->position,
                'account'=>($item->cm_account_no == NULL) ? 'TBD' : $item->cm_account_no,
                'name'=>$item->cm_full_name,
                'address'=>$item->cm_address,
                'id'=>$item->cm_id,
                'status'=>$item->pending,
                'created'=>$item->created_at,
            ];
        });
        return view('user.maintenance.consumer.online_application_list', compact('map'));
    }
    public function approvedAppList(){
        //when transfer server use DB on namespace
        $query = collect(DB::table('cons_master as cm')
        ->join('cons_attachment as ca','cm.cm_id','=','ca.cm_id')
        ->where('cm.pending',0)
        ->where('cm.cm_remarks','online')
        ->get());

        // if($query->isEmpty()){
        //     return response(['info'=>'No Record Found'],422);
        // }

        $posObj = (object)array('position' => 0);
        $map = $query->map(function($item) use($posObj){
            return[
                'num'=> ++$posObj->position,
                'account'=>($item->cm_account_no == NULL) ? 'TBD' : $item->cm_account_no,
                'name'=>$item->cm_full_name,
                'address'=>$item->cm_address,
                'id'=>$item->cm_id,
                'status'=>$item->pending,
                'created'=>$item->created_at,
            ];
        });
        return view('user.maintenance.consumer.approved_application', compact('map'));
    }
    public function getLastCons($id){
        $maxvalue = DB::table('cons_master as cm')
        ->where('cm.rc_id',$id)
        ->max('cm.cm_account_no');
        
        if($maxvalue == NULL){
            $q = DB::table('route_code as rc')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->where('rc.rc_id',$id)
            ->first();

            $maxvalue = $q->tc_code.''.$q->rc_code.'0000';
        }
        return response()->json($maxvalue);
    }
    public function approveConsumer($id){

        $query = DB::table('cons_master as cm')
        ->select('cm.cm_last_name', 'cm.cm_email')
        ->where('cm.cm_id', $id)
        ->first();
        
        $notificationData = [
            'title' => 'Dear' . ' ' . $query->cm_last_name . ':',
            'body' => 'Assalamo Alaikom.'
        ];
        $approvalData = [];
        Mail::to($query->cm_email)->send(new NotificationEmail($notificationData,$approvalData));
        
        $affected = DB::table('cons_master as cm')
        ->where('cm.cm_id', $id)
        ->update(['cm.pending' => 0]);
        // dd("Successfully");
        return response()->json($affected);
    }
    public function setConsAccount(request $request){
        // $query = DB::table('cons_master as cm')
        // ->select('cm.cm_last_name', 'cm.cm_email')
        // ->where('cm.cm_id', $request->cm_id)
        // ->first();
        
        
        $affected = DB::table('cons_master as cm')
        ->where('cm.cm_id', $request->cm_id)
        ->update(['cm.cm_account_no' => $request->cons_account]);

        return response()->json($affected);
    }
    public function show($id){
        $query = DB::table('cons_master as cm')
        ->join('cons_attachment as ca','cm.cm_id','=','ca.cm_id')
        ->where('cm.cm_id',$id)
        ->first();
        // dd($query->ca_brgy_cert);
        if ($query) {
            $imageArray = [];
            // no image
            $noimagePath = 'online_app/noimage.png';
            $noImage = base64_encode(Storage::disk('disk_d')->get($noimagePath));
            // brgy
            $brgyCrtFileName = $query->ca_brgy_cert;
            $brgyCrtPath = 'online_app/brgy_certificate/'.$brgyCrtFileName;
            $brgyCrtFile = Storage::disk('disk_d')->exists($brgyCrtPath) ? base64_encode(Storage::disk('disk_d')->get($brgyCrtPath)) : $noImage;
            $imageArray['brgy_cert'] = $brgyCrtFile;
            //fire
            $firePermName = $query->ca_fire_permit;
            $fireCrtPath = 'online_app/fire_permit/'.$firePermName;
            $fireFile = Storage::disk('disk_d')->exists($fireCrtPath) ? base64_encode(Storage::disk('disk_d')->get($fireCrtPath)) : $noImage;
            $imageArray['fire_permit'] = $fireFile;
            //pic
            $picName = $query->ca_pic;
            $picPath = 'online_app/picture/'.$picName;
            $picFile = Storage::disk('disk_d')->exists($picPath) ? base64_encode(Storage::disk('disk_d')->get($picPath)) : $noImage;
            $imageArray['picture'] = $picFile;
            //pvs
            $pvsName = $query->ca_pvs;
            $pvsPath = 'online_app/perspective_view_of_structure/'.$pvsName;
            $pvsFile = Storage::disk('disk_d')->exists($pvsPath) ? base64_encode(Storage::disk('disk_d')->get($pvsPath)) : $noImage;
            $imageArray['pvs'] = $pvsFile;
            //ca_elec_plan
            $eplanName = $query->ca_elec_plan;
            $eplanPath = 'online_app/perspective_view_of_structure/'.$eplanName;
            $eplanFile = Storage::disk('disk_d')->exists($eplanPath) ? base64_encode(Storage::disk('disk_d')->get($eplanPath)) : $noImage;
            $imageArray['eplan'] = $eplanFile;
            //ca_affidavit
            $affidName = $query->ca_affidavit;
            $affidPath = 'online_app/perspective_view_of_structure/'.$affidName;
            $affidFile = Storage::disk('disk_d')->exists($affidPath) ? base64_encode(Storage::disk('disk_d')->get($affidPath)) : $noImage;
            $imageArray['affidavit'] = $affidFile;
            //ca_gov_id
            $govName = $query->ca_gov_id;
            $govPath = 'online_app/perspective_view_of_structure/'.$govName;
            $govFile = Storage::disk('disk_d')->exists($govPath) ? base64_encode(Storage::disk('disk_d')->get($govPath)) : $noImage;
            $imageArray['gov'] = $govFile;
            //ca_build_permit
            $buildName = $query->ca_build_permit;
            $buildPath = 'online_app/perspective_view_of_structure/'.$buildName;
            $buildFile = Storage::disk('disk_d')->exists($buildPath) ? base64_encode(Storage::disk('disk_d')->get($buildPath)) : $noImage;
            $imageArray['build_permit'] = $buildFile;
            //inspector
            $inspectorName = $query->ca_inspector;
            $inspectorPath = 'online_app/inspector/'.$inspectorName;
            $inspectorFile = Storage::disk('disk_d')->exists($inspectorPath) ? base64_encode(Storage::disk('disk_d')->get($inspectorPath)) : $noImage;
            $imageArray['inspector'] = $inspectorFile;
            //verification
            // dd($query->ca_verification);
            $verificationName = $query->ca_verification;
            $verificationPath = 'online_app/verification/'.$verificationName;
            $verificationFile = Storage::disk('disk_d')->exists($verificationPath) ? base64_encode(Storage::disk('disk_d')->get($verificationPath)) : $noImage;
            $imageArray['verification'] = $verificationFile;
            //or
            $orName = $query->ca_or;
            $orPath = 'online_app/or_file/'.$orName;
            $orFile = Storage::disk('disk_d')->exists($orPath) ? base64_encode(Storage::disk('disk_d')->get($orPath)) : $noImage;
            $imageArray['or'] = $orFile;



            // $type = ['image/jpeg', 'image/jpg','image/png'];
            // dd($imageArray);
            // Prepare the response
            $response = [
                'images' => $imageArray,
                // 'type' => $type,
                'details'=> $query
            ];

            return response()->json($response);
        }else{
            
            return response()->json(['error' => 'Image not found.'],404);
        }
        // $map = $query->map(function($item){
        //     return[
        //         'account'=>($item->cm_account_no == NULL) ? 'TBD' : $item->cm_full_name,
        //         'name'=>$item->cm_full_name,
        //         'address'=>$item->cm_address,
        //         'created'=>$item->created_at,
        //     ];
        // });
        // return response()->json($query);
        
     }
    
    public function updateInspector(request $request){
        $ca_inspector = $request->ca_inspector;
        // dd($ca_inspector);
        $ca_inspector_db = time() . '_' . $ca_inspector->getClientOriginalName();
        $ca_verification = $request->ca_verification;
        // dd($ca_inspector);
        $ca_verification_db = time() . '_' . $ca_verification->getClientOriginalName();
        $destinationPath = 'online_app';
        Storage::disk('disk_d')->putFileAs($destinationPath.'/inspector/', $ca_inspector, $ca_inspector_db);
        Storage::disk('disk_d')->putFileAs($destinationPath.'/verification/', $ca_verification, $ca_verification_db);
        $query = DB::table('cons_attachment as ca')
            ->where('ca.cm_id', $request->cm_id)
            ->update(['ca.ca_inspector' => $ca_inspector_db,
            'ca_verification' => $ca_verification_db
            ]);
        
        if($query){
            return response(['info'=>'Succesfully Uploaded'],200);
        }
    }
    public function updateOR(request $request){
        $ca_or = $request->ca_or;
        // dd($ca_inspector);
        $ca_or_db = time() . '_' . $ca_or->getClientOriginalName();
        $destinationPath = 'online_app';
        Storage::disk('disk_d')->putFileAs($destinationPath.'/or_file/', $ca_or, $ca_or_db);
        $query = DB::table('cons_attachment as ca')
        ->where('ca.cm_id', $request->cm_id)
        ->update(['ca.ca_or' => $ca_or_db]);
        
        if($query){
            return response(['info'=>'Succesfully Uploaded'],200);
        }
    }
    public function generateInspectionReport($id){
        $filenameExport = "InspectionReport.xlsx";
        return Excel::download(new InspectionExport($id), $filenameExport); 
    }
    public function generateVerificationReport($id){
        $filenameExport = "VerificationSlip.xlsx";
        return Excel::download(new VerificationExport($id), $filenameExport); 
    }

    public function rejectOnline($id)
    {
        $selectCons = DB::table('cons_master as cm')
        ->join('cons_attachment as ca','cm.cm_id','=','ca.cm_id')
        ->where('cm.cm_id', $id)
        ->first();

        if($selectCons){
            // Get file paths
            $affidavitPath = 'online_app/affidavit_of_ownership/'. basename($selectCons->ca_affidavit);
            $brgyPath = 'online_app/brgy_certificate/'.basename($selectCons->ca_brgy_cert);
            $buildPath = 'online_app/building_permit/'.basename($selectCons->ca_build_permit);
            $elecPPath = 'online_app/electrical_plan/'.basename($selectCons->ca_elec_plan);
            $firePath = 'online_app/fire_permit/'.basename($selectCons->ca_fire_permit);
            $govPath = 'online_app/gov_id/'.basename($selectCons->ca_gov_id);
            $pvsPath = 'online_app/perspective_view_of_structure/'.basename($selectCons->ca_pvs);
            $picPath = 'online_app/picture/'.basename($selectCons->ca_pic);
            $insPath = 'online_app/inspector/'.basename($selectCons->ca_inspector);
            $orPath = 'online_app/or_file/'.basename($selectCons->ca_or);
            $verPath = 'online_app/verification/'.basename($selectCons->ca_verification);
            // dd($brgyPath);
            // Delete files if paths are not null
            if ($affidavitPath !== null && Storage::disk('disk_d')->exists($affidavitPath)) {
                Storage::disk('disk_d')->delete($affidavitPath);
            }
            if ($brgyPath !== null && Storage::disk('disk_d')->exists($brgyPath)) {
                Storage::disk('disk_d')->delete($brgyPath);
            }
            if ($buildPath !== null && Storage::disk('disk_d')->exists($buildPath)) {
                Storage::disk('disk_d')->delete($buildPath);
            }
            if ($elecPPath !== null && Storage::disk('disk_d')->exists($elecPPath)) {
                Storage::disk('disk_d')->delete($elecPPath);
            }
            if ($firePath !== null && Storage::disk('disk_d')->exists($firePath)) {
                Storage::disk('disk_d')->delete($firePath);
            }
            if ($govPath !== null && Storage::disk('disk_d')->exists($govPath)) {
                Storage::disk('disk_d')->delete($govPath);
            }
            if ($pvsPath !== null && Storage::disk('disk_d')->exists($pvsPath)) {
                Storage::disk('disk_d')->delete($pvsPath);
            }
            if ($picPath !== null && Storage::disk('disk_d')->exists($picPath)) {
                Storage::disk('disk_d')->delete($picPath);
            }
            if ($insPath !== null && Storage::disk('disk_d')->exists($insPath)) {
                Storage::disk('disk_d')->delete($insPath);
            }
            if ($orPath !== null && Storage::disk('disk_d')->exists($orPath)) {
                Storage::disk('disk_d')->delete($orPath);
            }
            if ($verPath !== null && Storage::disk('disk_d')->exists($verPath)) {
                Storage::disk('disk_d')->delete($verPath);
            }
            // dd($affidavitPath);
            $selectEwallet = DB::table('e_wallet')->where('cm_id',$id)->first();
            if($selectEwallet){
                // Delete Ewallet Log ( NOT NEEDED)
                // $deleteEWL = DB::table('e_wallet_log')->where('ew_id',$selectEwallet->ew_id)->delete();
                //Delete Ewallet
                $deleteEW = DB::table('e_wallet')->where('cm_id',$id)->delete();
                if($deleteEW){
                    // Delete Attachment
                    $deleteAttach = DB::table('cons_attachment')->where('cm_id',$id)->delete();
                    // Delete Consumer
                    if($deleteAttach){
                        $deleteCons = DB::table('cons_master')->where('cm_id',$id)->delete();
                        if($deleteCons){
                            return redirect('/user/maintenance/consumer/online_application_list')->with('info', 'Succesfully Deleted');
                        }else{
                            return response()->json(['error' => 'Something Went Wrong (Delete Consumer).'],404);
                        }
                    }else{
                        return response()->json(['error' => 'Something Went Wrong (Delete Attachement).'],404);
                    } 
                }else{
                    return response()->json(['error' => 'Something Went Wrong (Delete Ewallet).'],404);
                }

            }else{
                return response()->json(['error' => 'Something Went Wrong (GET Ewallet).'],404);
            }
        }else{
            return response()->json(['error' => 'Something Went Wrong (GET Consumer).'],404);
        }
    }
}
    
