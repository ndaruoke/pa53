<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Requests\CreateLeaveRequest;
use Carbon\Carbon;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Constant;

class LeaveSubmission extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
	
	/**
     * The request instance.
     *
     * @var CreateLeaveRequest
     */
	public $request;
	public $user;
	public $startDate;
	public $endDate;
	public $dayCount;
	public $url;
	public $approver;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $request, User $approver)
    {
        $this->request = $request;
		Carbon::setLocale('id');
        $start = new Carbon($request['start_date']);
        $end = new Carbon($request['end_date']);
		$this->startDate = $start->format('d M Y');
		$this->endDate = $end->format('d M Y');
		$this->dayCount = $end->diff($start)->days;
		$this->url = url()->to('/');
		$this->user = Auth::user();
		$this->approver = $approver;

        $constant = Constant::where('category','Cuti')->where('value',$request['type'])->get();
        $this->type = $constant->first()->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.leave.submission');
    }
}
