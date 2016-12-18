<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetDetailRepository;

class SendTimesheetReportEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send timesheet report to HRD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$arguments = $this->arguments();

        //$user = $this->argument('user');


        $timesheet = $this->timesheetRepository->with('users')->findWithoutFail($id);

        if(false)
        {
            $this->line('Timesheet failed to send');
        } else {
            $this->line('Timesheet has been send to HRD');
        }
        
    }
}
