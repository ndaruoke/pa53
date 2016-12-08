@extends('layouts.app')

@section('content')

<!--    <section class="content-header">
        <h1 class="pull-left">Create</h1>
        <h1 class="pull-right">
       
        </h1>
    </section>-->
    <div class="content">
    @include('flash::message')
 
@if(!isset($_POST['week']))
<hr>
    <?php function getListDate($y,$m,$w){
	if($w>2){
	$period = 1;
	} else {
	$period = 2;
	}
	
	$totalDay = cal_days_in_month(CAL_GREGORIAN,$m,$y);
    $totalDayWeek = 7;
    if($w==4){
        $totalDayWeek = $totalDay-21;
    }
	$listDate;
	for($i=1;$i<$totalDayWeek+1;$i++){
		$listDate[]= $y.'-'.$m.'-'.$i;
	}
	
	return array('period'=>$period,'week'=>$w,'year'=>$y,'listDate' => $listDate);

} 

?>


        <div class="clearfix"></div>

        
            {!! Form::open(['route' => 'add_timesheet.create']) !!}

        <div class="clearfix"></div>

                  <div class="nav-tabs-custom" id="timesheet_tab">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Timesheet</a></li>
              <li><a href="#tab_2" data-toggle="tab">Insentif</a></li>
              <li><a href="#tab_3" data-toggle="tab">Transport</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                 <table class="table table-hover">
                <tbody><tr>
                  <th>Proyek</th>
                  <th>Tanggal</th>
                  <th width="70">Start</th>
                  <th width="70">End</th>
                  <th>Lokasi</th>
				  <th>Aktifitas</th>
				  <th>Keterangan</th>
                </tr>
                @foreach ($timesheet_details as $row=>$detail)
 <tr>
                  <td>
{!! Form::select('timesheet['.$row.'][project]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2']) !!}
				  </td>
                  <td>{{str_replace(' 00:00:00','',$detail->date)}}{{ Form::hidden('timesheet['.$row.'][date]', str_replace(' 00:00:00','',$detail->date)) }}</td>
             <td><input type="text" name="timesheet[{{$row}}][start]" class="form-control timepicker" placeholder="00:00" value="{{ $detail->start_time }}"></td>
             <td><input type="text" name="timesheet[{{$row}}][end]" class="form-control timepicker" placeholder="00:00" value="{{ $detail->end_time }}"></td>
                  
                  <td>
{!! Form::select('timesheet['.$row.'][lokasi]', [''=>'']+$lokasi, $detail->lokasi, ['class' => 'form-control select2','id'=>'timesheet'.$row.'lokasi']) !!}
			</td>
			<td class="col-md-2">
{!! Form::select('timesheet['.$row.'][activity]', [''=>'']+$activity, $detail->activity, ['class' => 'form-control select2','id'=>'timesheet'.$row.'activity','onchange'=>'onChangeActivity('.$row.')']) !!}				    
            <input type="text" name="timesheet[{{$row}}][activity_other]" class="form-control" id="timesheet{{$row}}activity_other" style="display:none;">
			</td>

                  <td><input type="text" name="timesheet[{{$row}}][keterangan]" class="form-control" placeholder="Keterangan"></td>
                </tr>
