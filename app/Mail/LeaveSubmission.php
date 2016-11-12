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
use App\User;

class LeaveSubmission extends Mailable
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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CreateLeaveRequest request, User approver)
    {
        $this->request = $request;
		
		$this->startDate = new Carbon($request->start_date);
		$this->endDate = new Carbon($request->end_date);
		$this->dayCount = $this->endDate->diff($this->startDate)->days;
		$this->url = URL::to('/');
		$this->user = Auth::user();
		$this->approver = approver;
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
