@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tunjangan Project
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($tunjanganProject, ['route' => ['tunjanganProjects.update', $tunjanganProject->id], 'method' => 'patch']) !!}

                    @include('tunjangan_projects.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection