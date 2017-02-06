<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Timesheet;

class TimesheetSubmission extends Mailable
{
    /**
     * The request instance.
     *
     * @var CreateTimesheetRequest
     */
    public $user;
    public $url;
    public $timesheet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Timesheet $timesheet)
    {
        $this->user = $user;
        $this->url = url()->to('/');
        $this->timesheet = $timesheet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('email.timesheet.submission');
    }
}
