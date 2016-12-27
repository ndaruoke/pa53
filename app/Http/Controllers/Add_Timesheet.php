<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use App\Models\TimesheetTransport;
use App\Models\TimesheetInsentif;
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
        $nonlokal = array('DOMESTIK P. JAWA'=>'DOMESTIK P. JAWA','DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA','INTERNATIONAL'=>'INTERNATIONAL');
        $bantuan_perumahan = $this->getTunjanganPerumahan();
        return view('timesheets.add_timesheet',compact('project','nonlokal','bantuan_perumahan'));
    }

    public function show($id)
    {
        $lokasi = array('JABODETABEK'=>'JABODETABEK','DOMESTIK P. JAWA'=>'DOMESTIK P. JAWA','DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA','INTERNATIONAL'=>'INTERNATIONAL','UNCLAIMABLE'=>'UNCLAIMABLE');
        $activity = array('CUTI'=>'CUTI','LIBUR'=>'LIBUR','IDLE' => 'IDLE','SAKIT' => 'SAKIT','SUPPORT' => 'SUPPORT','IMPLEMENTASI' => 'IMPLEMENTASI','MANAGED OPERATION' => 'MANAGED OPERATION');
        $project = Project::pluck('project_name', 'id')->all();
        $timesheet = Timesheet::where('id','=',$id)->first();
        $timesheet_details = TimesheetDetail::where('timesheet_id','=',$id)->get();
        $timesheet_insentif = TimesheetInsentif::where('timesheet_id','=',$id)->get();
        $sum_timesheet_insentif = 0;
        foreach($timesheet_insentif as $g)
        {
        $sum_timesheet_insentif+= $g->value;
        }
        //echo $sum;
        $timesheet_transport = TimesheetTransport::where('timesheet_id','=',$id)->get();
        $sum_timesheet_transport = 0;
        foreach($timesheet_transport as $g)
        {
        $sum_timesheet_transport+= $g->value;
        }
        $nonlokal = array('DOMESTIK P. JAWA'=>'DOMESTIK P. JAWA','DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA','INTERNATIONAL'=>'INTERNATIONAL');
        $bantuan_perumahan = $this->getTunjanganPerumahan();
        //return response()->json($timesheet_transport);
        $summary = $this->populateSummary($id);
        return view('timesheets.edit_timesheet',compact('lokasi','activity','timesheet','project','id','timesheet_details','timesheet_insentif','timesheet_transport','summary','nonlokal','bantuan_perumahan','sum_timesheet_insentif','sum_timesheet_transport'));
    }
    
    public function form()
    {   $lokasi = array('JABODETABEK'=>'JABODETABEK','DOMESTIK P. JAWA'=>'DOMESTIK P. JAWA','DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA','INTERNATIONAL'=>'INTERNATIONAL','UNCLAIMABLE'=>'UNCLAIMABLE');
        $activity = array('CUTI'=>'CUTI','LIBUR'=>'LIBUR','IDLE' => 'IDLE','SAKIT' => 'SAKIT','SUPPORT' => 'SUPPORT','IMPLEMENTASI' => 'IMPLEMENTASI','MANAGED OPERATION' => 'MANAGED OPERATION');
        $project = Project::pluck('project_name', 'id')->all();
        $nonlokal = array('DOMESTIK P. JAWA'=>'DOMESTIK P. JAWA','DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA','INTERNATIONAL'=>'INTERNATIONAL');
        $bantuan_perumahan = $this->getTunjanganPerumahan();
        return view('timesheets.add_timesheet',compact('project','lokasi','activity','nonlokal','bantuan_perumahan'));
    }
    
    public function create(Request $req)
    {
        //return response()->json($req);
        $action = $req->action == 'Save' ? 'Disimpan' : 'Terkirim';
        $approval_status = $req->action == 'Save' ? '0' : '1';
        if(isset($req->edit)){
         //   return 'hoya';
            $deleted = DB::table('timesheets')->where('id', $req->edit)->delete();
            $deleted1 = DB::table('timesheet_transport')->where('timesheet_id', $req->edit)->delete();
            $deleted2 = DB::table('timesheet_insentif')->where('timesheet_id', $req->edit)->delete();
            $deleted4 = DB::table('timesheet_details')->where('timesheet_id', $req->edit)->delete();
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
            $timesheets[] = [
            'lokasi'=> $value['lokasi'],
            'activity'=> $value['activity'],
            'date'=> $value['date'],
            'start_time'=> $value['start'],
            'end_time'=> $value['end'],
            'timesheet_id'=>  $id,
            'project_id'=> $value['project'],
            'selected' => isset($value['select']) ? 1 : 0,
            'activity_detail' => $value['activity_other'],
            'approval_status' => $approval_status
        ]    + (isset($value['id']) ? array('id' => $value['id']) : array());
        }
        //return response()->json($timesheets);
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
        ]+ (isset($value['id']) ? array('id' => $value['id']) : array());}
        DB::table('timesheet_transport')->insert($trans);
        }
        if($req->insentif!=null) {
        foreach ($req->insentif as $key => $value) {
            $ins[] = [
            'date'=> $value['date'],
            'project_id'=> $value['project_id'],
            'value'=> $value['value'],
            'keterangan'=> $value['desc'],
            'lokasi'=> $value['lokasi'],
            'timesheet_id'=>  $id,
         //   'project_id'=> $value['project'],
        ]+ (isset($value['id']) ? array('id' => $value['id']) : array());}
        DB::table('timesheet_insentif')->insert($ins);
        }
        
        
        
        Flash::success('Timesheet updated successfully.');  
