@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tunjangan Position
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($tunjanganPosition, ['route' => ['tunjanganPositions.update', $tunjanganPosition->id], 'method' => 'patch']) !!}

                    @include('tunjangan_positions.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection