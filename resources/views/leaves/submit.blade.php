@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Leaves</h1>
        <br/><br/>
        <div class="small-box bg-red pull-left">
            <div class="inner">
                <h3> {!! is_null($userLeave)?"NA":$userLeave->leave_used !!} Dari {!! is_null($userLeave)?"NA":$userLeave->leave_count !!}</h3>

                <p>Jatah Cuti Terpakai</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>

        </div>
        <h1 class="pull-right">
            <a class="btn btn-primary btn-block " style="margin-top: -10px;margin-bottom: 5px" href="{!! route('leaves.create') !!}">Ajukan Cuti</a>
        </h1>
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

