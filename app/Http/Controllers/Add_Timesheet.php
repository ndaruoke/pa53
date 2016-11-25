<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models\Project;
use Response;
use DB;
use Flash;

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
    
    public function form()
    {
        $project = Project::pluck('project_name', 'id')->all();
        return view('timesheets.add_timesheet',compact('project'));
    }
    
    public function create(Request $req)
    {
        $id = DB::table('timesheets')
        ->insertGetId( array(
        'user_id' => Auth::user()->getId(),
        'periode' => $req->period,
        'month' => $req->month,
        'year' => $req->year,
        'week' => $req->week
        ));
        foreach ($req->timesheet as $key => $value) {
            $timesheets[] = [
            'lokasi'=> $value['lokasi'],
            'activity'=> $value['activity'],
            'date'=> $value['date'],
            'start_time'=> $value['start'],
            'end_time'=> $value['end'],
            'timesheet_id'=>  $id,
            'project_id'=> $value['project'],
        ];}

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
        $details = DB::table('timesheet_details')->insert($timesheets);
        
        
        Flash::success('Holiday updated successfully.');                   
        return redirect(route('add_timesheet.index'));
       // return response()->json($timesheets);
       //  return $id;
    }
    
}