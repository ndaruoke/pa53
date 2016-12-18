@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Leave
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('leaves.show_fields')
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{!! route('leaves.moderation') !!}" class="btn btn-default">Back</a>
                        </li>
                        <li>
                            {!! Form::open(['route' => ['leaves.moderation.approve', $leave->id], 'method' => 'get']) !!}

                                {!! Form::button('Approve', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-success',
                                    'onclick' => "return confirm('Are you sure to approve?')"
                                ]) !!}
                            {!! Form::close() !!}
                        </li>
                        <li>
                            {!! Form::open(['route' => ['leaves.moderation.reject', $leave->id], 'method' => 'get']) !!}

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
