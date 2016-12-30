@inject('count', 'App\Services\TimesheetCountService')
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Timesheet Approval</h1>
        <br/><br/>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-yellow pull-left">
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
            <div class="small-box bg-red pull-left">
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
                        {!! Form::select('approvalStatus', $approvalStatus, $status, ['class' => 'form-control select2']) !!}
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
        jQuery(document).ready(function ($) {

            //$('.approvalstatus option[value=val2]').attr('selected','selected');
        });
    </script>
@endsection

