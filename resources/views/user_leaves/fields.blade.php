<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::select('user_id', $users, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Leave Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('leave_count', 'Leave Count:') !!}
    {!! Form::number('leave_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Expire Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('expire_date', 'Expire Date:') !!}
    {!! Form::date('expire_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', $statuses, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('userLeaves.index') !!}" class="btn btn-default">Cancel</a>
</div>
