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
                    {!! Form::open(['route' => 'positions.store']) !!}

                    @include('positions.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
<?php
/**
+ * Created by PhpStorm.
+ * User: usreng
+ * Date: 10/23/2016
+ * Time: 3:46 PM
+ */
