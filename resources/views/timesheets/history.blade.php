@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Timesheets History</h1>
        <h1 class="pull-right">
        <!--
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('timesheets.create') !!}">Add New</a>
        -->
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                       {!! $html->table() !!}


@section('css')
    @include('layouts.datatables_css')
@endsection


@section('scripts')
//test
    @include('layouts.datatables_js')
    {!! $html->scripts() !!}
@endsection
            </div>
        </div>
    </div>
@endsection

