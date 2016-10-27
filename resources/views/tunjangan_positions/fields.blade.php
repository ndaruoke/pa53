<!-- Tunjangan Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tunjangan_id', 'Tunjangan:') !!}
    {!! Form::select('tunjangan_id', $tunjangans, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Position Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('position_id', 'Position:') !!}
    {!! Form::select('position_id', $positions, app('request')->input('position_id'), ['class' => 'form-control select2']) !!}
</div>

<!-- Lokal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lokal', 'Lokal:') !!}
    {!! Form::number('lokal', null, ['class' => 'form-control']) !!}
</div>

<!-- Non Lokal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('non_lokal', 'Non Lokal:') !!}
    {!! Form::number('non_lokal', null, ['class' => 'form-control']) !!}
</div>

<!-- Luar Jawa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('luar_jawa', 'Luar Jawa:') !!}
    {!! Form::number('luar_jawa', null, ['class' => 'form-control']) !!}
</div>

<!-- Internasional Field -->
<div class="form-group col-sm-6">
    {!! Form::label('internasional', 'Internasional:') !!}
    {!! Form::number('internasional', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tunjanganPositions.index') !!}" class="btn btn-default">Cancel</a>
</div>
