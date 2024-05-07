<?php

namespace App\Http\Controllers;

use App\Imports\DataImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ImportSalesDataController extends Controller
{
    public function index(){
        return view('user.utility.import_sales_data.index');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'fileToUpload'=> 'required|mimes:xls,xlsx'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors(['Invalid file format!']);
        } else {
             $x = Excel::import(new DataImport, $request->file('fileToUpload')->store('temp'));
             if($x){
                 return redirect()->route('import.sales.data')->with('success', 'Collection is successfully uploaded!');
             }
        }
    }
}
