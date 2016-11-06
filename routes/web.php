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

Route::get('cuti/submission', array('uses' => 'LeaveController@submission',
    'as' => 'cuti.submission'));

Route::get('cuti/submission/create', array('uses' => 'LeaveController@submissionCreate',
    'as' => 'cuti.submission.create'));

Route::get('cuti/submission/update', array('uses' => 'LeaveController@submissionUpdate',
    'as' => 'cuti.submission.update'));

Route::post('cuti/submission/store', array('uses' => 'LeaveController@submissionStore',
    'as' => 'cuti.submission.store'));

Route::get('cuti/submission/show/{id}', array('uses' => 'LeaveController@submissionShow',
    'as' => 'cuti.submission.show'));

Route::resource('leaves', 'LeaveController');