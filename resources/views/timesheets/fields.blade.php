<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Periode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('periode', 'Periode:') !!}
    {!! Form::date('periode', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('timesheets.index') !!}" class="btn btn-default">Cancel</a>
</div>
