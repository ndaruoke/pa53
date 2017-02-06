<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetDetailRepository;
use App\Models\User;
use Mail;
use App\Mail\TimesheetSubmission;

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
        $timesheet = $this->timesheetRepository->with('users')->find($id);


        $user = User::where('id', $id)->first();


        /**
         *
        $this->info($user->toJson());
        $this->line($timesheet->toJson());
        $request = $this->createFromUser($user);
        $this->info($user->toJson());
        $this->line($timesheet->toJson());
        dd($request);

         *
         * **/

        $mail = Mail::to($user['email'])
            ->send(new TimesheetSubmission($user, $timesheet));

        $this->info('Executed');
        
    }

    private function createFromUser(User $user)
    {
        return array(
            'name' => $user->name
        );
    }
}
