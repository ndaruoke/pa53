<!-- Role Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('role_id', 'Role:') !!}
    {!! Form::text('role_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Module Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('module_id', 'Module Id:') !!}
    {!! Form::text('module_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('roleAccesses.index') !!}" class="btn btn-default">Cancel</a>
</div>
