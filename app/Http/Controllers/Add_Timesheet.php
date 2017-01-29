<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use App\Models\User;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use App\Models\TimesheetInsentif;
use App\Models\TimesheetTransport;
use App\Repositories\ApprovalHistoryRepository;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;
use Response;
use Yajra\Datatables\Facades\Datatables;
use File;
use Illuminate\Support\Collection;
use Request as RequestFacade;


class Add_Timesheet extends Controller
{

    /** @var  ApprovalHistoryRepository */
    private $approvalHistoryRepository;

    public function __construct(ApprovalHistoryRepository $approvalHistoryRepo)
    {
        $this->middleware('auth');
        $this->approvalHistoryRepository = $approvalHistoryRepo;
    }

    public function index()
    {
        $project = Project::pluck('project_name', 'id')->all();
        $nonlokal = array('DOMESTIK P. JAWA' => 'DOMESTIK P. JAWA', 'DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA', 'INTERNATIONAL' => 'INTERNATIONAL');
        $bantuan_perumahan = $this->getTunjanganPerumahan();
        return view('timesheets.add_timesheet', compact('project', 'nonlokal', 'bantuan_perumahan'));
    }

    public function getTunjanganPerumahan()
    {
        $bantuan_perumahan = DB::select(DB::raw('SELECT tunjangan_positions.internasional,tunjangan_positions.non_lokal,tunjangan_positions.luar_jawa FROM tunjangan_positions, users WHERE tunjangan_positions.position_id = users.position and tunjangan_positions.tunjangan_id = 1 and users.id = ' . Auth::user()->id));
        if (empty ($bantuan_perumahan)) {
            $bantuan_perumahan['non_lokal'] = 0;
            $bantuan_perumahan['luar_jawa'] = 0;
            $bantuan_perumahan['internasional'] = 0;
            return $bantuan_perumahan;

        } else {
            $bantuan_perumaan_daily = array();
            $bantuan_perumaan_daily['non_lokal'] = $bantuan_perumahan [0]->non_lokal / 20;
            $bantuan_perumaan_daily['luar_jawa'] = $bantuan_perumahan [0]->luar_jawa / 20;
            $bantuan_perumaan_daily['internasional'] = $bantuan_perumahan [0]->internasional / 20;
            return $bantuan_perumaan_daily;
        }
    }

