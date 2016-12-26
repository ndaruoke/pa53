@extends('layouts.app')

@section('content')

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

        
 {!! Form::open(['route' => 'add_timesheet.create','id'=>'create_timesheet']) !!}

        <div class="clearfix"></div>

<div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">TIMESHEET SUMMARY</h3>
            </div>
            <div class="box-body">
              <table class="summary project">
<tbody><tr>
    <th>JABODETABEK</th>
    <th style="width:100px"></th>
    <th style="width:100px">HARI</th>
    <th style="width:100px"></th>
    <th>JUMLAH</th>
  </tr>
  <tr>
    <td>Tarif Insentif</td>
    <td></td>
    <td rowspan="5">{{$summary['lokal']['count']}} Hari</td>
    <td></td>
    <td>Rp. {{$summary['lokal']['Insentif Project']}}</td>
  </tr>
  <tr>
    <td>Tarif Transport Lokal</td>
    <td></td>
    <td></td>
    <td>Rp. {{$summary['lokal']['Transport Lokal']}}</td>
  </tr>
  <tr>
    <td>Tarif Insentif Luar Kota</td>
    <td></td>
    <td></td>
    <td>Rp. {{$summary['lokal']['Transport Luar Kota']}}</td>
  </tr>
  <tr>
    
  </tr>
  <tr>
   
  </tr>
  <tr>
    <th>NON LOKAL JAWA</th>
    <td></td>
    
    <td></td>
  </tr>
  <tr>
    <td>Tarif Insentif</td>
    <td></td>
    <td rowspan="5">{{$summary['non_lokal']['count']}} Hari</td>
    <td></td>
    <td>Rp. {{$summary['non_lokal']['Insentif Project']}}</td>
  </tr>
  <tr>
    <td>Tarif Transport Lokal</td>
    <td></td>
    <td></td>
    <td>Rp. {{$summary['non_lokal']['Transport Lokal']}}</td>
  </tr>
  <tr>
    <td>Tarif Insentif Luar Kota</td>
   <td></td>
    <td></td>
    <td>Rp. {{$summary['non_lokal']['Transport Luar Kota']}}</td>
  </tr>
  <tr>
    
  </tr>
  <tr>
    
  </tr>
  <tr>
    <th>LUAR JAWA DALAM NEGRI</th>
    
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Tarif Insentif</td>
    <td></td>
    <td rowspan="5">{{$summary['luar_jawa']['count']}} Hari</td>
    <td></td>
    <td>Rp. {{$summary['luar_jawa']['Insentif Project']}}</td>
  </tr>
  <tr>
    <td>Tarif Transport Lokal</td>
    <td></td>
    <td></td>
    <td>Rp. {{$summary['luar_jawa']['Transport Lokal']}}</td>
  </tr>
  <tr>
    <td>Tarif Insentif Luar Kota</td>
    <td></td>
    <td></td>
    <td>Rp. {{$summary['luar_jawa']['Transport Luar Kota']}}</td>
  </tr>
  <tr>
    
  </tr>
  <tr>
   
  </tr>
  <tr>
    <th>LUAR NEGERI</th>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Tarif Insentif</td>
    <td></td>
    <td rowspan="3">{{$summary['internasional']['count']}} Hari</td>
    <td></td>
    <td>Rp. {{$summary['internasional']['Insentif Project']}}</td>
  </tr>
  <tr>
    <td>Tarif Transport Lokal</td>
    <td></td>
    <td></td>
    <td>Rp. {{$summary['internasional']['Transport Lokal']}}</td>
  </tr>
  <tr>
    <td></td>
    <td></td>

    <td></td>
  </tr>
  <tr>
    <td><b>Tunjangan Bantuan Perumahan</b></td>
    <td></td>
    <td rowspan="1">{{$summary['perumahan']['count']}} Hari</td>
     <td></td>
     <td>Rp. {{$summary['perumahan']['total']}}</td>
  </tr>
  </tr>
  <tr>
    <td><b>Fasilitas Transport Proyek Konsultasi Luar Kota</b></td>
    <td></td>
    <td rowspan="1">{{$summary['adcost']['count']}} Hari</td>
    <td></td>
    <td>Rp. {{$summary['adcost']['total']}}</td>
  </tr>
  </tr>
  <tr>
    <th>TOTAL</th>
    <th></th>
    <th></th>
    <th></th>
    <th>Rp. {{$summary['total']}}</th>
  </tr>
  <tr>
    <th></th>
    
    <th></th>
  <th></th>
  </tr>
  
</tbody></table>
            </div>
          </div>
        </div>



<div class="clearfix"></div>

<div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Timesheet</h3>
            </div>
            <div class="box-body">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Proyek</th>
                  <th>Tanggal</th>
                  <th width="75">Start</th>
                  <th width="75">End</th>
                  <th>Lokasi</th>
				          <th>Aktifitas</th>
				          <th>Keterangan</th>
                  <th>Cek</th>
                </tr>
                @foreach ($timesheet_details as $row=>$detail)
 <tr>
            
            <td>
                {!! Form::select('timesheet['.$row.'][project]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2', 'disabled']) !!}
            </td>
            <td>{{substr($detail->date,0,10)}}{{ Form::hidden('timesheet['.$row.'][date]', str_replace(' 00:00:00','',$detail->date)) }}</td>
             <td><input type="text" name="timesheet[{{$row}}][start]" class="form-control timepicker" placeholder="00:00" value="{{ $detail->start_time }}" disabled="true"></td>
             <td><input type="text" name="timesheet[{{$row}}][end]" class="form-control timepicker" placeholder="00:00" value="{{ $detail->end_time }}" disabled="true"></td>
                  
            <td>
                {!! Form::select('timesheet['.$row.'][lokasi]', [''=>'']+$lokasi, $detail->lokasi, ['class' => 'form-control select2','id'=>'timesheet'.$row.'lokasi', 'disabled']) !!}
			      </td>
			      <td class="col-md-2">
                {!! Form::select('timesheet['.$row.'][activity]', [''=>'']+$activity, $detail->activity, ['class' => 'form-control select2','id'=>'timesheet'.$row.'activity','onchange'=>'onChangeActivity('.$row.')', 'disabled']) !!}				    
			    </td>

            <td>
                <input type="textarea" name="timesheet[{{$row}}][activity_other]" class="form-control" id="timesheet{{$row}}activity_other" value="{{$detail->activity_detail}}" style="display:visible;" disabled="true">
            </td>
            <td class="col-md-1">
                {{ Form::checkbox('timesheet['.$row.'][choose]', true, $detail->selected) }}
			      </td>
                </tr>
