<table class="table table-responsive" id="timesheetDetails-table">
    <thead>
    <th>Lokasi</th>
    <th>Activity</th>
    <th>Date</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Timesheet Id</th>
    <th>Leave Id</th>
    <th>Project Id</th>
    <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($timesheetDetails as $timesheetDetail)
    <tr>
        <td>{!! $timesheetDetail->lokasi !!}</td>
        <td>{!! $timesheetDetail->activity !!}</td>
        <td>{!! $timesheetDetail->date !!}</td>
        <td>{!! $timesheetDetail->start_time !!}</td>
        <td>{!! $timesheetDetail->end_time !!}</td>
        <td>{!! $timesheetDetail->timesheet_id !!}</td>
        <td>{!! $timesheetDetail->leave_id !!}</td>
        <td>{!! $timesheetDetail->project_id !!}</td>
        <td>
            {!! Form::open(['route' => ['timesheetDetails.destroy', $timesheetDetail->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('timesheetDetails.show', [$timesheetDetail->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                <a href="{!! route('timesheetDetails.edit', [$timesheetDetail->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    @endforeach
    </tbody>
</table>