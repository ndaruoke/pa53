<!-- Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_date', 'Start Date:') !!}
    {!! Form::date('start_date', null, ['class' => 'form-control']) !!}
</div>

<!-- End Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_date', 'End Date:') !!}
    {!! Form::date('end_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Approval Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('approval_id', 'Approval:') !!}
    {!! Form::select('approval_id', $users, null, ['class' => 'form-control select2']) !!}
    <!-- {!! Form::text('approval_id', null, ['class' => 'form-control']) !!} -->
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', $statuses, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('leaves.index') !!}" class="btn btn-default">Cancel</a>
</div>
