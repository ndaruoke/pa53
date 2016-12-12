<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $userLeave->id !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User:') !!}
    <p>{!! $userLeave->users->name !!}</p>
</div>

<!-- Leave Count Field -->
<div class="form-group">
    {!! Form::label('leave_count', 'Leave Count:') !!}
    <p>{!! $userLeave->leave_count !!}</p>
</div>

<!-- Leave Used Field -->
<div class="form-group">
    {!! Form::label('leave_used', 'Leave Used:') !!}
    <p>{!! $userLeave->leave_used !!}</p>
</div>

<!-- Expire Date Field -->
<div class="form-group">
    {!! Form::label('expire_date', 'Expire Date:') !!}
    <p>{!! $userLeave->expire_date !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $userLeave->statuses->name !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $userLeave->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $userLeave->updated_at !!}</p>
</div>