@endforeach
                </tbody></table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <table  class="table table-hover small-text" id="tb_insentif">
                <tr class="tr-header">
                <th>Tanggal</th>
                <th>Proyek</th>
                <th>Insentif</th>
                <th>Keterangan</th>
                <th><a href="javascript:void(0);" style="font-size:18px;" id="addInsentif" title="Add Insentif"><span class="glyphicon glyphicon-plus"></span></a>
                </th></tr>
                @foreach ($timesheet_insentif as $row=>$detail)
                <tr>
                <td>
                {{ Form::text('insentif['.$row.'][date]', $detail->date, array('class' => 'form-control')) }}
                <td>
                {!! Form::select('insentif['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2']) !!}
                </td>
                <td>
                {{ Form::text('insentif['.$row.'][value]', $detail->value, array('class' => 'form-control')) }}
                <td>
                {{ Form::text('insentif['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control')) }}
                </td><td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>
                @endforeach
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                <table  class="table table-hover small-text" id="tb_trasnportasi">
                <tr class="tr-header">
                <th>Tanggal</th>
                <th>Proyek</th>
                <th>Transportasi</th>
                <th>Keterangan</th>
                <th><a href="javascript:void(0);" style="font-size:18px;" id="addTransportasi" title="Add Transportasi"><span class="glyphicon glyphicon-plus"></span></a>
                 </th></tr>
                @foreach ($timesheet_transport as $row=>$detail)
                <tr>
                <td>
                {{ Form::text('trans['.$row.'][date]', $detail->date, array('class' => 'form-control')) }}
                <td>
                {!! Form::select('trans['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2']) !!}
                </td>
                <td>
                {{ Form::text('trans['.$row.'][value]', $detail->value, array('class' => 'form-control')) }}
                <td>
                {{ Form::text('trans['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control')) }}
                </td><td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>
                @endforeach
                </table>        
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
            
            {{ Form::hidden('edit', $id) }}
            {{ Form::hidden('month', $timesheet->month) }}
            {{ Form::hidden('year', $timesheet->year) }}
            {{ Form::hidden('week', $timesheet->week) }}
            {{ Form::hidden('period', getListDate($timesheet->year,$timesheet->month,$timesheet->week)['period']) }}

<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
       
    @endif
    </div>

    
    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script>
    $(document).ready(function(){
        $('.content').find('.select2-container--default').removeAttr("style");
        $('.content').find('.select2-container--default').css('width','100%');
    })
    
function onChangeActivity(id){
    setTimeout(function() {
  var selected = $("[id*=select2-timesheet"+id+"activity]").text();
  if(selected ==='SUPPORT'){
      $('#timesheet'+id+'lokasi').val("UNCLAIMABLE").trigger("change");
      $('#timesheet'+id+'lokasi').prop("disabled", true);
  } 
  else if(selected ==='OTHERS'){
      $('#timesheet'+id+'activity_other').show();
   //   $('#timesheet'+id+'lokasi').val("").trigger("change");
      $('#timesheet'+id+'lokasi').prop("disabled", false);
  } 
  else {
      $('#timesheet'+id+'activity_other').hide();
  //    $('#timesheet'+id+'lokasi').val("").trigger("change");
      $('#timesheet'+id+'lokasi').prop("disabled", false);
  }
  
}, 50);
    
}
  $(".timepicker").timepicker({
      showInputs: false,
      showMeridian : false
    });

    function getRowTransport(id){
         var row =  '<tr>'  + 
 '   <td><input type="text" name="trans['+id+'][date]" value="{!!date("Y-m-d")!!}" class="form-control" required></td>  '  + 
 '   <td><select class="form-control" name="trans['+id+'][project_id]"><option value="" selected="selected"></option>' +
 '<?php
foreach ($project as $key=>$value){
    echo ' <option value="'.$key.'">'.$value.'</option>';
}

?>'+
 '</select></td>'  + 
 '   <td><input type="text" name="trans['+id+'][value]" class="form-control"></td>  '  + 
 '   <td><input type="text" name="trans['+id+'][desc]" class="form-control"></td>  '  + 
 '   <td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>  '  + 
 '   </tr>  '  + 
 '    ' ; 
        return row;
    }

    function getRowInsentif(id){
         var row =  '<tr>'  + 
 '   <td><input type="text" name="insentif['+id+'][date]" value="{!!date("Y-m-d")!!}" class="form-control" ></td>  '  + 
 '   <td><select class="form-control" name="insentif['+id+'][project_id]"><option value="" selected="selected"></option>' +
 '<?php
foreach ($project as $key=>$value){
    echo ' <option value="'.$key.'">'.$value.'</option>';
}

?>'+
 '</select></td>'  + 
 '   <td><input type="text" name="insentif['+id+'][value]" class="form-control"></td>  '  + 
 '   <td><input type="text" name="insentif['+id+'][desc]" class="form-control"></td>  '  + 
 '   <td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>  '  + 
 '   </tr>  '  + 
 '    ' ; 
        return row;
    }

$(function(){
   var id={!! count($timesheet_transport) !!};
    $('#addTransportasi').on('click', function() {
            //  var data = row.appendTo("#tb_trasnportasi");
             // data.find("input").val('');
            // $("#tb_trasnportasi").append(row);
             $(getRowTransport(id)).appendTo("#tb_trasnportasi");
             id++;
     });
     $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
             $(this).closest("tr").remove();
      });
});      

$(function(){
    var id={!! count($timesheet_insentif) !!};
    $('#addInsentif').on('click', function() {
            //   var data = $("#tb_copy tr:eq(1)").clone(true).appendTo("#tb_insentif");
            //   data.find("input").val('');
             $(getRowInsentif(id)).appendTo("#tb_insentif");
             id++;
     });
     $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
             $(this).closest("tr").remove();
      });
});     


</script>

@endsection
@endsection