    public function show($id)
    {

    $notes = DB::select(DB::raw("SELECT DATE_FORMAT(approval_histories.moderated_at, \"%d-%m-%Y %H:%i:%p\") as date,
                CASE 
                WHEN sequence_id=0 THEN 'PM'
                WHEN sequence_id=1 THEN 'PMO'
                WHEN sequence_id=2 THEN 'Finance'
                END approval,
                approval_histories.approval_id,approval_note FROM approval_histories,timesheets,timesheet_details where timesheet_details.timesheet_id = timesheets.id and (approval_histories.approval_status=2 or approval_histories.approval_status=5) and approval_histories.transaction_id = timesheet_details.id and timesheets.id = ".$id." group by approval_note"));
               // return response()->json(json_decode(json_encode($alert), true));

               //return Datatables::of(collect($alert))->make(true);
    $columns = ['date', 'approval', 'name', 'approval_note'];
    if (RequestFacade::ajax()) {
        return Datatables::of(collect($notes))->make(true);;
    }

        $html = Datatables::getHtmlBuilder()->columns($columns);

        $alert = DB::select(DB::raw("SELECT approval_note FROM approval_histories,timesheets,timesheet_details where timesheet_details.timesheet_id = timesheets.id and (approval_histories.approval_status=2 or approval_histories.approval_status=5) and approval_histories.transaction_id = timesheet_details.id and timesheets.id = ".$id." group by approval_note"));
        $lokasi = ['' => ''] + Constant::where('category', 'Location')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $activity = ['' => ''] + Constant::where('category', 'Activity')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $project = Project::pluck('project_name', 'id')->all();
        $timesheet = Timesheet::where('id', '=', $id)->first();
        $timesheet_details = TimesheetDetail::where('timesheet_id', '=', $id)->get();
        $timesheet_insentif = TimesheetInsentif::where('timesheet_id', '=', $id)->get();
        $sum_timesheet_insentif = 0;
        foreach ($timesheet_insentif as $g) {
            $sum_timesheet_insentif += $g->value;
        }
        //echo $sum;
        $timesheet_transport = TimesheetTransport::where('timesheet_id', '=', $id)->get();
        $sum_timesheet_transport = 0;
        foreach ($timesheet_transport as $g) {
            $sum_timesheet_transport += $g->value;
        }
        $nonlokal = array('DOMESTIK P. JAWA' => 'DOMESTIK P. JAWA', 'DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA', 'INTERNATIONAL' => 'INTERNATIONAL');
        $bantuan_perumahan = $this->getTunjanganPerumahan();
        //return response()->json($timesheet_transport);
        $summary = $this->populateSummary($id);
        return view('timesheets.edit_timesheet', compact('html','alert','lokasi', 'activity', 'timesheet', 'project', 'id', 'timesheet_details', 'timesheet_insentif', 'timesheet_transport', 'summary', 'nonlokal', 'bantuan_perumahan', 'sum_timesheet_insentif', 'sum_timesheet_transport'));
    }

    public function populateSummary($timesheet_id)
    {
        $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional FROM tunjangan_positions,tunjangans,positions,users WHERE tunjangan_positions.tunjangan_id = tunjangans.id and tunjangan_positions.position_id = positions.id and users.position = positions.id and users.id = ' . Auth::user()->id));
        //  $tunjangan_name = internasional;

        foreach ($tunjangans as $t) {
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }

        $mandays = DB::select(DB::raw("SELECT lokasi , count(*)total FROM `timesheet_details` where timesheet_id = " . $timesheet_id . " and selected = 1 group by lokasi"));
        foreach ($mandays as $m) {
            if ($m->lokasi === "JABODETABEK") {
                $summary['lokal']['count'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['lokal'] as $key => $value) {
                        $summary['lokal'][$key] = $value * $m->total;
                        //  echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }

            } else if ($m->lokasi === "DOMESTIK L. JAWA") {
                $summary['luar_jawa']['count'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['luar_jawa'] as $key => $value) {
                        $summary['luar_jawa'][$key] = $value * $m->total;
                        //  echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
            } else if ($m->lokasi === "DOMESTIK P. JAWA") {
                $summary['non_lokal']['count'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['non_lokal'] as $key => $value) {
                        $summary['non_lokal'][$key] = $value * $m->total;
                        //   echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
            } else if ($m->lokasi === "INTERNATIONAL") {
                $summary['internasional']['count'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['internasional'] as $key => $value) {
                        $summary['internasional'][$key] = $value * $m->total;
                        //   echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
            }
            // echo $m->lokasi;
        }

        if (!isset($summary['lokal']['count'])) {
            $summary['lokal']['count'] = 0;
        }

        if (!isset($summary['lokal']['Transport Lokal'])) {
            $summary['lokal']['Transport Lokal'] = 0;
        }
        if (!isset($summary['lokal']['Transport Luar Kota'])) {
            $summary['lokal']['Transport Luar Kota'] = 0;
        }
        if (!isset($summary['lokal']['Insentif Project'])) {
            $summary['lokal']['Insentif Project'] = 0;
        }

        if (!isset($summary['luar_jawa']['count'])) {
            $summary['luar_jawa']['count'] = 0;
        }

        if (!isset($summary['luar_jawa']['Transport Lokal'])) {
            $summary['luar_jawa']['Transport Lokal'] = 0;
        }
        if (!isset($summary['luar_jawa']['Transport Luar Kota'])) {
            $summary['luar_jawa']['Transport Luar Kota'] = 0;
        }
        if (!isset($summary['luar_jawa']['Insentif Project'])) {
            $summary['luar_jawa']['Insentif Project'] = 0;
        }

        if (!isset($summary['non_lokal']['count'])) {
            $summary['non_lokal']['count'] = 0;
        }

        if (!isset($summary['non_lokal']['Transport Lokal'])) {
            $summary['non_lokal']['Transport Lokal'] = 0;
        }
        if (!isset($summary['non_lokal']['Transport Luar Kota'])) {
            $summary['non_lokal']['Transport Luar Kota'] = 0;
        }
        if (!isset($summary['non_lokal']['Insentif Project'])) {
            $summary['non_lokal']['Insentif Project'] = 0;
        }

        if (!isset($summary['internasional']['count'])) {
            $summary['internasional']['count'] = 0;
        }

        if (!isset($summary['internasional']['Transport Lokal'])) {
            $summary['internasional']['Transport Lokal'] = 0;
        }
        if (!isset($summary['internasional']['Transport Luar Kota'])) {
            $summary['internasional']['Transport Luar Kota'] = 0;
        }
        if (!isset($summary['internasional']['Insentif Project'])) {
            $summary['internasional']['Insentif Project'] = 0;
        }

        return $summary;
    }

    public function form(Request $req)
    {
        $ts = $this->queryTimesheetId($req->week, $req->month, $req->year);
        if ($ts != '') {
            return redirect()->route('add_timesheet.show', ['id' => $ts]);
        }

        $lokasi = ['' => ''] + Constant::where('category', 'Location')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $activity = ['' => ''] + Constant::where('category', 'Activity')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $project = Project::pluck('project_name', 'id')->all();
        $nonlokal = array('DOMESTIK P. JAWA' => 'DOMESTIK P. JAWA', 'DOMESTIK L. JAWA' => 'DOMESTIK L. JAWA', 'INTERNATIONAL' => 'INTERNATIONAL');
        $bantuan_perumahan = $this->getTunjanganPerumahan();
        return view('timesheets.add_timesheet', compact('project', 'lokasi', 'activity', 'nonlokal', 'bantuan_perumahan'));
    }

    public function queryTimesheetId($week, $month, $year)
    {
        //add only user
        $userId = Auth::User()->id;

        $timesheets = DB::table('timesheets')
            ->where('week', $week)
            ->where('month', $month)
            ->where('year', $year)
            ->where('user_id', $userId)->get();
        if (count($timesheets) > 0) {
            return $timesheets[0]->id;
        } else {
            return '';
        }
    }

    public function create(Request $req)
    {
        //return response()->json($req);
        $action = $req->action == 'Save' ? 'Disimpan' : 'Terkirim';
        $approval_status = $req->action == 'Save' ? '0' : '1';
        if (isset($req->edit)) {
            //   return 'hoya';
            $deleted = DB::table('timesheets')->where('id', $req->edit)->delete();
            $deleted1 = DB::table('timesheet_transport')->where('timesheet_id', $req->edit)->delete();
            $deleted2 = DB::table('timesheet_insentif')->where('timesheet_id', $req->edit)->delete();
            $deleted4 = DB::table('timesheet_details')->where('timesheet_id', $req->edit)->delete();
            $id = DB::table('timesheets')
                ->insertGetId(array(
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
                ->insertGetId(array(
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
                    'lokasi' => $value['lokasi'],
                    'activity' => $value['activity'],
                    'date' => $value['date'],
                    'start_time' => $value['start'],
                    'end_time' => $value['end'],
                    'timesheet_id' => $id,
                    'project_id' => $value['project'],
                    'selected' => isset($value['select']) ? 1 : 0,
                    'activity_detail' => $value['activity_other'],
                    'approval_status' => $approval_status
                ] + (isset($value['id']) ? array('id' => $value['id']) : array());
        }
        //return response()->json($timesheets);
        $details = DB::table('timesheet_details')->insert($timesheets);
        if ($req->trans != null) {
            foreach ($req->trans as $key => $value) {
                $trans[] = [
                        'date' => $value['date'],
                        'project_id' => $value['project_id'],
                        'value' => $value['value'],
                        'file' => $value['file'],
                        'keterangan' => $value['desc'],
                        //    'end_time'=> $value['end'],
                        'timesheet_id' => $id,
                        'status' => $approval_status
                        //   'project_id'=> $value['project'],
                    ];// + (isset($value['id']) ? array('id' => $value['id']) : array());
            }
            DB::table('timesheet_transport')->insert($trans);
        }
        if ($req->insentif != null) {
            foreach ($req->insentif as $key => $value) {
                $ins[] = [
                        'date' => $value['date'],
                        'project_id' => $value['project_id'],
                        'value' => $value['value'],
                        'keterangan' => $value['desc'],
                        'lokasi' => $value['lokasi'],
                        'timesheet_id' => $id,
                        'status' => $approval_status
                        //   'project_id'=> $value['project'],
                    ] ;//+ (isset($value['id']) ? array('id' => $value['id']) : array());
            }
            DB::table('timesheet_insentif')->insert($ins);
        }

        //create approval history
        if ($action == 'Terkirim') {
            $this->createApprovalHistory($id);
        }

        Flash::success('Timesheet updated successfully.');
//        return $deleted.$deleted1.$deleted2;
        return redirect(route('timesheets.index'));
        // return response()->json($timesheets);
        //  return $id;
    }

    public function createApprovalHistory($timesheetId)
    {
        $timesheetDetail = DB::table('timesheet_details')
            ->where('timesheet_id', '=', $timesheetId)
            ->where('approval_status', '=', 1)
            ->where('selected', '=', '1')
            ->whereNotIn('id', function($q){
            $q->select('transaction_id')->from('approval_histories')
            ->where('sequence_id', '=', '2')
            ->where('approval_status', '=', '1')
            ->where('approval_status', '=', '4');
            })
            ->get();

        $timesheetInsentif = DB::table('timesheet_insentif')
            ->where('timesheet_id', '=', $timesheetId)
            ->where('status','=',1)
            //paid and approve finance in approval history not in
            ->whereNotIn('id', function($q){
            $q->select('transaction_id')->from('approval_histories')
            ->where('sequence_id', '=', '2')
            ->where('approval_status', '=', '1')
            ->where('approval_status', '=', '4');
            })
            ->get();
            $timesheetTransport = DB::table('timesheet_transport')
            ->where('timesheet_id', '=', $timesheetId)
            ->where('status','=',1)
            //paid and approve finance in approval history not in
            ->whereNotIn('id', function($q){
            $q->select('transaction_id')->from('approval_histories')
            ->where('sequence_id', '=', '2')
            ->where('approval_status', '=', '1')
            ->where('approval_status', '=', '4');
            })
            ->get();

        $user = Auth::user()->id;


        foreach ($timesheetDetail as $td) {
            $project = DB::table('projects')
                ->where('id', '=', $td->project_id)->first();

            $approval = User::where('id', '=', $project->pm_user_id)->first();

            $detailExist = $this->isApprovalHistoryExist($td->id, 2, $user, $approval);

            if (!is_null($detailExist)) {
                if($detailExist->approval_status != 1) {
                    $detail = $this->updateApprovalHistory($detailExist->id, $td->date, $td->activity, $td->id, 2, $user, $approval['id']);
                }
            } else {
                $detail = $this->insertApprovalHistory($td->date, $td->activity, $td->id, 2, $user, $approval['id']);
            }
        }

        foreach ($timesheetInsentif as $ti) {
            $project = DB::table('projects')
                ->where('id', '=', $ti->project_id)->first();

            $approval = User::where('id', '=', $project->pm_user_id)->first();

            $insentifExist = $this->isApprovalHistoryExist($ti->id, 4, $user, $approval);

            if (!is_null($insentifExist)) {
                if($insentifExist->approval_status != 1) {
                    $insentif = $this->updateApprovalHistory($insentifExist->id, $ti->date, $ti->keterangan, $ti->id, 4, $user, $approval['id']);
                }
            } else {
                $insentif = $this->insertApprovalHistory($ti->date, $ti->keterangan, $ti->id, 4, $user, $approval['id']);
            }
        }

        foreach ($timesheetTransport as $tt) {
            $project = DB::table('projects')
                ->where('id', '=', $tt->project_id)->first();

            $approval = User::where('id', '=', $project->pm_user_id)->first();

            $transportExist = $this->isApprovalHistoryExist($tt->id, 3, $user, $approval);

            if (!is_null($transportExist)) {
                if($transportExist->approval_status != 1) {
                    $transport = $this->updateApprovalHistory($transportExist->id, $tt->date, $tt->keterangan, $tt->id, 3, $user, $approval['id']);
                }
            } else {
                $transport = $this->insertApprovalHistory($tt->date, $tt->keterangan, $tt->id, 3, $user, $approval['id']);
            }
        }
    }

    function isApprovalHistoryExist($transactionId, $transactionType, $user, $approval)
    {
        $transactionExist = DB::table('approval_histories')
            ->select('id','approval_status')
            ->where('transaction_id', '=', $transactionId)
            ->where('transaction_type', '=', $transactionType)
            ->where('user_id', '=', $user)

            ->where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->first();
        return $transactionExist;
    }

    function insertApprovalHistory($date, $note, $transactionId, $transactionType, $user, $approvalId)
    {
        $saveTransaction = DB::table('approval_histories')
            ->insertGetId(array(
                'date' => $date,
                'note' => $note,
                'sequence_id' => 0,
                'transaction_id' => $transactionId,
                'transaction_type' => $transactionType,
                'approval_status' => 0,
                'user_id' => $user,
                'approval_id' => $approvalId,
                'group_approval_id' => 0
            ));
        return $saveTransaction;
    }

    function updateApprovalHistory($id, $date, $note, $transactionId, $transactionType, $user, $approvalId)
    {
        $updateDetail = DB::table('approval_histories')
            ->where('id', $id)
            ->update(array(
                'date' => $date,
                'note' => $note,
                'sequence_id' => 0,
                'transaction_id' => $transactionId,
                'transaction_type' => $transactionType,
                'approval_status' => 0,
                'user_id' => $user,
                'approval_id' => $approvalId,
                'group_approval_id' => 0
            ));

        return $updateDetail;
    }

    public function getColumns()
    {
        $test = count(DB::select(DB::raw('SELECT * FROM `timesheet_details` WHERE timesheet_id=12 and selected=1')));
        
                $alert = DB::select(DB::raw("SELECT DATE_FORMAT(approval_histories.moderated_at, \"%d-%m-%Y %H:%i:%p\") as date,
                CASE 
                WHEN sequence_id=0 THEN 'PM'
                WHEN sequence_id=1 THEN 'PMO'
                WHEN sequence_id=2 THEN 'Finance'
                END approval,
                approval_histories.approval_id,users.name,approval_note FROM approval_histories,timesheets,timesheet_details,users where users.id = approval_histories.approval_id and timesheet_details.timesheet_id = timesheets.id and approval_histories.approval_status=2 or approval_histories.approval_status=5 and timesheet_details.timesheet_id = timesheet_details.id and timesheets.id = 7 group by approval_note"));
               // return response()->json(json_decode(json_encode($alert), true));
                
               return Datatables::of(collect($alert))->make(true);
    }

    public function getColor($status)
    {
        if ($status == "Approved") {
            return 'color:#00a65a';
        } else if ($status == "Rejected") {
            return 'color:#dd4b39';
        } else {
            return 'color:orange';
        }
    }

    public function postUploadImageFile(Request $request)
    {
        // $validator = Validator::make($request->all(),
        //     [
        //         'file' => 'image',
        //     ],
        //     [
        //         'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
        //     ]);
        // if ($validator->fails())
        //     return array(
        //         'fail' => true,
        //         'errors' => $validator->getMessageBag()->toArray()
        //     );
        $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
        $dir = 'upload/';
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $request->file('file')->move($dir, $filename);
        return $filename;
    }

    public function getRemoveImageFile($filename)
    {
        File::delete('upload/' . $filename);
    }

    public function downloadFile($filename){
        return response()->download(public_path('upload/'.$filename));
    }
}