@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Timesheets</h1>
        <h1 class="pull-right">
        <!--
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('timesheets.create') !!}">Add New</a>
        -->
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                 <table class="table table-hover">
                <tbody><tr>
                  <th>Proyek</th>
                  <th>Tanggal</th>
                  <th>Waktu</th>
                  <th width="150">Lokasi</th>
				  <th width="150">Aktifitas</th>
				  <th>Keterangan</th>
                </tr>
           
				
				<tr>
                  <td>
{!! Form::select('project_id', [''=>'']+$project, null, ['class' => 'form-control select2']) !!}
				  </td>
                  <td>01/08/2016</td>
             <td><input type="text" name="q" class="form-control" placeholder="00:00-00:00"></td>
                  <td>

<select class="form-control select2" >
                  <option selected="selected">JABODETABEK</option>
                  <option>DOMESTIK P. JAWA</option>
                  <option>DOMESTIK L. JAWA</option>
                  <option>INTERNATIONAL</option>
				  <option>UNCLAIMABLE</option>
                </select>

				  </td>
				  <td>
				    
<select class="form-control select2" >
                  <option selected="selected" style="font-size:x-small;">CUTI</option>
                  <option>LIBUR</option>
                  <option>IDLE</option>
                  <option>SAKIT</option>
				  <option>SUPPORT</option>
				  <option>OTHERS</option>
                </select>
				  </td>

                  <td><input type="text" name="q" class="form-control" placeholder="Keterangan"></td>
                </tr>
				
				
				
				
				
				
				
              </tbody></table>
            </div>
        </div>

Insentif
<div class="box box-primary">
            <div class="box-body">
<table  class="table table-hover small-text" id="tb_insentif">
<tr class="tr-header">
<th>Tanggal</th>
<th>Proyek</th>
<th>Insentif</th>
<th>Keterangan</th>
<th><a href="javascript:void(0);" style="font-size:18px;" id="addInsentif" title="Add Insentif"><span class="glyphicon glyphicon-plus"></span></a></th>
<tr>
<td><input type="text" name="fullname[]" class="form-control"></td>
<td>{!! Form::select('project_id', [''=>'']+$project, null, ['class' => 'form-control select2']) !!}</td>
<td><input type="text" name="mobileno[]" class="form-control"></td>
<td><input type="text" name="emailid[]" class="form-control"></td>
<td><a href='javascript:void(0);'  class='remove'><span class='glyphicon glyphicon-remove'></span></a></td>
</tr>
</table>
</div></div>

Transportasi
<div class="box box-primary">
            <div class="box-body">
<table  class="table table-hover small-text" id="tb_trasnportasi">
<tr class="tr-header">
<th>Tanggal</th>
<th>Proyek</th>
<th>Transportasi</th>
<th>Keterangan</th>
<th><a href="javascript:void(0);" style="font-size:18px;" id="addTransportasi" title="Add Transportasi"><span class="glyphicon glyphicon-plus"></span></a></th>
<tr>
<td><input type="text" name="fullname[]" class="form-control"></td>
<td>{!! Form::select('project_id', [''=>'']+$project, null, ['class' => 'form-control select2']) !!}</td>
<td><input type="text" name="mobileno[]" class="form-control"></td>
<td><input type="text" name="emailid[]" class="form-control"></td>
<td><a href='javascript:void(0);'  class='remove'><span class='glyphicon glyphicon-remove'></span></a></td>
</tr>
</table>
</div></div>

    </div>
    @section('scripts')
    <script>

$(function(){
    $('#addTransportasi').on('click', function() {
              var data = $("#tb_trasnportasi tr:eq(1)").clone(true).appendTo("#tb_trasnportasi");
              data.find("input").val('');
     });
     $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
            // alert("Sorry!! Can't remove first row!");
           }
      });
});      

$(function(){
    $('#addInsentif').on('click', function() {
              var data = $("#tb_insentif tr:eq(1)").clone(true).appendTo("#tb_insentif");
              data.find("input").val('');
     });
     $(document).on('click', '.remove', function() {
         var trIndex = $(this).closest("tr").index();
            if(trIndex>1) {
             $(this).closest("tr").remove();
           } else {
          //   alert("Sorry!! Can't remove first row!");
           }
      });
});     
</script>

@endsection
@endsection


