<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use Response;
use DB;
use Flash;
use Yajra\Datatables\Services\DataTable;
use Yajra\Datatables\Facades\Datatables;


class Add_Timesheet extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $project = Project::pluck('project_name', 'id')->all();
        return view('timesheets.add_timesheet',compact('project'));
    }

    public function show($id)
    {
        $lokasi = array('JABODETABEK'=>'JABODETABEK','DOMESTIK P. JAWA'=>'DOMESTIK P. JAWA','DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA','INTERNATIONAL'=>'INTERNATIONAL','UNCLAIMABLE'=>'UNCLAIMABLE');
        $activity = array('CUTI'=>'CUTI','LIBUR'=>'LIBUR','IDLE' => 'IDLE','SAKIT' => 'SAKIT','SUPPORT' => 'SUPPORT','OTHERS' => 'OTHERS');
        $project = Project::pluck('project_name', 'id')->all();
        $timesheet = Timesheet::where('id','=',$id)->first();
        $timesheet_details = TimesheetDetail::where('timesheet_id','=',$id)->get();
        $timesheet_insentif = DB::table('timesheet_insentif')->where('timesheet_id','=',$id)->get();
        $timesheet_transport = DB::table('timesheet_transport')->where('timesheet_id','=',$id)->get();
        //return response()->json($timesheet_transport);
        return view('timesheets.edit_timesheet',compact('lokasi','activity','timesheet','project','id','timesheet_details','timesheet_insentif','timesheet_transport'));
    }
    
    public function form()
    {   $lokasi = array('JABODETABEK'=>'JABODETABEK','DOMESTIK P. JAWA'=>'DOMESTIK P. JAWA','DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA','INTERNATIONAL'=>'INTERNATIONAL','UNCLAIMABLE'=>'UNCLAIMABLE');
        $activity = array('CUTI'=>'CUTI','LIBUR'=>'LIBUR','IDLE' => 'IDLE','SAKIT' => 'SAKIT','SUPPORT' => 'SUPPORT','OTHERS' => 'OTHERS');
        $project = Project::pluck('project_name', 'id')->all();
        return view('timesheets.add_timesheet',compact('project','lokasi','activity'));
    }
    
    public function create(Request $req)
    {
        $action = $req->action == 'Save' ? 'Disimpan' : 'Terkirim';
        if(isset($req->edit)){
            $deleted = DB::table('timesheets')->where('id', $req->edit)->delete();
            $deleted1 = DB::table('timesheet_transport')->where('timesheet_id', $req->edit)->delete();
            $deleted2 = DB::table('timesheet_insentif')->where('timesheet_id', $req->edit)->delete();
            $id = DB::table('timesheets')
            ->insertGetId( array(
            'id' => $req->edit,
            'user_id' => Auth::user()->getId(),
            'periode' => $req->period,
            'month' => $req->month,
            'year' => $req->year,
            'week' => $req->week,
            'action' => $action
            ));
        } else {
            $id = DB::table('timesheets')
            ->insertGetId( array(
            'user_id' => Auth::user()->getId(),
            'periode' => $req->period,
            'month' => $req->month,
            'year' => $req->year,
            'week' => $req->week,
            'action' => $action
            ));
        }
        
        foreach ($req->timesheet as $key => $value) {
            // check if other is selected
            if($value['activity_other']){
                $activity = $value['activity_other'];
            } else {
                $activity = $value['activity'];
            }
            $timesheets[] = [
            'lokasi'=> $value['lokasi'],
            'activity'=> $activity,
            'date'=> $value['date'],
            'start_time'=> $value['start'],
            'end_time'=> $value['end'],
            'timesheet_id'=>  $id,
            'project_id'=> $value['project'],
        ];}
        $details = DB::table('timesheet_details')->insert($timesheets);
        if($req->trans!=null) {
        foreach ($req->trans as $key => $value) {
            $trans[] = [
            'date'=> $value['date'],
            'project_id'=> $value['project_id'],
            'value'=> $value['value'],
            'keterangan'=> $value['desc'],
        //    'end_time'=> $value['end'],
            'timesheet_id'=>  $id,
         //   'project_id'=> $value['project'],
        ];}
        DB::table('timesheet_transport')->insert($trans);
        }
        if($req->insentif!=null) {
        foreach ($req->insentif as $key => $value) {
            $ins[] = [
            'date'=> $value['date'],
            'project_id'=> $value['project_id'],
            'value'=> $value['value'],
            'keterangan'=> $value['desc'],
        //    'end_time'=> $value['end'],
            'timesheet_id'=>  $id,
         //   'project_id'=> $value['project'],
        ];}
        DB::table('timesheet_insentif')->insert($ins);
        }
        
        
        
        Flash::success('Holiday updated successfully.');  
//        return $deleted.$deleted1.$deleted2;                 
        return redirect(route('add_timesheet.index'));
       // return response()->json($timesheets);
       //  return $id;
    }

    public function getColumns()
    {
        //return response()->json(Timesheet::all());
       //return response()->json(DB::select(DB::raw('SELECT id,periode,week, MONTHNAME(STR_TO_DATE(month, "%m")) as month,year FROM `timesheets`')));
       //return response()->json(DB::table('timesheets')->select(['id', 'periode', 'week', 'month', 'year'])->get());
        $columns = ['id', 'periode', 'week', 'month', 'year'];
        if (Datatables::getRequest()->ajax()) {
            
            return Datatables::collection(Timesheet::of(['id', 'periode', 'week', 'month', 'year']))->make(true);
        }
        $html = Datatables::getHtmlBuilder()->columns($columns);
        return view('timesheets.history', compact('html'));
    }
    
}