<!-- Type Field->
   <div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
 </div>

  <!-- Auditable Id Field->
  <div class="form-group col-sm-6">
        {!! Form::label('auditable_id', 'Auditable Id:') !!}
        {!! Form::text('auditable_id', null, ['class' => 'form-control']) !!}
    </div>

  <!-- Auditable Type Field->
  <div class="form-group col-sm-6">
        {!! Form::label('auditable_type', 'Auditable Type:') !!}
        {!! Form::text('auditable_type', null, ['class' => 'form-control']) !!}
    </div>

  <!-- Old Field->
  <div class="form-group col-sm-6">
        {!! Form::label('old', 'Old:') !!}
        {!! Form::text('old', null, ['class' => 'form-control']) !!}
    </div>

  <!-- New Field->
  <div class="form-group col-sm-6">
        {!! Form::label('new', 'New:') !!}
        {!! Form::text('new', null, ['class' => 'form-control']) !!}
    </div>

  <!-- User Id Field->
  <div class="form-group col-sm-6">
        {!! Form::label('user_id', 'User Id:') !!}
        {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
    </div>

  <!-- Route Field->
  <div class="form-group col-sm-6">
        {!! Form::label('route', 'Route:') !!}
        {!! Form::text('route', null, ['class' => 'form-control']) !!}
    </div>

  <!-- Ip Address Field->
  <div class="form-group col-sm-6">
        {!! Form::label('ip_address', 'Ip Address:') !!}
        {!! Form::text('ip_address', null, ['class' => 'form-control']) !!}
    </div>

  <!-- Submit Field->
  <div class="form-group col-sm-12">
         {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('audits.index') !!}" class="btn btn-default">Cancel</a>
</div>

