@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Download</h1>
        <!--<h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
               href="{!! route('users.create') !!}">Add New</a>
        </h1>-->
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
               Panduan Penggunaan Timesheet <br>
                          <a class="btn btn-primary" target="_blank" href="https://drive.google.com/open?id=0B9TELNLJUulQTURQR3pKTkJkZnJldVhKaEh0azhzQWdqT2x3">Download</a>
               
            </div>
        </div>
    </div>
@endsection

