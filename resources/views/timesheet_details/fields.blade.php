<!-- Lokasi Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lokasi', 'Lokasi:') !!}
    {!! Form::text('lokasi', null, ['class' => 'form-control']) !!}
</div>

<!-- Activity Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('activity', 'Activity:') !!}
    {!! Form::textarea('activity', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::date('date', null, ['class' => 'form-control']) !!}
</div>

<!-- Start Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_time', 'Start Time:') !!}
    {!! Form::date('start_time', null, ['class' => 'form-control']) !!}
</div>

<!-- End Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_time', 'End Time:') !!}
    {!! Form::date('end_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Timesheet Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('timesheet_id', 'Timesheet Id:') !!}
    {!! Form::text('timesheet_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Leave Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('leave_id', 'Leave Id:') !!}
    {!! Form::text('leave_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Project Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('project_id', 'Project Id:') !!}
    {!! Form::text('project_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('timesheetDetails.index') !!}" class="btn btn-default">Cancel</a>
</div>
