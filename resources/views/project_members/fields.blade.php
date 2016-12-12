<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User:') !!}
    {!! Form::select('user_id', $user, null, ['class' => 'form-control']) !!}
    <!-- {!! Form::text('user_id', null, ['class' => 'form-control']) !!} -->
</div>

<!-- Project Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('project_id', 'Project:') !!}
    {!! Form::select('project_id', $project, null, ['class' => 'form-control']) !!}
    <!-- {!! Form::text('project_id', null, ['class' => 'form-control']) !!} -->
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('projectMembers.index') !!}" class="btn btn-default">Cancel</a>
</div>
