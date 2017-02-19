@inject('count', 'App\Services\TimesheetCountService')
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Timesheet Report</h1>
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
                            <button type="reset" class="btn" disabled="disabled">Report Type</button>
                        </span>
                        <span>
                                {!! Form::select('reportType',
                                  [1 => 'Approve', 5 => 'On Hold', 6 => 'Over Budget'],
                                  $reportType,
                                  ['class' => 'form-control select2', 'id' => 'reportType'])
                                !!}
                        </span>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                            <button type="reset" class="btn" disabled="disabled">Project</button>
                        </span>
                        <span>
                                {!! Form::select('project',
                                  $projects,
                                  $project,
                                  ['class' => 'form-control select2', 'id' => 'project'])
                                !!}
                        </span>
                    </div>
                    <div class="input-group form-inline">
                        <span>
                            <button type="reset" class="btn" disabled="disabled">Periode</button>
                        </span>
                        <span>
                            {!! Form::select('month',
                                  [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                  5 => 'May', 6 => 'Juny', 7 => 'July', 8 => 'August',
                                  9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'],
                                  $month,
                                  ['class' => 'form-control select2', 'id' => 'week'])
                                !!}
                        </span>
                        <span>
                            {!! Form::select('year',
                                  [2017 => '2017', 2018 => '2018', 2019 => '2019', 2020 => '2020'],
                                  $year,
                                  ['class' => 'form-control select2', 'id' => 'year'])
                                !!}
                        </span>

                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-flat" >Tampilkan</button>
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

