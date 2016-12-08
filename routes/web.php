<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});


Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('users', 'UserController');

Route::get('change/{id}', array('uses' => 'UserController@change',
'as' => 'change'));

Route::resource('approvalHistories', 'ApprovalHistoryController');

Route::resource('departments', 'DepartmentController');

//Route::post('holidaysimport', 'HolidayController@processSheet');
Route::post('holidaysimport', array('uses' => 'HolidayController@processSheet',
'as' => 'holidaysimport'));

Route::resource('holidays', 'HolidayController');


Route::resource('projects', 'ProjectController');

Route::resource('projectMembers', 'ProjectMemberController');

Route::resource('roles', 'RoleController');

Route::resource('sequences', 'SequenceController');

Route::resource('timesheets', 'TimesheetController');

Route::resource('timesheetDetails', 'TimesheetDetailController');

Route::resource('tunjangans', 'TunjanganController');

Route::resource('tunjanganProjects', 'TunjanganProjectController');

Route::resource('tunjanganPositions', 'TunjanganPositionController');

Route::resource('positions', 'PositionController');

Route::resource('audits', 'AuditController');

Route::resource('accessModules', 'AccessModuleController');

Route::resource('roleAccesses', 'RoleAccessController');


//Example role access
Route::get('testrole', function () {
    return 'tes';
})->middleware('checkRole:Admin|CBS|Finance|Manager|PMO|VP');

Route::resource('constants', 'ConstantController');

Route::resource('userLeaves', 'UserLeaveController');

Route::get('leaves/submission', array('uses' => 'LeaveController@submission',
'as' => 'leaves.submission'));

Route::get('leaves/submission/create', array('uses' => 'LeaveController@submissionCreate',
'as' => 'leaves.submission.create'));

Route::get('leaves/submission/update', array('uses' => 'LeaveController@submissionUpdate',
'as' => 'leaves.submission.update'));

Route::post('leaves/submission/store', array('uses' => 'LeaveController@submissionStore',
'as' => 'leaves.submission.store'));

Route::get('leaves/submission/show/{id}', array('uses' => 'LeaveController@submissionShow',
'as' => 'leaves.submission.show'));

Route::get('timesheet_history', 'Add_Timesheet@getColumns');

Route::get('timesheet/show/{id}', array('uses' => 'Add_Timesheet@show',
'as' => 'add_timesheet.show'));

Route::post('add_timesheet/create', array('uses' => 'Add_Timesheet@create',
'as' => 'add_timesheet.store'));

Route::post('add_timesheet/form', array('uses' => 'Add_Timesheet@form',
'as' => 'add_timesheet.form'));
Route::resource('add_timesheet', 'Add_Timesheet');

Route::get('leaves/moderation', array('uses' => 'LeaveController@moderation',
'as' => 'leaves.moderation'));

Route::get('leaves/moderation/show/{id}', array('uses' => 'LeaveController@moderationShow',
'as' => 'leaves.moderation.show'));

Route::get('leaves/moderation/edit/{id}', array('uses' => 'LeaveController@moderationEdit',
'as' => 'leaves.moderation.edit'));

Route::get('leaves/moderation/approve/{id}', array('uses' => 'LeaveController@moderationApprove',
'as' => 'leaves.moderation.approve'));

Route::get('leaves/moderation/reject/{id}', array('uses' => 'LeaveController@moderationReject',
'as' => 'leaves.moderation.reject'));

Route::resource('leaves', 'LeaveController');