//        return $deleted.$deleted1.$deleted2;                 
        return redirect(route('timesheets.index'));
       // return response()->json($timesheets);
       //  return $id;
    }

        function search($array, $key, $value)
        {
            $results = array();

            if (is_array($array)) {
                if (isset($array[$key]) && $array[$key] == $value) {
                    $results[] = $array;
                }

                foreach ($array as $subarray) {
                    $results = array_merge($results, $this->search($subarray, $key, $value));
                }
            }

            return $results;
        }

    public function populateSummary($timesheet_id){
         $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional FROM tunjangan_positions,tunjangans,positions,users WHERE tunjangan_positions.tunjangan_id = tunjangans.id and tunjangan_positions.position_id = positions.id and users.position = positions.id and users.id = '.Auth::user()->id));
      //  $tunjangan_name = internasional;
        
        foreach ($tunjangans as $t){
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }

        $mandays = DB::select(DB::raw("SELECT lokasi , count(*)total FROM `timesheet_details` where timesheet_id = ".$timesheet_id." and selected = 1 group by lokasi"));
        foreach($mandays as $m)
        {
            if($m->lokasi === "JABODETABEK"){
                $summary['lokal']['count'] = $m->total;
                if ( !empty ( $arr ) ) {
                    foreach ($arr['lokal'] as $key => $value){
                    $summary['lokal'][$key] = $value*$m->total;
                  //  echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
                
            } else if ($m->lokasi === "DOMESTIK L. JAWA"){
                $summary['luar_jawa']['count'] = $m->total;
                if ( !empty ( $arr ) ) {
                    foreach ($arr['luar_jawa'] as $key => $value){
                        $summary['luar_jawa'][$key] = $value*$m->total;
                    //  echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
            } else if ($m->lokasi === "DOMESTIK P. JAWA"){
               $summary['non_lokal']['count'] = $m->total;
               if ( !empty ( $arr ) ) {
                    foreach ($arr['non_lokal'] as $key => $value){
                        $summary['non_lokal'][$key] = $value*$m->total;
                    //   echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
               }
            } else if ($m->lokasi === "INTERNATIONAL"){
                $summary['internasional']['count'] = $m->total;
                if ( !empty ( $arr ) ) {
                    foreach ($arr['internasional'] as $key => $value){
                        $summary['internasional'][$key] = $value*$m->total;
                    //   echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
            }
           // echo $m->lokasi;
        }

        if(!isset($summary['lokal']['count'])){
            $summary['lokal']['count'] = 0;
        }

        if(!isset($summary['lokal']['Transport Lokal'])){
            $summary['lokal']['Transport Lokal'] = 0;
        }
        if(!isset($summary['lokal']['Transport Luar Kota'])){
            $summary['lokal']['Transport Luar Kota'] = 0;
        }
        if(!isset($summary['lokal']['Insentif Project'])){
            $summary['lokal']['Insentif Project'] = 0;
        }

        if(!isset($summary['luar_jawa']['count'])){
            $summary['luar_jawa']['count'] = 0;
        }
    
        if(!isset($summary['luar_jawa']['Transport Lokal'])){
            $summary['luar_jawa']['Transport Lokal'] = 0;
        }
        if(!isset($summary['luar_jawa']['Transport Luar Kota'])){
            $summary['luar_jawa']['Transport Luar Kota'] = 0;
        }
        if(!isset($summary['luar_jawa']['Insentif Project'])){
            $summary['luar_jawa']['Insentif Project'] = 0;
        }

        if(!isset($summary['non_lokal']['count'])){
            $summary['non_lokal']['count'] = 0;
        }

        if(!isset($summary['non_lokal']['Transport Lokal'])){
            $summary['non_lokal']['Transport Lokal'] = 0;
        }
        if(!isset($summary['non_lokal']['Transport Luar Kota'])){
            $summary['non_lokal']['Transport Luar Kota'] = 0;
        }
        if(!isset($summary['non_lokal']['Insentif Project'])){
            $summary['non_lokal']['Insentif Project'] = 0;
        }

        if(!isset($summary['internasional']['count'])){
            $summary['internasional']['count'] = 0;
        }

        if(!isset($summary['internasional']['Transport Lokal'])){
            $summary['internasional']['Transport Lokal'] = 0;
        }
        if(!isset($summary['internasional']['Transport Luar Kota'])){
            $summary['internasional']['Transport Luar Kota'] = 0;
        }
        if(!isset($summary['internasional']['Insentif Project'])){
            $summary['internasional']['Insentif Project'] = 0;
        }

       return $summary ;
    }    
    public function getColumns()
    {   
   // return response()->json( $this->getTunjanganPerumahan());

//return response()->json( TimesheetDetail::where('timesheet_id','=',1)->get());

 return response()->json(  $status);
       return Auth::user()->id;
       return response()->json( $this->populateSummary());
       // return response()->json(Timesheet::all());
       //return response()->json(DB::select(DB::raw('SELECT id,periode,week, MONTHNAME(STR_TO_DATE(month, "%m")) as month,year FROM `timesheets`')));
       //return response()->json(DB::table('timesheets')->select(['id', 'periode', 'week', 'month', 'year'])->get());
        $columns = ['id', 'periode', 'week', 'month', 'year'];
        if (Datatables::getRequest()->ajax()) {
            
            return Datatables::collection(Timesheet::of(['id', 'periode', 'week', 'month', 'year']))->make(true);
        }
        $html = Datatables::getHtmlBuilder()->columns($columns);
        return view('timesheets.history', compact('html'));
    }
    
    public function getTunjanganPerumahan(){
        $bantuan_perumahan = DB::select(DB::raw('SELECT tunjangan_positions.internasional,tunjangan_positions.non_lokal,tunjangan_positions.luar_jawa FROM tunjangan_positions, users WHERE tunjangan_positions.position_id = users.position and tunjangan_positions.tunjangan_id = 1 and users.id = '.Auth::user()->id));
        if(empty ($bantuan_perumahan)){
            $bantuan_perumahan['non_lokal'] = 0;
            $bantuan_perumahan['luar_jawa'] = 0;
            $bantuan_perumahan['internasional'] = 0;
                    return $bantuan_perumahan;

        } else {
            $bantuan_perumaan_daily = array();
            $bantuan_perumaan_daily['non_lokal'] = $bantuan_perumahan [0]->non_lokal/20;
            $bantuan_perumaan_daily['luar_jawa'] = $bantuan_perumahan [0]->luar_jawa/20;
            $bantuan_perumaan_daily['internasional'] = $bantuan_perumahan [0]->internasional/20;
           return $bantuan_perumaan_daily;
        }
    }
public function getColor($status){
        if($status=="Approved"){
            return 'color:#00a65a';
        }else if($status=="Rejected"){
            return 'color:#dd4b39';
        }
        else{
            return 'color:orange';
        }
    }
}