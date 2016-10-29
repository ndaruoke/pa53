<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $sequence->id !!}</p>
</div>

<!-- Level Field -->
<div class="form-group">
    {!! Form::label('level', 'Level:') !!}
    <p>{!! $sequence->level !!}</p>
</div>

<!-- Date Field -->
<div class="form-group">
    {!! Form::label('date', 'Date:') !!}
    <p>{!! $sequence->date !!}</p>
</div>

<!-- Role Id Field -->
<div class="form-group">
    {!! Form::label('role_id', 'Role Id:') !!}
    <p>{!! $sequence->roles->name !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $sequence->users->name !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $sequence->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $sequence->updated_at !!}</p>
</div>

