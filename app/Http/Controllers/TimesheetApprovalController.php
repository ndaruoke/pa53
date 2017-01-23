<?php

namespace App\Http\Controllers;

use App\DataTables\ModerationTimesheetDataTable;
use App\DataTables\TimesheetDataTable;
use App\Http\Requests\CreateTimesheetRequest;
use App\Models\ApprovalHistory;
use App\Models\Constant;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use App\Models\TimesheetTransport;
use App\Models\TimesheetInsentif;
use App\Models\User;
use App\Repositories\TimesheetDetailRepository;
use App\Repositories\TimesheetRepository;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;
use Response;
use Carbon;

class TimesheetApprovalController extends AppBaseController
{
    /** @var  TimesheetRepository */
    private $timesheetRepository;
    private $timesheetDetailRepository;

    public function __construct(TimesheetRepository $timesheetRepo, TimesheetDetailRepository $timesheetDetailRepo)
    {
        $this->middleware('auth');
        $this->timesheetRepository = $timesheetRepo;
        $this->timesheetDetailRepository = $timesheetDetailRepo;
    }

    /**
     * Display a listing of the Timesheet.
     *
     * @param TimesheetDataTable $timesheetDataTable
     * @return Response
     */
    public function moderation(ModerationTimesheetDataTable $moderationTimesheetDataTable)
    {
        $user = Auth::user();
        if (empty($_REQUEST['approvalStatus'])) {
            $status = 0;
        } else {
            $status = $_REQUEST['approvalStatus'];
        }
        $approvalStatus = ['' => ''] + Constant::where('category', 'Moderation')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        return $moderationTimesheetDataTable->render('timesheets.moderation', array('user' => $user, 'approvalStatus' => $approvalStatus, 'status' => $status));
    }

