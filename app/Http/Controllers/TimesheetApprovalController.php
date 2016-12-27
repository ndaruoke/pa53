<?php

namespace App\Http\Controllers;

use App\DataTables\ModerationTimesheetDataTable;
use App\DataTables\TimesheetDetailDataTable;
use App\DataTables\TimesheetDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Models\ApprovalHistory;
use App\Models\Constant;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use App\Models\User;
use App\Models\Project;
use App\Models\UserLeave;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetDetailRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth;
use DB;
use Illuminate\Http\Request;

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
        if(empty($_REQUEST['approvalStatus']))
        {
            $status = 0;
        } else 
        {
            $status = $_REQUEST['approvalStatus'];
        }
        $approvalStatus = [''=>''] +Constant::where('category','Moderation')->orderBy('name','asc')->pluck('name', 'value')->all();
        return $moderationTimesheetDataTable->render('timesheets.moderation', array('user'=>$user,'approvalStatus'=>$approvalStatus, 'status'=>$status));
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

        $lokasi = [''=>''] +Constant::where('category','Location')->orderBy('name','asc')->pluck('name', 'value')->all();
        $activity = [''=>''] +Constant::where('category','Activity')->orderBy('name','asc')->pluck('name', 'value')->all();
        $project = Project::pluck('project_name', 'id')->all();
        $timesheets = Timesheet::where('approval_id','=',$approvalId)->where('user_id','=',$userId)->get();
        $timesheet_details = DB::
                table('timesheet_details')->
                join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')->
                where('approval_histories.approval_id','=',$approvalId)->
                where('approval_histories.approval_status','=',$approvalStatus)->
                where('approval_histories.transaction_type','=', 2)->
                where('selected','=','1')->
                get();
        $timesheet_insentif = DB::
                table('timesheet_insentif')->
                join('approval_histories', 'approval_histories.transaction_id', 'timesheet_insentif.id')->
                where('approval_histories.approval_id','=',$approvalId)->
                where('approval_histories.approval_status','=',$approvalStatus)->
                where('approval_histories.transaction_type','=', 3)->
                get();
        $timesheet_transport = DB::
                table('timesheet_transport')->
                join('approval_histories', 'approval_histories.transaction_id', 'timesheet_transport.id')->
                where('approval_histories.approval_id','=',$approvalId)->
                where('approval_histories.approval_status','=',$approvalStatus)->
                where('approval_histories.transaction_type','=', 4)->
                get();
        $summary = $this->populateSummary($timesheets, $userId, $timesheet_insentif, $timesheet_transport);

        if (empty($timesheets)) {
            Flash::error('Timesheet not found');

            return redirect(route('leaves.moderation'));
        }

        return view('timesheets.moderation_edit',compact('userId','lokasi','activity','timesheets','project','timesheet_details','timesheet_insentif','timesheet_transport','summary'));
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
        if (empty($request->moderation)) {
            Flash::error('Please choose reject or approve');

            return back();
        }
        $userId = $request->userId;
        $approval = Auth::user();

        foreach($request->timesheetdetail as $key => $value)
        {
            if(!empty($value['choose'])) $timesheetDetailId[] = ['id'=> $value['transaction_id']];
        }

        if(!empty($request->trans))
        {
            foreach($request->trans as $key => $value)
                {
                    if(!empty($value['choose'])) $transId[] = ['id'=> $value['transaction_id']];
                }
        }
        
        if(!empty($request->insentif))
                {
            foreach($request->insentif as $key => $value)
            {
                if(!empty($value['choose'])) $insId[] = ['id'=> $value['transaction_id']];
            }
        }

        if($request->moderation == 1) //approve
        {

            //timesheet detail
            $approvalHistoryDetailId = DB::
            table('approval_histories')->
            whereIn('transaction_id',$timesheetDetailId)->
            where('approval_histories.transaction_type','=', 2)->
            where(function ($query) use($approval){
                $query->where('approval_id','=',$approval['id'])
                        ->orWhere('group_approval_id','=', $approval['role']);
            })->
            get();

            foreach($approvalHistoryDetailId as $id)
            {
                ApprovalHistory::approve($id->id);
            }
            
            if(!empty($transId))   
            {
                //transport  
                $approvalHistoryTransportId = DB::
                table('approval_histories')->
                whereIn('transaction_id',$transId)->
                where('approval_histories.transaction_type','=', 4)->
                where(function ($query) use($approval){
                    $query->where('approval_id','=',$approval['id'])
                            ->orWhere('group_approval_id','=', $approval['role']);
                })->
                get();

                foreach($approvalHistoryTransportId as $id)
                {
                    ApprovalHistory::approve($id->id);
                }

            } 
            
            if(!empty($insId))
            {
                 //adcost  
                $approvalHistoryInsentifId = DB::
                table('approval_histories')->
                whereIn('transaction_id',$insId)->
                where('approval_histories.transaction_type','=', 3)->
                where(function ($query) use($approval){
                    $query->where('approval_id','=',$approval['id'])
                            ->orWhere('group_approval_id','=', $approval['role']);
                })->
                get();

                foreach($approvalHistoryInsentifId as $id)
                {
                    ApprovalHistory::approve($id->id);
                }
            }
            if(empty($approvalHistoryDetailId)){ $approvalHistoryDetailId = []; }
            if(empty($approvalHistoryTransportId)){ $approvalHistoryTransportId = []; }
            if(empty($approvalHistoryInsentifId)){ $approvalHistoryInsentifId = []; }
            $this->createApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId);
           
        }

        if($request->moderation == 2) //reject
        {
            //timesheet detail
            $approvalHistoryDetailId = DB::
            table('approval_histories')->
            whereIn('transaction_id',$timesheetDetailId)->
            where('approval_histories.transaction_type','=', 2)->
            where(function ($query) use($approval){
                $query->where('approval_id','=',$approval['id'])
                        ->orWhere('group_approval_id','=', $approval['role']);
            })->
            get();

            foreach($approvalHistoryId as $id)
            {
                ApprovalHistory::reject($id->id);
            }
            
                
            //transport  
            $approvalHistoryTransportId = DB::
            table('approval_histories')->
            whereIn('transaction_id',$transId)->
            where('approval_histories.transaction_type','=', 4)->
            where(function ($query) use($approval){
                $query->where('approval_id','=',$approval['id'])
                        ->orWhere('group_approval_id','=', $approval['role']);
            })->
            get();

            foreach($approvalHistoryId as $id)
            {
                ApprovalHistory::reject($id->id);
            }


            //adcost  
            $approvalHistoryInsentifId = DB::
            table('approval_histories')->
            whereIn('transaction_id',$insId)->
            where('approval_histories.transaction_type','=', 3)->
            where(function ($query) use($approval){
                $query->where('approval_id','=',$approval['id'])
                        ->orWhere('group_approval_id','=', $approval['role']);
            })->
            get();

            foreach($approvalHistoryId as $id)
            {
                ApprovalHistory::reject($id->id);
            }

            if(empty($approvalHistoryDetailId)){ $approvalHistoryDetailId = []; }
            if(empty($approvalHistoryTransportId)){ $approvalHistoryTransportId = []; }
            if(empty($approvalHistoryInsentifId)){ $approvalHistoryInsentifId = []; }
            $this->rejectApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId);
        }


        Flash::success('Reject successfully.');

        return redirect(route('timesheets.moderation'));
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
        $userLeave = $this->userLeaveRepository->findByField('user_id',$leave->user_id)->first();
        if (empty($userLeave)) {
            Flash::error('User Leave not found');

            return redirect(route('leaves.moderation'));
        }

        $endDate = new Carbon($leave->end_date);
        $startDate = new Carbon($leave->start_date);
        $dayCount = $endDate->diff($startDate)->days;
        $userLeave->leave_used = $userLeave->leave_used-$dayCount;
        
        $userLeave->save();

        $leave = Leave::reject($id);

        if ($leave == false) {
            Flash::error('Reject Leave Fail');

            return redirect(route('leaves.moderation'));
        }

        $leave = $this->leaveRepository->findWithoutFail($id);
        $approver = User::where('id',$leave->approval_id)->get()->first();
        $user = User::where('id',$leave->user_id)->get()->first();
        //send email
		Mail::to($user->email)
                ->queue(new LeaveNotification($leave, $approver, $user, 'rejected'));

        Flash::success('Reject successfully.');

        return redirect(route('leaves.moderation'));
    }

    private function getCC(CreateLeaveRequest $request)
	{
		$user = Auth::user();
		
		$consultantPosition = [6,7,8,9,16];
        $managingConsultantPosition = [4,5];
        $bod = [1,2,3];;

		if($request->project != null) // Consultant to MC + HRD
		{
			$cc = User::whereIn('position',$managingConsultantPosition)
                ->where('department',$user->department)->get();
			return $cc->pluck('email')->all()->first();
		}
        if(in_array($user->position, $consultantPosition)) // PM to VP+HRD
        {
            $cc = User::where('position',3)
                ->where('department',$user->department)->get();
            return $cc->pluck('email')->all()->first();
        }

		if(in_array($user->position, $managingConsultantPosition)) // Managing Consultant to Vice President
        {
            return $this->getHRD();
        }

        if(in_array($user->position, $bod)) // VP, Director auto approve admin
        {
            return $this->getHRD();
        }

		else
		{
			return $this->getHRD();
        }
    }


    public function populateSummary($timesheets, $user_id, $timesheet_insentif, $timesheet_transport){
         $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional FROM tunjangan_positions,tunjangans,positions,users WHERE tunjangan_positions.tunjangan_id = tunjangans.id and tunjangan_positions.position_id = positions.id and users.position = positions.id and users.id = '.$user_id));


        foreach ($tunjangans as $t){
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }

        $summary['total'] = 0;

        foreach($timesheet_insentif as $adcost)
        {
            $summary['total'] += $adcost->value;
        }
        $summary['perumahan']['total'] = $timesheet_insentif->pluck('value')->sum();
        $summary['perumahan']['count'] = $timesheet_insentif->count();

        foreach($timesheet_transport as $transport)
        {
            $summary['total'] += $transport->value;
        }
        $summary['adcost']['total'] = $timesheet_transport->pluck('value')->sum();
        $summary['adcost']['count'] = $timesheet_transport->count();

        $mandays = DB::table('timesheet_details')
        ->select(DB::raw('count(*) as total, lokasi'))
        ->whereIn('timesheet_id', $timesheets->pluck('id'))     
        ->groupBy('lokasi')
        ->get();

        foreach($mandays as $m)
        {
            if($m->lokasi === "JABODETABEK"){
                $summary['lokal']['count'] = $m->total;
                if ( !empty ( $arr ) ) {
                    if($arr['lokal']!=null)
                    {
                        foreach ($arr['lokal'] as $key => $value){
                            $summary['lokal'][$key] = $value*$m->total;
                        }
                    }
                }
                
            } else if ($m->lokasi === "LUARJAWA"){
                $summary['luar_jawa']['count'] = $m->total;
                if ( !empty ( $arr ) ) {
                    foreach ($arr['luar_jawa'] as $key => $value){
                        $summary['luar_jawa'][$key] = $value*$m->total;
                    //  echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
                    }
                }
            } else if ($m->lokasi === "JAWA"){
               $summary['non_lokal']['count'] = $m->total;
                foreach ($arr['non_lokal'] as $key => $value){
                    $summary['non_lokal'][$key] = $value*$m->total;
                 //   echo $key. ' = '.$value. ' * '.$m->total.' '.$value*$m->total.'<br>';
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
            $summary['internasional']['Insentif Project'] ;

       return $summary ;
    } 



    public function createApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId)
    {
        foreach($approvalHistoryDetailId as $td)   
        {
            $this->createNextHistory($td);
        }

        foreach($approvalHistoryInsentifId as $ti)   
        {
            $this->createNextHistory($ti);
        }

        foreach($approvalHistoryTransportId as $tt)   
        {
            $this->createNextHistory($tt);
        }       
    }

    public function rejectApprovalHistory($approvalHistoryDetailId, $approvalHistoryTransportId, $approvalHistoryInsentifId)
    {
        foreach($approvalHistoryDetailId as $td)   
        {
            $this->rejectAllHistory($td);
        }

        foreach($approvalHistoryInsentifId as $ti)   
        {
            $this->rejectAllHistory($ti);
        }

        foreach($approvalHistoryTransportId as $tt)   
        {
            $this->rejectAllHistory($tt);
        }       
    }

    public function createNextHistory($approvalHistory)
    {
        if($approvalHistory->sequence_id == 0)
            {
                $approvalId = 0;
                $groupApprovalId = 5; //PMO
                $sequence = 1;
            }
            if($approvalHistory->sequence_id == 1)
            {
                $approvalId = 0;
                $groupApprovalId = 4; //Finance
                $sequence = 2;
            }

            if($approvalHistory->sequence_id == 0 || $approvalHistory->sequence_id == 1)
            {
                $saveDetail = DB::table('approval_histories')
                    ->insertGetId( array(
                    'date' => $approvalHistory->date,
                    'note' => $approvalHistory->note,
                    'sequence_id' => $sequence,
                    'transaction_id' => $approvalHistory->transaction_id,
                    'transaction_type' => $approvalHistory->transaction_type,
                    'approval_status' => 0,
                    'user_id' => $approvalHistory->user_id,
                    'approval_id' => $approvalId,
                    'group_approval_id' => $groupApprovalId
                    ));
            }
    }

    public function rejectAllHistory($approvalHistory)
    {
        $approvalHistoryInsentifId = DB::
            table('approval_histories')->
            select('approval_histories->id')->
            where('transaction_id','=',$approvalHistory->transaction_id)->
            where('approval_histories.transaction_type','=',$approvalHistory->transaction_type)->
            where('approval_histories.user_id','=',$approvalHistory->user_id)->
            get();

            foreach($approvalHistoryId as $id)
            {
                ApprovalHistory::reject($id->id);
            }
    }
}
