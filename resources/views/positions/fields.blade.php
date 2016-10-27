<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Hierarchy Field -->
<div class="form-group col-sm-6">
    {!! Form::label('hierarchy', 'Hierarchy:') !!}
    {!! Form::number('hierarchy', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', array(''=>'',1=>'Active',0=>'Inactive'), null, ['class' => 'form-control select2']) !!}
</div>

 @if (isset($position))
<div class="form-group col-sm-12">
    <a href="{{ url('/tunjanganPositions?search=')}}{!!$position->id.'&searchFields=position_id:='!!}">Tunjangan Detail</a>
</div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('positions.index') !!}" class="btn btn-default">Cancel</a>
</div>
