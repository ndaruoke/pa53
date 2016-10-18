@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Position
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($position, ['route' => ['positions.update', $position->id], 'method' => 'patch']) !!}

                        @include('positions.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection