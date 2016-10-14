<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::date('date', null, ['class' => 'form-control']) !!}
</div>

<!-- Note Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('note', 'Note:') !!}
    {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
</div>

<!-- Sequence Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sequence_id', 'Sequence:') !!}
    {!! Form::number('sequence_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Timesheet Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('timesheet_id', 'Timesheet Periode:') !!}
    {!! Form::select('timesheet', $timesheet, null, ['class' => 'form-control']) !!}
    <!--    {!! Form::text('timesheet_id', null, ['class' => 'form-control']) !!} -->
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('approvalHistories.index') !!}" class="btn btn-default">Cancel</a>
</div>
