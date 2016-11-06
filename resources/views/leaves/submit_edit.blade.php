@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Leave
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($leave, ['route' => ['cuti.submit_update', $leave->id], 'method' => 'patch']) !!}

                        @include('submit_leaves.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection