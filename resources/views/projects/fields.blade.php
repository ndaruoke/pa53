<!-- Project Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('project_name', 'Project Name:') !!}
    {!! Form::text('project_name', null, ['class' => 'form-control']) !!}
</div>


<!-- Pm User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pm_user_id', 'Project Manager:') !!}
    {!! Form::select('pm_user_id', [''=>'']+$user, null, ['class' => 'form-control select2']) !!}
</div>
<!-- Iwo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('iwo', 'Iwo:') !!}
    {!! Form::text('iwo', null, ['class' => 'form-control']) !!}
</div>

<!-- Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Code:') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<!-- Claimable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('claimable', 'Claimable:') !!}

    {!! Form::select('claimable', array(''=>'',1=>'Yes',0=>'No'), null, ['class' => 'form-control select2']) !!}
</div>

<!-- Departent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('departent_id', 'Departent Id:') !!}
    {!! Form::select('departent_id', [''=>'']+$department, null, ['class' => 'form-control select2']) !!}
</div>
<div class="form-group col-sm-12">
    {!! Form::label('Tunjangan', 'Tunjangan :') !!}
</div>
<div class="col-xs-5">
{!! Form::select('from[]', $tunjangan, null, ['class' => 'form-control','multiple'=>'multiple','id'=>'search','size'=>'8']) !!}
<!--   <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
            <option value="1">Item 1</option>
            <option value="2">Item 5</option>
            <option value="2">Item 2</option>
            <option value="2">Item 4</option>
            <option value="3">Item 3</option>
        </select> -->
</div>

<div class="col-xs-2">
    <button type="button" id="search_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
    <button type="button" id="search_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
    <button type="button" id="search_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
    <button type="button" id="search_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
</div>

<div class="col-xs-5">
    <select name="tunjangan[]" id="search_to" class="form-control" size="8" multiple="multiple">
        @if (empty($selected_tunjangan))
        @else
            @foreach ($selected_tunjangan as $stj)
                <option value="{{ $stj->tunjangan['id'] }}">{{ $stj->tunjangan['name'] }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('Member', 'Member :') !!}
</div>
<div class="col-xs-5">
{!! Form::select('from[]', $user, null, ['class' => 'form-control','multiple'=>'multiple','id'=>'search2','size'=>'8']) !!}
<!--   <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
            <option value="1">Item 1</option>
            <option value="2">Item 5</option>
            <option value="2">Item 2</option>
            <option value="2">Item 4</option>
            <option value="3">Item 3</option>
        </select> -->
</div>

<div class="col-xs-2">
    <button type="button" id="search_rightAll2" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
    <button type="button" id="search_rightSelected2" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
    <button type="button" id="search_leftSelected2" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
    <button type="button" id="search_leftAll2" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
</div>

<div class="col-xs-5">
    <select name="member[]" id="member_to" class="form-control" size="8" multiple="multiple">
        @if (empty($selected_member))
        @else
            @foreach ($selected_member as $stm)
                <option value="{{ $stm->user['id'] }}">{{ $stm->user['name'] }}</option>
            @endforeach
        @endif
    </select>
</div>
<!-- Tunjangan List Field -->
<!--<div class="form-group col-sm-6">
    {!! Form::label('tunjangan_list', 'Tunjangan List:') !!}
{!! Form::text('tunjangan_list', null, ['class' => 'form-control']) !!}
        </div>-->
<div class="form-group col-sm-12">
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('projects.index') !!}" class="btn btn-default">Cancel</a>
</div>

