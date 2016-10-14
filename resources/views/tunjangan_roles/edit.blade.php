@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tunjangan Role
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tunjanganRole, ['route' => ['tunjanganRoles.update', $tunjanganRole->id], 'method' => 'patch']) !!}

                        @include('tunjangan_roles.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection