<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $tunjanganProject->id !!}</p>
</div>

<!-- Project Id Field -->
<div class="form-group">
    {!! Form::label('project_id', 'Project Id:') !!}
    <p>{!! $tunjanganProject->projects->name !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $tunjanganProject->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $tunjanganProject->updated_at !!}</p>
</div>

