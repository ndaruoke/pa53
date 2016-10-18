<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $audit->id !!}</p>
</div>

<!-- Type Field -->
<div class="form-group">
    {!! Form::label('type', 'Type:') !!}
    <p>{!! $audit->type !!}</p>
</div>

<!-- Auditable Id Field -->
<div class="form-group">
    {!! Form::label('auditable_id', 'Auditable Id:') !!}
    <p>{!! $audit->auditable_id !!}</p>
</div>

<!-- Auditable Type Field -->
<div class="form-group">
    {!! Form::label('auditable_type', 'Auditable Type:') !!}
    <p>{!! $audit->auditable_type !!}</p>
</div>

<!-- Old Field -->
<div class="form-group">
    {!! Form::label('old', 'Old:') !!}
    <p>{!! $audit->old !!}</p>
</div>

<!-- New Field -->
<div class="form-group">
    {!! Form::label('new', 'New:') !!}
    <p>{!! $audit->new !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $audit->user_id !!}</p>
</div>

<!-- Route Field -->
<div class="form-group">
    {!! Form::label('route', 'Route:') !!}
    <p>{!! $audit->route !!}</p>
</div>

<!-- Ip Address Field -->
<div class="form-group">
    {!! Form::label('ip_address', 'Ip Address:') !!}
    <p>{!! $audit->ip_address !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $audit->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $audit->updated_at !!}</p>
</div>

