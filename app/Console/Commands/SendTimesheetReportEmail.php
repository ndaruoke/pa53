<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetDetailRepository;
use App\Models\User;
use Mail;
use App\Mail\TimesheetSubmission;
use Excel;
use DB;
use App\Models\TimesheetDetail;

class SendTimesheetReportEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /** @var  Repository */
    private $timesheetRepository;
    private $userRepository;

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
    public function __construct(TimesheetRepository $timesheetRepo, UserRepository $userRepo)
    {
        parent::__construct();
        $this->timesheetRepository = $timesheetRepo;
        $this->userRepository = $userRepo;
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

        $id = 1;

        /**
        $timesheet = TimesheetDetail::
        join('timesheets', 'timesheet_details.timesheet_id', 'timesheets.id')->
        join('users', 'users.id', 'timesheets.user_id')->
        join('projects', 'projects.id', 'timesheet_details.project_id')->
        select('users.nik as user_id',
            'timesheet_details.project_id',
            'projects.project_name as wbs_id',
            'timesheet_details.activity as subject',
            'timesheet_details.activity_detail as message',
            'timesheet_details.hour as hour_total',
            'timesheet_details.date as ts_date',
            'timesheet_details.created_at as submit_date')->
        get();

         * **/

        $timesheet = TimesheetDetail::
        join('timesheets', 'timesheet_details.timesheet_id', 'timesheets.id')->
        join('users', 'users.id', 'timesheets.user_id')->
        join('projects', 'projects.id', 'timesheet_details.project_id')->
        get();

        $data = array();
        $count = 0;
        foreach ($timesheet as $result) {
            //$data[] = (array)$result;
            //$res = array();
            $res = array(
                'user_id'=>$result->nik,
                'project_id'=>$result->project_id,
                'wbs_id'=>$result->project_name,
                'subject'=>$result->activity,
                'message'=>$result->activity_detail,
                'hour_total'=>$result->hour,
                'ts_date'=>$result->date,
                'submit_date'=>$result->created_at->toDateTimeString()
        );
            $data[$count] = $res;
            $count++;
        }


        $user = User::where('id', $id)->first();

        $path = Excel::create('Timesheet', function($excel) use ($timesheet, $data) {

            // Set the title
            $excel->setTitle('Timesheet');
            // Chain the setters
            $excel->setCreator('PA-Online')
                ->setCompany('PT. Sigma Metrasys Solution');
            // Call them separately
            $excel->setDescription('Weekly Timesheet');

            //get data
            $excel->sheet('timesheet', function($sheet) use ($timesheet, $data) {
                //$data[] = (array)$timesheet;
                //dd($timesheet->toArray());

                //$sheet->fromArray($data);
                $sheet->fromModel($data, null, 'A1', true);
            });

        })->store('xls', false, true);

        // send mail
        $mail = Mail::to($user['email'])->send(new TimesheetSubmission($user, $path['full']));

        $this->info('Executed');
        
    }

    private function createFromUser(User $user)
    {
        return array(
            'name' => $user->name
        );
    }
}
