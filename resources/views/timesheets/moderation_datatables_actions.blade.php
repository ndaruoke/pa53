
<div class='btn-group'>
    <a href="{{ route('timesheets.moderation.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if( $approval_status != 1 && $approval_status != 2)
    <a href="{{ route('timesheets.moderation.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    </div>
    @endif
</div>

