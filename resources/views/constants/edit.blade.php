@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Constant
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($constant, ['route' => ['constants.update', $constant->id], 'method' => 'patch']) !!}

                    @include('constants.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection