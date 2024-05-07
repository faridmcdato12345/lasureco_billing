<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReadAndBillController extends Controller
{
    public function getConsumerPerRoute(Request $request){
        $bp = $request->bill_period;
        $query = collect(DB::table('cons_master as cm')
        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
        ->leftJoin('meter_reg as mr', function($join) use($bp) {
            $join->on('cm.cm_id', '=', 'mr.cm_id')
              ->where('mr.mr_date_year_month', '=', $bp);
        })
        ->select('cm.cm_id','cm.cm_account_no','cm.cm_full_name','cm.rc_id',
        'cm.ct_id','cm.cm_seq_no','rc.rc_code','rc.rc_desc','cm.mm_master')
        ->where('rc.rc_code',$request->route_code)
        ->whereNull('mr.mr_id')
        ->get());
        // $count = count($query);
        if($query->isEmpty()){
            return response()->json('Consumer for this Route are empty or already read',404);
        }

        return response()->json($query);

    }

    public function uploadImages(Request $request)
    {
        $images = $request->all();

        // Chunk size (adjust according to your needs)
        $chunkSize = 50;
        $count = 0;
        // Process images in chunks
        foreach (array_chunk($images, $chunkSize) as $chunk) {
            $count = $count + $this->storeChunkOfImages($chunk);
        }

        if($count > 0){
            return response()->json(['message' => 'Images stored successfully'], 200);
        }else{
            return response()->json(['message' => 'Images already exist'], 422);
        }

        
    }

    private function storeChunkOfImages($chunk)
    {
        $count = 0;
        foreach ($chunk as $image) {
            $imageName = $image['image_name'];
            $imageData = $image['image'];
            $billperiod = substr($imageName, 0, 6); // first 6 digit 2024014343010001 -> 202401
            $routecode = substr($imageName, 8, 4);  // 8-12 digit 2024014343010001 -> 4301
            $account = substr($imageName,6,10);
            // dd($billperiod,$routecode,$account);

            // Decode base64 image data
            $decodedImage = base64_decode($imageData);

            if (!Storage::disk('disk_d')->exists('Read_And_Bill/'.$billperiod)) {
                Storage::disk('disk_d')->makeDirectory('Read_And_Bill/'.$billperiod);
            }

            if(!Storage::disk('disk_d')->exists('Read_And_Bill/'.$billperiod.'/'.$routecode)){
                Storage::disk('disk_d')->makeDirectory('Read_And_Bill/'.$billperiod.'/'.$routecode);
            }

            if(!Storage::disk('disk_d')->exists('Read_And_Bill/'.$billperiod.'/'.$routecode)){
                Storage::disk('disk_d')->makeDirectory('Read_And_Bill/'.$billperiod.'/'.$routecode);
            }

            // Ensure directories exist
            $directoryPath = 'Read_And_Bill/'.$billperiod.'/'.$routecode;
            if (!Storage::disk('disk_d')->exists($directoryPath)) {
                Storage::disk('disk_d')->makeDirectory($directoryPath, 0777, true, true);
                // The 0777 mode ensures the directory is writable. Adjust as needed.
            }

            // Store the image file
            $imagePath = $directoryPath.'/'.$account.'.jpg'; 
            $exist = storage::disk('disk_d')->has($directoryPath.'/'.$account.'.jpg');
            if($exist){
                continue;
            }else{
                $count = $count +1;
                Storage::disk('disk_d')->put($imagePath, $decodedImage);
            }
        }

        return $count;
    }
    public function getUsers($name){
        $users = DB::table('user')->select(
            'user_id','password',
            DB::raw('SUBSTRING_INDEX(username, " ", 1) AS username'),
            DB::raw('RTRIM(LTRIM(user_full_name)) AS user_full_name'))
            ->where('username','LIKE','%'.$name.'%')->get();
        if($users->isEmpty()){
            return response()->json(['info'=>'No User Found'],404);
        }
        return response()->json(['users'=>$users],200);
    }

}

