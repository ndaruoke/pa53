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
use App\Models\User;
use App\Repositories\TimesheetDetailRepository;
use App\Repositories\TimesheetRepository;
use Auth;
use DB;
use Flash;
use Illuminate\Http\Request;
use Response;

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
        $approval = User::where('id', '=', $approvalId)->select('id')->first();

        if($approval==null)
        {
            $approval = Auth::User();
        }

        $lokasi = ['' => ''] + Constant::where('category', 'Location')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $activity = ['' => ''] + Constant::where('category', 'Activity')->orderBy('name', 'asc')->pluck('name', 'value')->all();
        $project = Project::pluck('project_name', 'id')->all();
        $timesheets = Timesheet::where('approval_id', '=', $approvalId)->where('user_id', '=', $userId)->get();
        $timesheet_details = DB::
        table('timesheet_details')->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')->
        where('approval_histories.approval_id', '=', $approvalId)->
        where('approval_histories.approval_status', '=', $approvalStatus)->
        where('approval_histories.transaction_type', '=', 2)->
        where('selected', '=', '1')->
        get();
        $timesheet_insentif = DB::
        table('timesheet_insentif')->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_insentif.id')->
        where('approval_histories.approval_id', '=', $approvalId)->
        where('approval_histories.approval_status', '=', $approvalStatus)->
        where('approval_histories.transaction_type', '=', 3)->
        get();
        $timesheet_transport = DB::
        table('timesheet_transport')->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_transport.id')->
        where('approval_histories.approval_id', '=', $approvalId)->
        where('approval_histories.approval_status', '=', $approvalStatus)->
        where('approval_histories.transaction_type', '=', 4)->
        get();
        $summary = $this->populateSummary($timesheets, $user, $approval, $approvalStatus, $timesheet_insentif, $timesheet_transport);

        if (empty($timesheets)) {
            Flash::error('Timesheet not found');

            return redirect(route('leaves.moderation'));
        }

        return view('timesheets.moderation_edit', compact('approvalStatus', 'user', 'userId', 'lokasi', 'activity', 'timesheets', 'project', 'timesheet_details', 'timesheet_insentif', 'timesheet_transport', 'summary'));
    }

    public function populateSummary($timesheets, $user, $approval, $approvalStatus, $timesheet_insentif, $timesheet_transport)
    {
        $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional FROM tunjangan_positions,tunjangans,positions,users WHERE tunjangan_positions.tunjangan_id = tunjangans.id and tunjangan_positions.position_id = positions.id and users.position = positions.id and users.id = ' . $user->id));

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
        and approval_histories.approval_id = " . $approval['id'] . " 
        and approval_histories.approval_status = " . $approvalStatus . " 
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

            } else if ($m->lokasi === "LUARJAWA") {
                $summary['luar_jawa']['count'] = $m->total;
                if (!empty ($arr)) {
                    foreach ($arr['luar_jawa'] as $key => $value) {
                        $summary['luar_jawa'][$key] = $value * $m->total;
                        //  echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
            } else if ($m->lokasi === "JAWA") {
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
        if (empty($request->moderation )&& $request->paid != "1") {
            Flash::error('Please choose reject or approve');

            return back()->withInput();
        }

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

        if($request->paid == "1") //paid
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


            //transport
            if (!empty($transId)) {
                $approvalHistoryTransportId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $transId)->
                where('approval_histories.transaction_type', '=', 4)->
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


            //adcost
            if (!empty($insId)) {
                $approvalHistoryInsentifId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $insId)->
                where('approval_histories.transaction_type', '=', 3)->
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


                //transport
            if (!empty($transId)) {
                $approvalHistoryTransportId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $transId)->
                where('approval_histories.transaction_type', '=', 4)->
                where(function ($query) use ($approval) {
                    $query->where('approval_id', '=', $approval['id'])
                        ->orWhere('group_approval_id', '=', $approval['role']);
                })->
                get();

                foreach ($approvalHistoryTransportId as $id) {
                    ApprovalHistory::approve($id->id);
                }

            }


                //adcost
            if (!empty($insId)) {
                $approvalHistoryInsentifId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $insId)->
                where('approval_histories.transaction_type', '=', 3)->
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
                where(function ($query) use ($approval) {
                    $query->where('approval_id', '=', $approval['id'])
                        ->orWhere('group_approval_id', '=', $approval['role']);
                })->
                get();

                foreach ($approvalHistoryDetailId as $id) {
                    ApprovalHistory::reject($id->id);

                    $approval = ApprovalHistory::find($id->id);
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
                where(function ($query) use ($approval) {
                    $query->where('approval_id', '=', $approval['id'])
                        ->orWhere('group_approval_id', '=', $approval['role']);
                })->
                get();

                foreach ($approvalHistoryTransportId as $id) {
                    ApprovalHistory::reject($id->id);
                }
            }




            //adcost
            if (!empty($insId)) {
                $approvalHistoryInsentifId = DB::
                table('approval_histories')->
                whereIn('transaction_id', $insId)->
                where('approval_histories.transaction_type', '=', 3)->
                where(function ($query) use ($approval) {
                    $query->where('approval_id', '=', $approval['id'])
                        ->orWhere('group_approval_id', '=', $approval['role']);
                })->
                get();

                foreach ($approvalHistoryInsentifId as $id) {
                    ApprovalHistory::reject($id->id);
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
            $this->rejectApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId);

            Flash::success('Reject successfully.');
        }



        if($request->moderation == 2 && empty($request->approval_note))
        {
            Flash::error('Approval note must be filled');
        }

        return redirect(route('timesheets.moderation'));
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
                $approvalHistory = $this->updateApprovalHistory($isExist->id, $approvalHistory->date, $approvalHistory->note, $sequence, $approvalHistory->transaction_id,
                    $approvalHistory->transaction_type, $approvalHistory->user_id, $approvalId, $groupApprovalId);
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
        $timesheet = $this->timesheetRepository->findWithoutFail($id);
        if (empty($timesheet)) {
            Flash::error('Timesheet not found');

            return redirect(route('timesheet.moderation'));
        }
        $userLeave = $this->userLeaveRepository->findByField('user_id', $leave->user_id)->first();
        if (empty($userLeave)) {
            Flash::error('User Leave not found');

            return redirect(route('leaves.moderation'));
        }

        $endDate = new Carbon($leave->end_date);
        $startDate = new Carbon($leave->start_date);
        $dayCount = $endDate->diff($startDate)->days;
        $userLeave->leave_used = $userLeave->leave_used - $dayCount;

        $userLeave->save();

        $leave = Leave::reject($id);

        if ($leave == false) {
            Flash::error('Reject Leave Fail');

            return redirect(route('leaves.moderation'));
        }

        $leave = $this->leaveRepository->findWithoutFail($id);
        $approver = User::where('id', $leave->approval_id)->get()->first();
        $user = User::where('id', $leave->user_id)->get()->first();
        //send email
        Mail::to($user->email)
            ->queue(new LeaveNotification($leave, $approver, $user, 'rejected'));

        Flash::success('Reject successfully.');

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
            ->select('id')
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
}
