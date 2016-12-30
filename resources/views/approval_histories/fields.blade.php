<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::date('date', null, ['class' => 'form-control']) !!}
</div>

<!-- Note Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('note', 'Note:') !!}
    {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
</div>

<!-- Sequence Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sequence_id', 'Sequence:') !!}
    {!! Form::number('sequence_id', null, ['class' => 'form-control']) !!}
</div>

@if( ! empty($timesheets))
    <!-- Timesheet Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('transaction_id', 'Timesheet Periode:') !!}
        {!! Form::select('transaction_id', $timesheets, null, ['class' => 'form-control select2']) !!}
    </div>
@endif

@if( ! empty($leaves))
    <!-- Leave Id Field -->
    <div class="form-group col-sm-6">
    {!! Form::label('transaction_id', 'Leave Note:') !!}
    {!! Form::select('transaction_id', $leaves, null, ['class' => 'form-control select2']) !!}
    <!--    {!! Form::text('timesheet_id', null, ['class' => 'form-control']) !!} -->
    </div>
@endif

<!-- Transaction Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transaction_type', 'Transaction Type:') !!}
    {!! Form::select('transaction_type', $transactiontypes, null, ['class' => 'form-control select2']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User:') !!}
    {!! Form::select('user_id', $users, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Approval Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('approval_status', 'Approval Status:') !!}
    {!! Form::select('approval_status', $approvalstatuses, null, ['class' => 'form-control select2']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('approvalHistories.index') !!}" class="btn btn-default">Cancel</a>
</div>
