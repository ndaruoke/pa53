@extends('layouts.app')

@section('content')



 {!! Form::open(['route' => 'add_timesheet.form']) !!}


<div class="box-body">
              <div class="input-group input-group-sm">
                <span class="input-group-btn">
                    <button type="reset" class="btn" disabled="disabled">Timesheet</button>
                </span>
                 <select name="week" class="form-control select2" style="width: 40%;">
                  <option value="01">Week 1</option>
                  <option value="02">Week 2</option>
                  <option value="03">Week 3</option>
                  <option value="04">Week 4</option>
                </select>
                <select name="month" class="form-control select2" style="width: 40%;">
                  <option value="01">Januari</option>
                  <option value="02">Februari</option>
                  <option value="03">Maret</option>
                  <option value="04">April</option>
                  <option value="05">Mei</option>
                  <option value="06">Juni</option>
                  <option value="07">Juli</option>
                  <option value="08">Agustus</option>
                  <option value="09">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
                <select name="year" class="form-control select2" style="width: 20%;">
                  <option>2016</option>
                  <option>2017</option>
                  <option>2016</option>
                  <option>2017</option>
                </select>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-info btn-flat">Tampilkan</button>
                </span>
              </div>
            </div>
          
        </form>

        

<!--    <section class="content-header">
        <h1 class="pull-left">Create</h1>
        <h1 class="pull-right">
       
        </h1>
    </section>-->
    <div class="content">
    @include('flash::message')
    
@if(isset($_POST['week']))
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

                  <div class="nav-tabs-custom">
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
           
<?php
if(isset($_POST['week'])){
   foreach (getListDate($_POST["year"],$_POST["month"],$_POST["week"])['listDate'] as $row=>$date){
       ?>
       <tr>
                  <td>
{!! Form::select('timesheet['.$row.'][project]', [''=>'']+$project, null, ['class' => 'form-control select2']) !!}
				  </td>
                  <td>{{$date}}{{ Form::hidden('timesheet['.$row.'][date]', $date) }}</td>
             <td><input type="text" name="timesheet[{{$row}}][start]" class="form-control timepicker" placeholder="00:00"></td>
             <td><input type="text" name="timesheet[{{$row}}][end]" class="form-control timepicker" placeholder="00:00"></td>
                  
                  <td>

<select name="timesheet[{{$row}}][lokasi]" class="form-control select2" >
                  <option selected="selected">JABODETABEK</option>
                  <option>DOMESTIK P. JAWA</option>
                  <option>DOMESTIK L. JAWA</option>
                  <option>INTERNATIONAL</option>
				  <option>UNCLAIMABLE</option>
                </select>

				  </td>
				  <td>
				    
<select name="timesheet[{{$row}}][activity]" class="form-control select2" >
                  <option selected="selected" style="font-size:x-small;">CUTI</option>
                  <option>LIBUR</option>
                  <option>IDLE</option>
                  <option>SAKIT</option>
				  <option>SUPPORT</option>
				  <option>OTHERS</option>
                </select>
				  </td>

                  <td><input type="text" name="timesheet[{{$row}}][keterangan]" class="form-control" placeholder="Keterangan"></td>
                </tr>

       <?php
   }
}
?>

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
                <th><a href="javascript:void(0);" style="font-size:18px;" id="addInsentif" title="Add Insentif"><span class="glyphicon glyphicon-plus"></span></a></th>
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
                <th><a href="javascript:void(0);" style="font-size:18px;" id="addTransportasi" title="Add Transportasi"><span class="glyphicon glyphicon-plus"></span></a></th>
                </table>        
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
@if(isset($_POST['week']))
{{ Form::hidden('month', $_POST['month']) }}
{{ Form::hidden('year', $_POST['year']) }}
{{ Form::hidden('week', $_POST['week']) }}
{{ Form::hidden('period', getListDate($_POST["year"],$_POST["month"],$_POST["week"])['period']) }}
@endif
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
       
    @endif
    </div>

    
    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script>

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
   var id=0;
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
    var id=0;
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

