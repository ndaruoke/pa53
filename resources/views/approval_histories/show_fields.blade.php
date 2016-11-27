<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $approvalHistory->id !!}</p>
</div>

<!-- Date Field -->
<div class="form-group">
    {!! Form::label('date', 'Date:') !!}
    <p>{!! $approvalHistory->date !!}</p>
</div>

<!-- Note Field -->
<div class="form-group">
    {!! Form::label('note', 'Note:') !!}
    <p>{!! $approvalHistory->note !!}</p>
</div>

<!-- Sequence Id Field -->
<div class="form-group">
    {!! Form::label('sequence_id', 'Sequence Id:') !!}
    <p>{!! $approvalHistory->sequence_id !!}</p>
</div>

@if( ! empty($approvalHistory->timesheets))
<!-- Timesheet Id Field -->
<div class="form-group">
    {!! Form::label('transaction_id', 'Timesheet:') !!}
    <p>{!! $approvalHistory->timesheets->periode !!}</p>
</div>
@endif

@if( ! empty($approvalHistory->leaves))
<!-- Timesheet Id Field -->
<div class="form-group">
    {!! Form::label('transaction_id', 'Leave:') !!}
    <p>{!! $approvalHistory->leaves->name !!}</p>
</div>
@endif

<!-- Sequence Id Field -->
<div class="form-group">
    {!! Form::label('approval_status', 'Approval Status:') !!}
    <p>{!! $approvalHistory->approvalstatuses->name !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $approvalHistory->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $approvalHistory->updated_at !!}</p>
</div>