    /**
     * Display the specified Timesheet.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function moderationShow($id)
    {
        $timesheet = $this->timesheetRepository->with('users')->with('timesheetdetails')->with('timesheetinsentifs')->with('timesheettransports')->findWithoutFail($id);

        if (empty($timesheet)) {
            Flash::error('Timesheet not found');

            return redirect(route('timesheets.moderation'));
        }

        return view('timesheets.moderation_show')->with('timesheet', $timesheet);
    }

    /**
     * Edit the specified Timesheet.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function moderationEdit(Request $request)
    {
        $approvalId = $request->approvalId;
        $userId = $request->userId;
        $approvalStatus = $request->approvalStatus;

        $user = User::where('id', '=', $userId)->select('id')->first();
        $approval = User::where('id', '=', $approvalId)->select('id','role')->first();

        if($approval==null)
        {
            $approval = Auth::User();
        }

        $lokasi = ['' => ''] + Constant::where('category', 'Location')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $activity = ['' => ''] + Constant::where('category', 'Activity')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $project = Project::pluck('project_name', 'id')->all();
        $timesheets = Timesheet::where('approval_id', '=', $approvalId)->where('user_id', '=', $userId)->get();
        $timesheet_details = TimesheetDetail::
        select(DB::raw('DATE_FORMAT(timesheet_details.date, \'%d-%m-%Y\') as id_date, timesheet_details.date,timesheet_details.id,timesheet_details.activity,
            timesheet_details.approval_status,timesheet_details.project_id, timesheet_details.start_time,timesheet_details.end_time,
            timesheet_details.lokasi,timesheet_details.activity_detail,approval_histories.transaction_id, approval_histories.approval_status as approval_status_history'))->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')->
        where('approval_histories.user_id', '=', $user['id'])->
        where('approval_histories.approval_status', '=', $approvalStatus)->
        where('approval_histories.transaction_type', '=', 2)->
        where(function ($query) use ($approval) {
            $query->where('approval_histories.approval_id', '=', $approval['id'])
                ->orWhere('approval_histories.group_approval_id', '=', $approval['role']);
        })->
        where('selected', '=', '1')->
        get();

        $timesheet_insentif = TimesheetInsentif::
        select(DB::raw('DATE_FORMAT(timesheet_insentif.date, \'%d-%m-%Y\') as id_date, timesheet_insentif.date,timesheet_insentif.id,timesheet_insentif.keterangan,
            timesheet_insentif.status,timesheet_insentif.value,timesheet_insentif.project_id, approval_histories.transaction_id, approval_histories.approval_status'))->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_insentif.id')->
        where('approval_histories.user_id', '=', $user['id'])->
        where('approval_histories.approval_status', '=', $approvalStatus)->
        where('approval_histories.transaction_type', '=', 4)->
        where(function ($query) use ($approval) {
            $query->where('approval_histories.approval_id', '=', $approval['id'])
                ->orWhere('approval_histories.group_approval_id', '=', $approval['role']);
        })->
        get();

        $timesheet_transport = TimesheetTransport::
        select(DB::raw('DATE_FORMAT(timesheet_transport.date, \'%d-%m-%Y\') as id_date, timesheet_transport.date,timesheet_transport.id,timesheet_transport.keterangan,
            timesheet_transport.status,timesheet_transport.value,timesheet_transport.project_id, timesheet_transport.file, approval_histories.transaction_id, approval_histories.approval_status'))->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_transport.id')->
        where('approval_histories.user_id', '=', $user['id'])->
        where('approval_histories.approval_status', '=', $approvalStatus)->
        where('approval_histories.transaction_type', '=', 3)->
        where(function ($query) use ($approval) {
            $query->where('approval_histories.approval_id', '=', $approval['id'])
                ->orWhere('approval_histories.group_approval_id', '=', $approval['role']);
        })->
        get();

        $summary = $this->populateSummary($timesheets, $user, $approval, $approvalStatus, $timesheet_insentif, $timesheet_transport);

        $projectbudget = $this->getProjectBudget($user, $approval, $timesheet_details, $timesheet_insentif, $timesheet_transport);

        if (empty($timesheets)) {
            Flash::error('Timesheet not found');

            return redirect(route('leaves.moderation'));
        }

        return view('timesheets.moderation_edit', compact('approvalStatus', 'user', 'userId', 'lokasi', 'activity', 'timesheets', 'project', 'timesheet_details', 'timesheet_insentif', 'timesheet_transport', 'summary', 'approval'));
    }

    public function populateSummary($timesheets, $user, $approval, $approvalStatus, $timesheet_insentif, $timesheet_transport)
    {
        $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional 
            FROM tunjangan_positions,tunjangans,positions,users 
            WHERE tunjangan_positions.tunjangan_id = tunjangans.id and tunjangan_positions.position_id = positions.id 
            and users.position = positions.id and users.id = ' . $user->id));

        foreach ($tunjangans as $t) {
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }

        $summary['total'] = 0;

        //TODO without insentif
        if (!empty($timesheet_insentif)) {
            foreach ($timesheet_insentif as $adcost) {
                $summary['total'] += $adcost->value;
            }
        }

        $summary['perumahan']['total'] = $timesheet_insentif->pluck('value')->sum();
        $summary['perumahan']['count'] = $timesheet_insentif->count();

        //TODO without transport
        if (!empty($timesheet_transport)) {
            foreach ($timesheet_transport as $transport) {
                $summary['total'] += $transport->value;
            }
        }

        $summary['adcost']['total'] = $timesheet_transport->pluck('value')->sum();
        $summary['adcost']['count'] = $timesheet_transport->count();

        /**
         * $mandays = DB::table('timesheet_details')
         * ->join('approval_histories','approval_histories.transaction_id','timesheet_details.timesheet_id')
         * ->select(DB::raw('count(*) as total, lokasi'))
         * ->where('user_id', '=',$user['id'])
         * ->where('approval_histories.approval_status','=',$approvalStatus)
         * ->where('transaction_type','=', 2)
         * ->where(function ($query) use($approval){
         * $query->where('approval_histories.approval_id','=',$approval->id)
         * ->orWhere('approval_histories.group_approval_id','=', $approval->role);
         * })
         * ->groupBy('lokasi')
         * ->get();
         **/
        $mandays = DB::select(DB::raw("SELECT lokasi , count(*)total FROM `timesheet_details` 
        JOIN timesheets ON timesheets.id = timesheet_details.timesheet_id
        JOIN approval_histories ON approval_histories.transaction_id = timesheet_details.id
        where approval_histories.user_id = " . $user['id'] . " 
        and (approval_histories.approval_id = " . $approval['id'] . " or approval_histories.group_approval_id = " . $approval['role'] . ")
        and approval_histories.approval_status = " . $approvalStatus . " 
        and approval_histories.transaction_type = 2
        and selected = 1 group by lokasi"));


        foreach ($mandays as $m) {
            if ($m->lokasi === "JABODETABEK") {
                $summary['lokal']['count'] = $m->total;
                if (!empty ($arr)) {
                    if ($arr['lokal'] != null) {
                        foreach ($arr['lokal'] as $key => $value) {
                            $summary['lokal'][$key] = $value * $m->total;
                        }
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

        $summary['total'] +=
            //$summary['lokal']['count'] +
            $summary['lokal']['Transport Lokal'] +
            $summary['lokal']['Transport Luar Kota'] +
            $summary['lokal']['Insentif Project'] +
            //$summary['luar_jawa']['count'] +
            $summary['luar_jawa']['Transport Lokal'] +
            $summary['luar_jawa']['Transport Luar Kota'] +
            $summary['luar_jawa']['Insentif Project'] +
            //$summary['non_lokal']['count'] +
            $summary['non_lokal']['Transport Lokal'] +
            $summary['non_lokal']['Transport Luar Kota'] +
            $summary['non_lokal']['Insentif Project'] +
            //$summary['internasional']['count'] +
            $summary['internasional']['Transport Lokal'] +
            $summary['internasional']['Transport Luar Kota'] +
            $summary['internasional']['Insentif Project'];

        return $summary;
    }

    /**
     * Approve Timesheet in storage.
     *
     * @param CreateTimesheetRequest $request
     *
     * @return Response
     */
    public function moderationUpdate(Request $request)
    {
        $userId = $request->userId;
        $approval = Auth::user();

        foreach ($request->timesheetdetail as $key => $value) {
            if (!empty($value['choose'])) $timesheetDetailId[] = ['id' => $value['transaction_id']];
        }

        if (!empty($request->trans)) {
            foreach ($request->trans as $key => $value) {
                if (!empty($value['choose'])) $transId[] = ['id' => $value['transaction_id']];
            }
        }

        if (!empty($request->insentif)) {
            foreach ($request->insentif as $key => $value) {
                if (!empty($value['choose'])) $insId[] = ['id' => $value['transaction_id']];
            }
        }


        if ($request->moderation == 1) //approve
        {

            //timesheet detail
            if (!empty($timesheetDetailId)) {
                $approvalHistoryDetailId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $timesheetDetailId)->
                where('approval_histories.transaction_type', '=', 2)->
                where(function ($query) use ($approval) {
                    $query->where('approval_id', '=', $approval['id'])
                        ->orWhere('group_approval_id', '=', $approval['role']);
                })->
                get();

                foreach ($approvalHistoryDetailId as $id) {
                    ApprovalHistory::approve($id->id);
                }
            }


                //transport 3
            if (!empty($transId)) {
                $approvalHistoryTransportId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $transId)->
                where('approval_histories.transaction_type', '=', 3)->
                where(function ($query) use ($approval) {
                    $query->where('approval_id', '=', $approval['id'])
                        ->orWhere('group_approval_id', '=', $approval['role']);
                })->
                get();

                foreach ($approvalHistoryTransportId as $id) {
                    ApprovalHistory::approve($id->id);
                }

            }


                //adcost perumahan 4
            if (!empty($insId)) {
                $approvalHistoryInsentifId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $insId)->
                where('approval_histories.transaction_type', '=', 4)->
                where(function ($query) use ($approval) {
                    $query->where('approval_id', '=', $approval['id'])
                        ->orWhere('group_approval_id', '=', $approval['role']);
                })->
                get();

                foreach ($approvalHistoryInsentifId as $id) {
                    ApprovalHistory::approve($id->id);
                }
            }

            if (empty($approvalHistoryDetailId)) {
                $approvalHistoryDetailId = [];
            }
            if (empty($approvalHistoryTransportId)) {
                $approvalHistoryTransportId = [];
            }
            if (empty($approvalHistoryInsentifId)) {
                $approvalHistoryInsentifId = [];
            }
            $this->createApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId);

            Flash::success('Approve successfully.');

        }

        if ($request->moderation == 2) //reject
        {
            //timesheet detail
            if (!empty($timesheetDetailId)) {
                $approvalHistoryDetailId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $timesheetDetailId)->
                where('approval_histories.transaction_type', '=', 2)->
                get();

                foreach ($approvalHistoryDetailId as $id) {
                    $approval = ApprovalHistory::find($id->id);
                    $approval->approval_status = 2;
                    $approval->updated_at = Carbon\Carbon::now();
                    $approval->moderated_at = Carbon\Carbon::now();
                    $approval->approval_note = $request->approval_note;
                    $approval->save();
                }

                foreach ($timesheetDetailId as $id)
                {
                    $detail = TimesheetDetail::find($id)->first();
                    $detail->approval_status = 0; //reset status
                    $detail->save();
                }
            }


            //transport
            if (!empty($transId)) {
                $approvalHistoryTransportId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $transId)->
                where('approval_histories.transaction_type', '=', 4)->
                get();

                foreach ($approvalHistoryTransportId as $id) {
                    $approval = ApprovalHistory::find($id->id);
                    $approval->approval_status = 2;
                    $approval->updated_at = Carbon\Carbon::now();
                    $approval->moderated_at = Carbon\Carbon::now();
                    $approval->approval_note = $request->approval_note;
                    $approval->save();
                }

                foreach ($transId as $id)
                {
                    $detail = TimesheetTransport::find($id)->first();
                    $detail->status = 0; //reset status
                    $detail->save();
                }
            }




            //adcost
            if (!empty($insId)) {
                $approvalHistoryInsentifId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $insId)->
                where('approval_histories.transaction_type', '=', 3)->
                get();

                foreach ($approvalHistoryInsentifId as $id) {
                    $approval = ApprovalHistory::find($id->id);
                    $approval->approval_status = 2;
                    $approval->updated_at = Carbon\Carbon::now();
                    $approval->moderated_at = Carbon\Carbon::now();
                    $approval->approval_note = $request->approval_note;
                    $approval->save();
                }

                foreach ($insId as $id)
                {
                    $detail = TimesheetInsentif::find($id)->first();
                    $detail->status = 0; //reset status
                    $detail->save();
                }
            }

