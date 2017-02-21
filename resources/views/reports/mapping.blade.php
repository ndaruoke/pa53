@inject('count', 'App\Services\TimesheetCountService')
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Timesheet Mapping</h1>
        <br/><br/>

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
                            <button type="reset" class="btn" disabled="disabled">Project</button>
                        </span>
                        <span>
                                {!! Form::select('project',
                                  $projects,
                                  $projects,
                                  ['class' => 'form-control select2', 'id' => 'project'])
                                !!}
                        </span>
                        <span class="input-group-btn">
                    <button type="submit" class="btn btn-info btn-flat">Tampilkan</button>
                </span>
                    </div>
                </div>

            </form>


            <div class="box-body">
               @section('css')
            @include('layouts.datatables_css')
            @endsection

                {!! $html->table() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@include('layouts.datatables_js')
                {!! $html->scripts() !!}
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            //$('.approvalstatus option[value=val2]').attr('selected','selected');
        });
    </script>
@endsection

