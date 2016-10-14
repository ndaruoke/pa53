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
                   {!! Form::model($holiday, ['route' => ['holidays.update', $holiday->id], 'method' => 'patch']) !!}

                        @include('holidays.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection