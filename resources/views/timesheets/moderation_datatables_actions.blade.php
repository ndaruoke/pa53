{!! Form::open(['route' => ['timesheets.moderation.edit'], 'method' => 'patch']) !!}
<div class='btn-group'>

    {{ Form::hidden('userId', $user_id) }}
    {{ Form::hidden('approvalId', $approval_id) }}
    {{ Form::hidden('approvalStatus', $approval_status) }}

    {!! Form::button('<i class="glyphicon glyphicon-check"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-default btn-xs'
    ]) !!}
    </div>
</div>
{!! Form::close() !!}



