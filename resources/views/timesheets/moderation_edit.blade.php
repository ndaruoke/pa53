@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Timesheet
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('timesheets.moderate_fields')
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{!! route('timesheets.moderation') !!}" class="btn btn-default">Back</a>
                        </li>
                        <li>
                            {!! Form::open(['route' => ['timesheets.moderation.update'], 'method' => 'get']) !!}

                                {!! Form::button('Approve', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-success',
                                    'onclick' => "return confirm('Are you sure to approve?')"
                                ]) !!}
                            {!! Form::close() !!}
                        </li>
                        <li>
                            {!! Form::open(['route' => ['timesheets.moderation.reject'], 'method' => 'get']) !!}

                                {!! Form::button('Reject', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-warning',
                                    'onclick' => "return confirm('Are you sure to reject?')"
                                ]) !!}
                            {!! Form::close() !!}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
