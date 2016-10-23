<!-- Tunjangan Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tunjangan_id', 'Tunjangan Id:') !!}
    {!! Form::text('tunjangan_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Role Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('role_id', 'Role Id:') !!}
    {!! Form::text('role_id', null, ['class' => 'form-control']) !!}
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
    <a href="{!! route('tunjanganRoles.index') !!}" class="btn btn-default">Cancel</a>
</div>
