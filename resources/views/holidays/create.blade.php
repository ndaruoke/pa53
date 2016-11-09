@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Holiday
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'holidays.store']) !!}

                        @include('holidays.fields')

                    {!! Form::close() !!}
                </div>
                
            </div>
            
        </div>
       <h5> Bulk Import Holiday</h5>
                <div class="box box-primary">

            <div class="box-body">
                <div class="row">
<div class="form-group col-sm-6">
            <form  action="{{ URL::to('holidaysimport') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
			{!! csrf_field() !!}
            {!! Form::file('import_file', ['class' => 'form-control']) !!}
			<br><button class="btn btn-primary">Import File</button>
		    </form>
        </div>
                </div>
                
            </div>
            
        </div>
    </div>
@endsection
