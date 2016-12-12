<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'New Password:') !!}
    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Fill to change']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('confirm_password', 'Confirm Password:') !!}
    {!! Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => 'repeat new password']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
