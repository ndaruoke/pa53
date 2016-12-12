<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $project->id !!}</p>
</div>

<!-- Project Name Field -->
<div class="form-group">
    {!! Form::label('project_name', 'Project Name:') !!}
    <p>{!! $project->project_name !!}</p>
</div>

<!-- Tunjangan List Field -->
<div class="form-group">
    {!! Form::label('tunjangan_list', 'Tunjangan List:') !!}
    <p>{!! $project->tunjangan_list !!}</p>
</div>

<!-- Iwo Field -->
<div class="form-group">
    {!! Form::label('budget', 'Budget:') !!}
    <p>{!! $project->budget !!}</p>
</div>

<!-- Code Field -->
<div class="form-group">
    {!! Form::label('code', 'Code:') !!}
    <p>{!! $project->code !!}</p>
</div>

<!-- Claimable Field -->
<div class="form-group">
    {!! Form::label('claimable', 'Claimable:') !!}
    <p>{!! $project->claimable !!}</p>
</div>

<!-- Department Id Field -->
<div class="form-group">
    {!! Form::label('department_id', 'Department:') !!}
    <p>{!! $project->departments->name !!}</p>
</div>

<!-- Pm User Id Field -->
<div class="form-group">
    {!! Form::label('pm_user_id', 'Pm User:') !!}
    <p>{!! $project->users->name !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $project->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $project->updated_at !!}</p>
</div>