            if (empty($approvalHistoryDetailId)) {
                $approvalHistoryDetailId = [];
            }
            if (empty($approvalHistoryTransportId)) {
                $approvalHistoryTransportId = [];
            }
            if (empty($approvalHistoryInsentifId)) {
                $approvalHistoryInsentifId = [];
            }

            Flash::success('Reject successfully.');
        }


        if($request->moderation == "4") //paid
        {
            if (!empty($timesheetDetailId))
            {
                $this->paidTimesheetDetail($timesheetDetailId, $approval);
            }
            if(!empty($insId))
            {
                $this->paidAdCost($insId, $approval);
            }
            if(!empty($transId))
            {
                $this->paidTransport($transId, $approval);
            }
        }

        if($request->moderation == "5") //onhold
        {
            if(!empty($timesheetDetailId ))
            {
                $this->onholdTimesheetDetail($timesheetDetailId, $approval, $request);
            }
            if(!empty($insId))
            {
                $this->onholdAdCost($insId, $approval, $request);
            }
            if(!empty($transId))
            {
                $this->onholdTransport($transId, $approval, $request);
            }
        }

        if($request->moderation == "6") //over budget
        {
            if (!empty($timesheetDetailId))
            {
                $this->overbudgetTimesheetDetail($timesheetDetailId, $approval);
            }
            if(!empty($insId))
            {
                $this->overbudgetAdCost($insId, $approval);
            }
            if(!empty($transId))
            {
                $this->overbudgetTransport($transId, $approval);
            }
        }

        if($request->moderation == 2 && empty($request->approval_note))
        {
            Flash::error('Approval note must be filled');
        }

        return redirect(route('timesheets.moderation'));
    }

    public function paidTimesheetDetail($timesheetDetailId, $approval)
    {
        //timesheet detail
        if (!empty($timesheetDetailId)) {
            $approvalHistoryDetailId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $timesheetDetailId)->
            where('approval_histories.transaction_type', '=', 2)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryDetailId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 4; //paid
                $approval->save();
            }
        }



    }

    public function paidTransport($transId, $approval)
    {
        //transport
        if(!empty($transId)) {
            $approvalHistoryTransportId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $transId)->
            where('approval_histories.transaction_type', '=', 3)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryTransportId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 4; //paid
                $approval->save();
            }

        }



    }

    public function paidAdCost($insId, $approval)
    {
        //adcost
        if (!empty($insId)) {
            $approvalHistoryInsentifId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $insId)->
            where('approval_histories.transaction_type', '=', 4)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

                foreach ($approvalHistoryInsentifId as $id) {
                    $approval = ApprovalHistory::find($id->id);
                    $approval->approval_status = 4; //paid
                    $approval->save();
                }
            }
    }

    public function onholdTimesheetDetail($timesheetDetailId, $approval, $request)
    {
        //timesheet detail
        if (!empty($timesheetDetailId)) {
            $approvalHistoryDetailId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $timesheetDetailId)->
            where('approval_histories.transaction_type', '=', 2)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryDetailId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 5; //onhold
                $approval->updated_at = Carbon\Carbon::now();
                $approval->moderated_at = Carbon\Carbon::now();
                $approval->approval_note = $request->approval_note;
                $approval->save();
            }
        }



    }

    public function onholdTransport($transId, $approval, $request)
    {
        //transport
        if(!empty($transId)) {
            $approvalHistoryTransportId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $transId)->
            where('approval_histories.transaction_type', '=', 3)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryTransportId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 5; //onhold
                $approval->updated_at = Carbon\Carbon::now();
                $approval->moderated_at = Carbon\Carbon::now();
                $approval->approval_note = $request->approval_note;
                $approval->save();
            }

        }



    }

    public function onholdAdCost($insId, $approval, $request)
    {
        //adcost
        if (!empty($insId)) {
            $approvalHistoryInsentifId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $insId)->
            where('approval_histories.transaction_type', '=', 4)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryInsentifId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 5; //onhold
                $approval->updated_at = Carbon\Carbon::now();
                $approval->moderated_at = Carbon\Carbon::now();
                $approval->approval_note = $request->approval_note;
                $approval->save();
            }
        }
    }

    public function overbudgetTimesheetDetail($timesheetDetailId, $approval)
    {
        //timesheet detail
        if (!empty($timesheetDetailId)) {
            $approvalHistoryDetailId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $timesheetDetailId)->
            where('approval_histories.transaction_type', '=', 2)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryDetailId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 6; //over budget
                $approval->save();
            }
        }



    }

    public function overbudgetTransport($transId, $approval)
    {
        //transport
        if(!empty($transId)) {
            $approvalHistoryTransportId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $transId)->
            where('approval_histories.transaction_type', '=', 3)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryTransportId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 6; //over budget
                $approval->save();
            }

        }



    }

    public function overbudgetAdCost($insId, $approval)
    {
        //adcost
        if (!empty($insId)) {
            $approvalHistoryInsentifId = DB::
            table('approval_histories')->
            whereIn('transaction_id', $insId)->
            where('approval_histories.transaction_type', '=', 4)->
            where(function ($query) use ($approval) {
                $query->where('approval_id', '=', $approval['id'])
                    ->orWhere('group_approval_id', '=', $approval['role']);
            })->
            get();

            foreach ($approvalHistoryInsentifId as $id) {
                $approval = ApprovalHistory::find($id->id);
                $approval->approval_status = 6; //over budget
                $approval->save();
            }
        }
    }

    public function createApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId)
    {
        foreach ($approvalHistoryDetailId as $td) {
            $this->createNextHistory($td);
        }

        foreach ($approvalHistoryInsentifId as $ti) {
            $this->createNextHistory($ti);
        }

        foreach ($approvalHistoryTransportId as $tt) {
            $this->createNextHistory($tt);
        }
    }

    public function createNextHistory($approvalHistory)
    {
        if ($approvalHistory->sequence_id == 0) {
            $approvalId = 0;
            $groupApprovalId = 5; //PMO
            $sequence = 1;
        }
        if ($approvalHistory->sequence_id == 1) {
            $approvalId = 0;
            $groupApprovalId = 4; //Finance
            $sequence = 2;
        }

        if ($approvalHistory->sequence_id == 0 || $approvalHistory->sequence_id == 1) {

            $isExist = $this->isApprovalHistoryExist($approvalHistory->transaction_id, $approvalHistory->transaction_type, $approvalHistory->user_id, $approvalId, $groupApprovalId);

            if ($isExist==null) {
                $approvalHistory = $this->insertApprovalHistory($approvalHistory->date, $approvalHistory->note, $sequence, $approvalHistory->transaction_id,
                    $approvalHistory->transaction_type, $approvalHistory->user_id, $approvalId, $groupApprovalId);
            } else {
                if($isExist->approval_status != 1)
                {
                    $approvalHistory = $this->updateApprovalHistory($isExist->id, $approvalHistory->date, $approvalHistory->note, $sequence, $approvalHistory->transaction_id,
                        $approvalHistory->transaction_type, $approvalHistory->user_id, $approvalId, $groupApprovalId);
                }
            }

        }
    }

    public function rejectApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId)
    {
        foreach ($approvalHistoryDetailId as $td) {
            $this->rejectAllHistory($td);
        }

        foreach ($approvalHistoryInsentifId as $ti) {
            $this->rejectAllHistory($ti);
        }

        foreach ($approvalHistoryTransportId as $tt) {
            $this->rejectAllHistory($tt);
        }
    }

    public function rejectAllHistory($approvalHistory)
    {
        $approvalHistoryId = DB::
        table('approval_histories')->
        select('approval_histories.id')->
        where('transaction_id', '=', $approvalHistory->transaction_id)->
        where('approval_histories.transaction_type', '=', $approvalHistory->transaction_type)->
        where('approval_histories.user_id', '=', $approvalHistory->user_id)->
        get();

        foreach ($approvalHistoryId as $id) {
            ApprovalHistory::reject($id->id);
        }
    }

    /**
     * Reject Leave in storage.
     *
     * @param CreateLeaveRequest $request
     *
     * @return Response
     */
    public function moderationReject($id)
    {
        return redirect(route('leaves.moderation'));
    }

    private function getCC(CreateLeaveRequest $request)
    {
        $user = Auth::user();

        $consultantPosition = [6, 7, 8, 9, 16];
        $managingConsultantPosition = [4, 5];
        $bod = [1, 2, 3];;

        if ($request->project != null) // Consultant to MC + HRD
        {
            $cc = User::whereIn('position', $managingConsultantPosition)
                ->where('department', $user->department)->get();
            return $cc->pluck('email')->all()->first();
        }
        if (in_array($user->position, $consultantPosition)) // PM to VP+HRD
        {
            $cc = User::where('position', 3)
                ->where('department', $user->department)->get();
            return $cc->pluck('email')->all()->first();
        }

        if (in_array($user->position, $managingConsultantPosition)) // Managing Consultant to Vice President
        {
            return $this->getHRD();
        }

        if (in_array($user->position, $bod)) // VP, Director auto approve admin
        {
            return $this->getHRD();
        } else {
            return $this->getHRD();
        }
    }

    private function isApprovalHistoryExist($transactionId, $transactionType, $user, $approvalId, $groupApprovalId)
    {
        $transactionExist = DB::table('approval_histories')
            ->select('id','approval_status')
            ->where('transaction_id', '=', $transactionId)
            ->where('transaction_type', '=', $transactionType)
            ->where('user_id', '=', $user)
            ->where(function ($query) use ($approvalId, $groupApprovalId) {
                $query->where('approval_id', '=', $approvalId)
                    ->orWhere('group_approval_id', '=', $groupApprovalId);
            })->first();

        return $transactionExist;
    }

    private function insertApprovalHistory($date, $note, $sequence, $transactionId, $transactionType, $user, $approvalId, $groupApprovalId)
    {
        $saveTransaction = DB::table('approval_histories')
            ->insertGetId(array(
                'date' => $date,
                'note' => $note,
                'sequence_id' => $sequence,
                'transaction_id' => $transactionId,
                'transaction_type' => $transactionType,
                'approval_status' => 0,
                'user_id' => $user,
                'approval_id' => $approvalId,
                'group_approval_id' => $groupApprovalId
            ));

        return $saveTransaction;
    }

    private function updateApprovalHistory($id, $date, $note, $sequence, $transactionId, $transactionType, $user, $approvalId, $groupApprovalId)
    {
        $updateDetail = DB::table('approval_histories')
            ->where('id', $id)
            ->update(array(
                'date' => $date,
                'note' => $note,
                'sequence_id' => $sequence,
                'transaction_id' => $transactionId,
                'transaction_type' => $transactionType,
                'approval_status' => 0,
                'user_id' => $user,
                'approval_id' => $approvalId,
                'group_approval_id' => $groupApprovalId
            ));

        return $updateDetail;
    }

    private function getProjectBudget($user, $approval, $timesheet_details, $timesheet_insentif, $timesheet_transport)
    {
        $timesheetDetailValue = $this->getDetailValue($user, $approval, $timesheet_details);

        $timesheetInsentifValue = $this->getInsentifValue($timesheet_insentif);

        $timesheetTransportValue = $this->getTransportValue($timesheet_transport);

        $summaryBudget = [];

        foreach($timesheetDetailValue as $detail)
        {
            if(array_key_exists('project_id', $summaryBudget) && $summaryBudget['project_id'] == $detail['project_id'])
            {
                $summaryBudget['total'] += $detail['Transport Lokal'];
                $summaryBudget['total'] += $detail['Transport Luar Kota'];
                $summaryBudget['total'] += $detail['Insentif Project'];
            } else
            {
                $summaryBudget['project_id'] = $detail['project_id'];

                $summaryBudget['total'] = 0;
                $summaryBudget['total'] += $detail['Transport Lokal'];
                $summaryBudget['total'] += $detail['Transport Luar Kota'];
                $summaryBudget['total'] += $detail['Insentif Project'];

                $summaryBudget['budget'] = $detail['budget'];
            }
        }

        foreach($timesheetTransportValue as $transport)
        {
            if(array_key_exists('project_id', $summaryBudget) && $summaryBudget['project_id'] == $transport['project_id'])
            {
                $summaryBudget['total'] += $transport->total;
            } else
            {
                $summaryBudget['project_id'] = $detail['project_id'];

                $summaryBudget['total'] = 0;
                $summaryBudget['total'] = $transport['total'];

                $summaryBudget['budget'] = $transport['budget'];
            }
        }

        foreach($timesheetInsentifValue as $insentif)
        {
            if(array_key_exists('project_id', $summaryBudget) && $summaryBudget['project_id'] == $insentif['project_id'])
            {
                $summaryBudget['total'] += $insentif->total;
            } else
            {
                $summaryBudget['project_id'] = $detail['project_id'];

                $summaryBudget['total'] = 0;
                $summaryBudget['total'] = $insentif['total'];

                $summaryBudget['budget'] = $insentif['budget'];
            }

        }
        return collect($summaryBudget)->groupBy('project_id');
    }

    private function getDetailValue($user, $approval, $timesheet_details)
    {
        $mandays = TimesheetDetail::select(DB::raw('project_id, lokasi, budget, count(*)total'))->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')->
        join('projects', 'timesheet_details.project_id', 'projects.id')->
        where('approval_histories.approval_status', '=', 4)->
        where('approval_histories.transaction_type', '=', 2)->
        whereIn('approval_histories.transaction_id', $timesheet_details->pluck('transaction_id'))->
        where('selected','=',1)->
        groupBy('timesheet_details.lokasi')->
        get();

        $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional 
            FROM tunjangan_positions,tunjangans,positions,users 
            WHERE tunjangan_positions.tunjangan_id = tunjangans.id and tunjangan_positions.position_id = positions.id 
            and users.position = positions.id and users.id = ' . $user->id));

        foreach ($tunjangans as $t) {
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }

        foreach ($mandays as $m) {
            if ($m->lokasi === "JABODETABEK") {
                $detail['lokal']['budget'] = $m->budget;
                $detail['lokal']['project_id'] = $m->project_id;
                $detail['lokal']['total'] = $m->total;
                if (!empty ($arr)) {
                    if ($arr['lokal'] != null) {
                        foreach ($arr['lokal'] as $key => $value) {
                            $detail['lokal'][$key] = $value * $m->total;
                        }
                    }
                }
            } else if ($m->lokasi === "DOMESTIK L. JAWA") {
                $detail['luar_jawa']['budget'] = $m->budget;
                $detail['luar_jawa']['project_id'] = $m->project_id;
                $detail['luar_jawa']['total'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['luar_jawa'] as $key => $value) {
                        $detail['luar_jawa'][$key] = $value * $m->total;
                    }
                }
            } else if ($m->lokasi === "DOMESTIK P. JAWA") {
                $detail['non_lokal']['budget'] = $m->budget;
                $detail['non_lokal']['project_id'] = $m->project_id;
                $detail['non_lokal']['total'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['non_lokal'] as $key => $value) {
                        $detail['non_lokal'][$key] = $value * $m->total;
                    }
                }
            } else if ($m->lokasi === "INTERNATIONAL") {
                $detail['internasional']['budget'] = $m->budget;
                $detail['internasional']['project_id'] = $m->project_id;
                $detail['internasional']['total'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['internasional'] as $key => $value) {
                        $detail['internasional'][$key] = $value * $m->total;
                    }
                }
            }
        }

        return $detail;
    }

    private function getInsentifValue($timesheet_insentif)
    {
        $timesheetInsentifValue = TimesheetInsentif::select(DB::raw('sum(timesheet_insentif.value)total, timesheet_insentif.project_id'))->
            join('approval_histories', 'approval_histories.transaction_id', 'timesheet_insentif.id')->
            where('approval_histories.approval_status', '=', 4)->
            where('approval_histories.transaction_type', '=', 4)->
            whereIn('approval_histories.transaction_id', $timesheet_insentif->pluck('transaction_id'))->
            groupBy('timesheet_insentif.project_id')->
            get();

        return $timesheetInsentifValue;
    }

    private function getTransportValue($timesheet_transport)
    {
        $timesheetTransportValue = TimesheetTransport::
            select(DB::raw('sum(timesheet_transport.value)total, timesheet_transport.project_id'))->
            join('approval_histories', 'approval_histories.transaction_id', 'timesheet_transport.id')->
            where('approval_histories.approval_status', '=', 4)->
            where('approval_histories.transaction_type', '=', 3)->
            whereIn('approval_histories.transaction_id', $timesheet_transport->pluck('transaction_id'))->
            groupBy('timesheet_transport.project_id')->
            get();

        return $timesheetTransportValue;
    }
}
