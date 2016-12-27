@inject('count', 'App\Services\LeaveCountService')
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Leave Approval</h1>
        <br/><br/>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="small-box bg-yellow pull-left">
                <div class="inner">
                    <h3>{{$count->leavepending}}</h3>
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
                    <h3>{{$count->leaveapproved}}</h3>
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
                    <h3>{{$count->leaverejected}}</h3>
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
            <div class="box-body">
                @include('leaves.table')
            </div>
        </div>
    </div>
@endsection