@endforeach
                </tbody></table>
            </div>
          </div>
        </div>

<div class="clearfix"></div>

<div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Bantuan Perumahan</h3>
            </div>
            <div class="box-body">
              <table  class="table table-hover small-text" id="tb_insentif">
                <tr class="tr-header">
                <th>Tanggal</th>
                <th>Proyek</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                </th></tr>
                @foreach ($timesheet_insentif as $row=>$detail)
                <tr>
                <td >
                  {{ Form::date('insentif['.$row.'][date]', $detail->date, array('class' => 'form-control','disabled')) }}
                <td >
                  {!! Form::select('insentif['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2', 'disabled']) !!}
                </td>
                <td >
                  {{ Form::text('insentif['.$row.'][value]', $detail->value, array('class' => 'form-control', 'disabled')) }}
                <td>
                <td>
                  {{ Form::text('insentif['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control', 'disabled')) }}
                </td>
                <td class="col-md-1">
                  {{ Form::checkbox('insentif['.$row.'][choose]', true) }}
                </td>
                </tr>
                @endforeach
                </table>
            </div>
          </div>
        </div>

<div class="clearfix"></div>

<div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Transport Proyek Konsultasi Luar Kota</h3>
            </div>
            <div class="box-body">
              <table  class="table table-hover small-text" id="tb_trasnportasi">
                <tr class="tr-header">
                <th>Tanggal</th>
                <th>Proyek</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                </th></tr>
                @foreach ($timesheet_transport as $row=>$detail)
                <tr>
                <td>
                  {{ Form::date('trans['.$row.'][date]', $detail->date, array('class' => 'form-control','disabled')) }}
                <td>
                  {!! Form::select('trans['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2','disabled']) !!}
                </td>
                <td>
                  {{ Form::text('trans['.$row.'][value]', $detail->value, array('class' => 'form-control','disabled')) }}
                <td>
                  {{ Form::text('trans['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control','disabled')) }}
                </td>
                <td class="col-md-1">
                  {{ Form::checkbox('trans['.$row.'][choose]', true) }}
                </td>
                </tr>
                @endforeach
                </table>       
            </div>
          </div>
        </div>

<div class="clearfix"></div>

            
            {{ Form::hidden('edit', $userId) }}
                        
<div class="form-group col-sm-12">
    {!! Form::submit('Submit',['name'=>'action','class' => 'btn btn-primary']) !!}
    {!! Form::submit('Back',['name'=>'action','class' => 'btn btn-warning']) !!}
</div>
<div class="clearfix"></div>
{!! Form::close() !!}
       
    @endif
    </div>

    
    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script>
    $(document).ready(function(){
        for (i = 0; i < 7; i++) { 
    if(($('#select2-timesheet'+i+'activity-container').text() === 'IMPLEMENTASI') || ($('#select2-timesheet'+i+'activity-container').text() === 'MANAGED OPERATION')){
         $('#timesheet'+i+'activity_other').show();
    } else{}
}
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
  else if(selected ==='IMPLEMENTASI'){
      $('#timesheet'+id+'activity_other').show();
    //  $('#timesheet'+id+'lokasi').val("").trigger("change");
      $('#timesheet'+id+'lokasi').prop("disabled", false);
  } 

   else if(selected ==='MANAGED OPERATION'){
      $('#timesheet'+id+'activity_other').show();
    //  $('#timesheet'+id+'lokasi').val("").trigger("change");
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
 '   <td><input type="date" name="trans['+id+'][date]" value="{!!date("Y-m-d")!!}" class="form-control" ></td>  '  + 
 '   <td><select class="form-control" name="trans['+id+'][project_id]"  ><option value="" selected="selected"></option>' +
 '<?php
foreach ($project as $key=>$value){
    echo ' <option value="'.$key.'">'.$value.'</option>';
}

?>'+
 '</select></td>'  + 
 '   <td><input type="text" name="trans['+id+'][value]" class="form-control"  ></td>  '  + 
 '   <td><input type="text" name="trans['+id+'][desc]" class="form-control"  ></td>  '  + 
 '   <td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>  '  + 
 '   </tr>  '  + 
 '    ' ; 
        return row;
    }

    function getRowInsentif(id){
         var row =  '<tr>'  + 
 '   <td><input type="date" name="insentif['+id+'][date]" value="{!!date("Y-m-d")!!}" class="form-control"  ></td>  '  + 
 '   <td><select class="form-control" name="insentif['+id+'][project_id]"><option value="" selected="selected"  ></option>' +
 '<?php
foreach ($project as $key=>$value){
    echo ' <option value="'.$key.'">'.$value.'</option>';
}

?>'+
 '</select></td>'  + 
 '   <td><input type="text" name="insentif['+id+'][value]" class="form-control" ></td>  '  + 
 '   <td><input type="text" name="insentif['+id+'][desc]" class="form-control" ></td>  '  + 
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


