@inject('count', 'App\Services\TimesheetCountService')
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Timesheet Approval</h1>
        <br/><br/>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-red pull-left">
                <div class="inner">
                    <h3>{{$count->timesheetpending}}</h3>
                    <p>Pending Request</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-checkbox-outline-blank"></i>
                </div>

            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-green pull-left">
                <div class="inner">
                    <h3>{{$count->timesheetapproved}}</h3>
                    <p>Approved Request</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-checkbox-outline"></i>
                </div>

            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-blue pull-left">
                <div class="inner">
                    <h3>{{$count->timesheetrejected}}</h3>
                    <p>Rejected Request</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-checkbox-blank"></i>
                </div>

            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">


    <form id="search-form">

        <div class="box-body">
              <div class="input-group input-group-sm">
                <span class="input-group-btn">
                    <button type="reset" class="btn" disabled="disabled">Timesheet</button>
                </span>
                 <select name="termin" id="termin" class="form-control select2" style="width: 40%;">
                  <option value="1">Termin 1</option>
                  <option value="2">Termin 2</option>
                </select>
                <select name="month" id="month" class="form-control select2" style="width: 40%;">
                  <option value="1">Januari</option>
                  <option value="2">Februari</option>
                  <option value="3">Maret</option>
                  <option value="4">April</option>
                  <option value="5">Mei</option>
                  <option value="6">Juni</option>
                  <option value="7">Juli</option>
                  <option value="8">Agustus</option>
                  <option value="9">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
                <select name="year" id="year" class="form-control select2" style="width: 20%;">
                  <option value="2016">2016</option>
                  <option value="2017">2017</option>
                  <option value="2018">2018</option>
                  <option value="2019">2019</option>
                </select>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-info btn-flat">Tampilkan</button>
                </span>
              </div>
            </div>
          
        </form>


            <div class="box-body">
                @include('timesheets.table')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        jQuery(document).ready(function($) {
            $('#search-form').on('submit', function(e) {
                window.alert('hit');
                oTable.draw();
                e.preventDefault();
            });
        });
    </script>
@endsection

