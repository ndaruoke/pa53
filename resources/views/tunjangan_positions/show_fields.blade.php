<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $tunjanganPosition->id !!}</p>
</div>

<!-- Tunjangan Id Field -->
<div class="form-group">
    {!! Form::label('tunjangan_id', 'Tunjangan:') !!}
    <p>{!! $tunjanganPosition->tunjangans->name !!}</p>
</div>

<!-- Position Id Field -->
<div class="form-group">
    {!! Form::label('position_id', 'Position:') !!}
    <p>{!! $tunjanganPosition->positions->name !!}</p>
</div>

<!-- Lokal Field -->
<div class="form-group">
    {!! Form::label('lokal', 'Lokal:') !!}
    <p>{!! $tunjanganPosition->lokal !!}</p>
</div>

<!-- Non Lokal Field -->
<div class="form-group">
    {!! Form::label('non_lokal', 'Non Lokal:') !!}
    <p>{!! $tunjanganPosition->non_lokal !!}</p>
</div>

<!-- Luar Jawa Field -->
<div class="form-group">
    {!! Form::label('luar_jawa', 'Luar Jawa:') !!}
    <p>{!! $tunjanganPosition->luar_jawa !!}</p>
</div>

<!-- Internasional Field -->
<div class="form-group">
    {!! Form::label('internasional', 'Internasional:') !!}
    <p>{!! $tunjanganPosition->internasional !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $tunjanganPosition->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $tunjanganPosition->updated_at !!}</p>
</div>

