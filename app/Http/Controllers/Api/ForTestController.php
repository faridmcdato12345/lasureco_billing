<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForTestController extends Controller
{
    public function testFile(Request $request){
        // dd('Hi Ramos');
        // $file = $request->file('user_file');
        $file = $request->user_file;
        $name = time() . '_' . $file->getClientOriginalName();
        $request->user_file->move(public_path('images'), $name);
        $file2 = $request->user_file2;
        $name2 = time() . '_' . $file2->getClientOriginalName();

        // dd($name . ' '. $name2);
        $request->user_file2->move(public_path('images'), $name2);
        // get the original file name
        // $filename = $request->file('user_file')->getClientOriginalName();
        // $filename = $request->user_file->getClientOriginalName();
        // $path = $request->user_file->store('images', 's3');
        // dd($file .', '. $request->comments . ', ' . $path);
        return response(['message'=>'Succesfully Updated'],200);
    }
}
