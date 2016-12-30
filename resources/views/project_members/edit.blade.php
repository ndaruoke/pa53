@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Project Member
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($projectMember, ['route' => ['projectMembers.update', $projectMember->id], 'method' => 'patch']) !!}

                    @include('project_members.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection