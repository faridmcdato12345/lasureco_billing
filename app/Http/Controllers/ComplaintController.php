<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Services\ComplaintExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class ComplaintController extends Controller
{
    public function index(){
        return view('user.maintenance.complaintShow');
    }
    public function store(Request $request){
        $validatedData = $request->validate([
            'account_number' => ['required'],
            'category' => ['required'],
        ]);
        if($request->image != ''){
        $imageName = time().'.'.$request->image->extension(); 
        $request->image->move(public_path('images'), $imageName);
        }
        else{
            $imageName = '';
        } 
        $pp = date('Y-m-d H:i:s');
        $newpp = explode(':', $pp);
        $newpp1 = explode('-', $pp);

        $finalpp = $newpp[0] . $newpp[1] . $newpp[2];
        $newfinalpp = explode('-',$finalpp);
        $newfinalpp1 = $newfinalpp[0]. $newfinalpp[1]. $newfinalpp[2];
        $newfinalpp2 = explode(' ',$newfinalpp1);
        $newfinalpp3 = $newfinalpp2[0].$newfinalpp2[1];
        // $newpp = 'PP' . '-' . $newpp;
        $query = DB::table('complaint_list')
        ->insert([
            'cmid' => $request->cmid,
            'complaint_no' => 'PP' . '-' . $newfinalpp3,
            'category' => $request->category,
            'sub_category' => $request->subcategory,
            'sketch_path' => $imageName,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $request->tellerid,
        ]);
        return view('user.maintenance.complaintShow');

    }
    /* complaint list */
    public function getPendingConsumerC(){
        // $query = Consumer::select('cm_id','ct_id','cm_account_no','cm_full_name','cm_address','cm_con_status','mm_master')->where('pending',1);    
        $query = DB::table('complaint_list as cl')
        ->join('cons_master as cm', 'cl.cmid','=','cm.cm_id')
        ->join('cons_type as ct', 'ct.ct_id','=','cm.ct_id')
        ->select('cl.id','cl.complaint_no','cl.category','cl.sub_category','cl.sketch_path','cl.recommendation','cl.finding','cl.status','cl.cmid','cl.created_at','cm.cm_full_name as ffname','cm.cm_account_no as accno','ct.ct_desc as ctdesc')
        ->get();
        
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('fullname', function($row){
                    $btn3 = '<label>'.substr($row->ffname,0,20).'</label>';
                    return $btn3;
                })
            ->addColumn('accno', function($row){
                    $btn2 = '<label>'.$row->accno.'</label>';
                    return $btn2;
                })
            ->addColumn('created_at', function($row){
                    $date = strtotime($row->created_at);
                    $date1 = date('M-d-Y',$date);
                    $btn4 = '<label>'.$date1.'</label>';
                    return $btn4;
                })
            ->addColumn('user_status', function($row){
                if($row->status == 1){
                    $btn1 = '<label>On Process</label>';
                }
                else{
                    $btn1 = '<label>Done</label>';
                }
                    return $btn1;
                })
            ->addColumn('action', function($row){
                $data = $row->id . '@' . $row->cmid . '@' . $row->complaint_no . '@' . $row->category . '@' . $row->sketch_path . '@' . $row->sub_category . '@' .$row->status . '@' . $row->finding . '@' . $row->recommendation . '@' . $row->ctdesc; 
                $btn = '<button style ="font-size:10px;height:30%;width:28.5%" id="'.$data.'" class="btn btn-primary btn-sm" onclick = "text(this,'.$row->cmid.')" >view</button>';
                $btn .= '<button style ="text-align:right;font-size:10px;height:30%;width:35%" id="'.$data.'" class="btn btn-danger btn-sm ml-1 mr-1" onclick = "text2(this,'.$row->id.')" >update</button>';
                $btn .= '<button style ="font-size:10px;height:30%;width:28.5%" id="'.$data.'" class="btn btn-success btn-sm" onclick = "text3(this,'.$row->cmid.')" >Print</button>';
                return $btn;
                })
            
            ->rawColumns(['action','user_status','fullname','accno','created_at'])
            ->make(true);
    }
    /* show category */
    public function complaintCategory(){
        $queryCC = DB::table('complaint_category')
        ->select('complaint_id','category')
        ->get();

        return datatables($queryCC)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button style ="height:35px;margin-left:7px;font-size:10px" id="'.$row->category.'" class="btn btn-primary btn-sm" onclick = "text2(this,'.$row->complaint_id.')" >SELECT</button>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function complaintCategoryindex(){
        return view('user.maintenance.complaintStore');
    }
    /* add category */
    public function complaintCategoryStore(request $request){
        $query = DB::table('complaint_category')
        ->insert([
            'category' => $request->category,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->route('complaint.category');
    }
    /* addsub category */
    public function complaintSubCategoryStore(request $request){
        $query = DB::table('complaint_sub_category')
        ->insert([
            'sub_category' => $request->subcategory,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->route('complaint.category');
    }
    /* show subcategory */
    public function complaintSubCategory(){
        $queryCC = DB::table('complaint_sub_category')
        ->select('sub_category_id','sub_category')
        ->get();

        return datatables($queryCC)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button style ="height:35px;margin-left:7px;font-size:10px" id="'.$row->sub_category.'" class="btn btn-primary btn-sm" onclick = "text(this,'.$row->sub_category_id.')" >SELECT</button>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function complaintListInfo($id){
        $query = DB::table('cons_master')
        ->join('cons_type as ct', 'cons_master.ct_id','=','ct.ct_id')
        ->select('cm_full_name','cm_account_no','cm_address','mm_master','ct.ct_desc',)
        ->where('cm_id',$id)
        ->first();
        return response([
            'data'=>$query,
        ],200);
    }
    public function sketh($id){
        $query = DB::table('complaint_list')
        ->select('sketch_path','id')
        ->where('id',$id)
        ->get();
        return view('print.maintenance.print_sketch',['data'=>$query]);
    }
    public function exportComplaint(Request $request){
       
        $f = (new ComplaintExport($request->from_date,$request->to_date))
            ->download('complaint.xlsx');
        if($f){
            $query = DB::table('complaint_list')
            ->whereDate('complaint_list.created_at','>=',$request->from_date)
            ->whereDate('complaint_list.created_at','<=',$request->to_date)
            ->orderByDesc('complaint_no');
            return $f;
        }
    }
    public function updateComplaint(Request $request){
        $query = DB::table('complaint_list')
        ->where('id', $request->cid)
        ->update(['finding' => $request->finding,
                'recommendation' => $request->recommendation,
                    'status' => intval($request->status)]);
            
                    return redirect()->route('complaint.show');
    }
}
