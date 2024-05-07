<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HrController extends Controller
{
    public function dtr(Request $request)
    {
        $emp_id = $request->id;
        $dateFrom = Carbon::parse($request->date_from)->format('Y-m-d');
        $dateTo = Carbon::parse($request->date_to)->format('Y-m-d');
        $queryAttendance = collect(DB::connection('pgsql')->table('att_payloadtimecard')
        ->where('emp_id',$emp_id)
        ->whereBetween('att_date',[$dateFrom,$dateTo])
        ->get());
        
        if($queryAttendance->isEmpty()){
            return response()->json(['info'=>'No Attendance Found'],422);
        }
        $dtr = collect();
        
        $diffDays = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo));
        
        for($i=0;$i <= $diffDays;$i++){
            $newDate[$i] = Carbon::parse($dateFrom)->addDays($i);
            $data[$i] = $queryAttendance->where('att_date',$newDate[$i]->format('Y-m-d'));
            if($data[$i]->isEmpty()){
                if($newDate[$i]->isWeekend()){
                    $dtr->push([
                        'date'=> $newDate[$i]->format('Y-m-d'),
                        'am_in_out'=> 'Weekend',
                        'pm_in_out'=> 'Weekend',
                    ]);
                }else{
                    $dtr->push([
                        'date'=> $newDate[$i]->format('Y-m-d'),
                        'am_in_out'=> 'Absent',
                        'pm_in_out'=> 'Absent',
                    ]);
                }
                
            }else{
                $amIn = $this->formatAmPmIn($data[$i], 'am');
                $amOut = $this->formatAmPmOut($data[$i], 'am');
                $pmIn = $this->formatAmPmIn($data[$i], 'pm');
                $pmOut = $this->formatAmPmOut($data[$i], 'pm');
                $dtr->push([
                    'date' => $data[$i]->first()->att_date,
                    'am_in_out' => $amIn.' | '.$amOut,
                    'pm_in_out' => $pmIn.' | '.$pmOut,
                ]);
            }            
            $newDate[$i]->addDays();
        }

        return response()->json(['info'=> $dtr],200);
    }
    private function formatAmPmIn($dataSubset, $timeTableAlias) {
        $item = $dataSubset->where('time_table_alias', $timeTableAlias)->whereNotNull('clock_in')->first();
        if(!$item){
            return 'No Check In';
        }
        return carbon::parse($item->clock_in)->format('h:i');
    }
    private function formatAmPmOut($dataSubset, $timeTableAlias) {
        $item = $dataSubset->where('time_table_alias', $timeTableAlias)->whereNotNull('clock_out')->first();
        if(!$item){
            return 'No Check Out';
        }
        return carbon::parse($item->clock_out)->format('h:i');
    }
    public function hr_register(Request $request){
        $user = DB::connection('hr_mysql')->table('employees')
        ->select('username','emp_id','firstname','lastname','password')
        ->where('firstname',ucwords($request->first_name))
        ->where('lastname',ucwords($request->last_name))
        ->where('emp_code',$request->code)
        ->first();
        $passLength = strlen($request->password);

        if($user->username != NULL && $user->password != NULL){
            return response()->json(['info'=>'Account Alraedy Exist'],422);
        }
        if($user->username != NULL || $user->password != NULL){
            return response()->json(['info'=>'Please Consult ICT Department'],422);
        }
        
        if($request->password !== $request->confirm_password){
            return response()->json(['info'=>'Password did not Matched'],422);
        }

        if(!isset($request->user_name)){
            return response()->json(['info'=>'Username is required'],422);
        }

        $username = DB::connection('hr_mysql')->table('employees')
        ->where('username',$request->user_name)
        ->first();

        if($username){
            return response()->json(['info'=>'User Name already taken'],422);
        }
        if($passLength < 8){
            return response()->json(['info'=>'Password must be equal to or morethan 8 Characters'],422);
        }

        DB::connection('hr_mysql')->table('employees')
        ->where('firstname',ucwords($request->first_name))
        ->where('lastname',ucwords($request->last_name))
        ->where('emp_code',$request->code)
        ->update([
            'username' => $request->user_name,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['info'=>'Succesfully Registered'],200);

    }
    public function hr_login(Request $request)
    {
        $user = DB::connection('hr_mysql')->table('employees')
        ->select('username','emp_id','firstname','lastname','password','emp_code')
        ->where('username',$request->username)
        ->first();

        if(!$user){
            return response()->json(['info'=>'User Doesnt Exist'],422);
        }
        if(!Hash::check($request->password, $user->password))
        {
            return response(['info'=>'Invalid password'],401);
        }
        // $token = $this->storeAuthToken(hash('sha256',$user->emp_id.'-'.Carbon::now()),$user->emp_id);
        $token = $this->storeAuthToken(hash('sha256',$user->emp_id).'|'.Carbon::now(),$user->emp_id);
        // Profile Image not found return default image
        $noimagePath = 'hr_profile_images/noimage.jpg';
        $noImage = base64_encode(Storage::disk('disk_d')->get($noimagePath));
        // Profile Image Path
        $profileImagePath = 'hr_profile_images/'.$user->emp_code.'.jpg';
        $profileImage = Storage::disk('disk_d')->exists($profileImagePath) ? 
        base64_encode((Storage::disk('disk_d')->get($profileImagePath))) : $noImage;
        $user->photo_url = 'data:image/jpeg;base64,'.$profileImage;
        // remove password from User
        unset($user->password);
        $response = [
            'user'=>$user,
            'token'=>$token
        ];
        
        return response()->json($response,200);
    }
    private function storeAuthToken($data,$empID)
    {
        $token = DB::connection('hr_mysql')->table('employees')
        ->where('emp_id',$empID)
        ->update(['token' => $data]);

        return $data;
    }
    public function userDetails($id)
    {
        $user = DB::connection('hr_mysql')->table('employees')
        ->select('username','emp_id','firstname','lastname','contact_no','gender','date_of_birth','current_address','permanent_address')
        ->where('emp_id',$id)
        ->first();
        if(!$user){
            return response()->json(['info'=>'User Doesnt Exist'],422);
        }

        return response()->json(['info'=>$user]);
    }
    public function changePassword(Request $request)
    {
        $emp_id = $request->id;
        if($request->password !== $request->confirm_password){
            return response()->json(['info'=>'Please Confirm New Password, Password Did not Matched!'],422);
        }

        $user = DB::connection('hr_mysql')->table('employees')
        ->select('emp_id','password')
        ->where('emp_id',$emp_id)
        ->first();

        if(!$user){
            return response()->json(['info'=>'User Doesnt Exist'],422);
        }
        
        $passLength = strlen($request->password);
        if($passLength < 8){
            return response()->json(['info'=>'Password must be equal to or morethan 8 Characters'],422);
        }

        $updatePass = DB::connection('hr_mysql')->table('employees')
        ->where('emp_id',$emp_id)
        ->update(['password' => bcrypt($request->password)]);

        return response()->json(['info'=>'Succesfully Changed Pass'],200);


    }
}
