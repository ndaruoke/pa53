<!-- Project Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('project_id', 'Project Id:') !!}
    <!--{!! Form::text('project_id', null, ['class' => 'form-control']) !!}-->
        {!! Form::select('project_id', $projects, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tunjanganProjects.index') !!}" class="btn btn-default">Cancel</a>
</div>
