<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-edit"></i><span>User</span></a>
</li>

<li class="{{ Request::is('approvalHistories*') ? 'active' : '' }}">
    <a href="{!! route('approvalHistories.index') !!}"><i class="fa fa-edit"></i><span>Approval History</span></a>
</li>

<li class="{{ Request::is('departments*') ? 'active' : '' }}">
    <a href="{!! route('departments.index') !!}"><i class="fa fa-edit"></i><span>Department</span></a>
</li>

<li class="{{ Request::is('holidays*') ? 'active' : '' }}">
    <a href="{!! route('holidays.index') !!}"><i class="fa fa-edit"></i><span>Holiday</span></a>
</li>

<li class="{{ Request::is('leaves*') ? 'active' : '' }}">
    <a href="{!! route('leaves.index') !!}"><i class="fa fa-edit"></i><span>Leave</span></a>
</li>

<li class="{{ Request::is('projects*') ? 'active' : '' }}">
    <a href="{!! route('projects.index') !!}"><i class="fa fa-edit"></i><span>Project</span></a>
</li>

<li class="{{ Request::is('projectMembers*') ? 'active' : '' }}">
    <a href="{!! route('projectMembers.index') !!}"><i class="fa fa-edit"></i><span>Project Member</span></a>
</li>

<li class="{{ Request::is('roles*') ? 'active' : '' }}">
    <a href="{!! route('roles.index') !!}"><i class="fa fa-edit"></i><span>Role</span></a>
</li>

<li class="{{ Request::is('sequences*') ? 'active' : '' }}">
    <a href="{!! route('sequences.index') !!}"><i class="fa fa-edit"></i><span>Sequence</span></a>
</li>

<li class="{{ Request::is('timesheets*') ? 'active' : '' }}">
    <a href="{!! route('timesheets.index') !!}"><i class="fa fa-edit"></i><span>Timesheet</span></a>
</li>

<li class="{{ Request::is('timesheetDetails*') ? 'active' : '' }}">
    <a href="{!! route('timesheetDetails.index') !!}"><i class="fa fa-edit"></i><span>Timesheet Detail</span></a>
</li>

<li class="{{ Request::is('tunjangans*') ? 'active' : '' }}">
    <a href="{!! route('tunjangans.index') !!}"><i class="fa fa-edit"></i><span>Tunjangan</span></a>
</li>

<li class="{{ Request::is('tunjanganProjects*') ? 'active' : '' }}">
    <a href="{!! route('tunjanganProjects.index') !!}"><i class="fa fa-edit"></i><span>Tunjangan Project</span></a>
</li>

<li class="{{ Request::is('tunjanganRoles*') ? 'active' : '' }}">
    <a href="{!! route('tunjanganRoles.index') !!}"><i class="fa fa-edit"></i><span>Tunjangan Role</span></a>
</li>

