<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $accessModule->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $accessModule->name !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $accessModule->description !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <<<<<<< HEAD:resources/views/access_modules/show_fields.blade.php
    <p>{!! $accessModule->status !!}</p>
    =======
    <p>{!! $position->status == 1 ? "Active" : "Inactive" !!}</p>
    >>>>>>> origin/master:resources/views/positions/show_fields.blade.php
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $accessModule->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $accessModule->updated_at !!}</p>
</div>

