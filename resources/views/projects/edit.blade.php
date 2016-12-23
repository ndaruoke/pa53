@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Project
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($project, ['id'=>'project_form', 'route' => ['projects.update', $project->id], 'method' => 'patch']) !!}

                        @include('projects.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection

@section('scripts')
   
    <script type="text/javascript" src="{{ URL::asset('js/multiselect.min.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function($){
            VMasker(document.querySelector("#budgetid")).maskMoney({
                // Decimal precision -> "90"
                precision: 0,
                // Decimal separator -> ",90"
                separator: ',',
                // Number delimiter -> "12.345.678"
                delimiter: '.',
                // Money unit -> "R$ 12.345.678,90"
                unit: 'Rp'
                });
        });

        $("#project_form").submit(function($){
            VMasker(document.querySelector("#budgetid")).unMask();
        });

        jQuery(document).ready(function($) {
            //remove duplicates
            var origin = new Array();
            $('#search option').each(function(){
                origin.push({'text':this.text,'value':this.value});
            });

            var toRemove = new Array();
            $('#search_to option').each(function(){
                toRemove.push({'text':this.text,'value':this.value});
            });

            //alert(JSON.stringify(toRemove));

            for( var i=origin.length - 1; i>=0; i--){
                for( var j=0; j<toRemove.length; j++){
                    if(origin[i] && (origin[i].text === toRemove[j].text)){
                        origin.splice(i, 1);
                    }
                }
            }
            var options = $("#search");
            options.empty()
            $.each(origin, function() {
                options.append(new Option(this.text, this.value));
            });
            //remove duplicates


            $('#search').multiselect({
                search: {
                    left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    afterMoveToRight: function() { alert(''); },
                }
            });
            $('#search2').multiselect({
                right: '#member_to',
                search: {
                    left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    afterMoveToRight: function() { alert(''); },
                },
                rightAll: '#search_rightAll2',
                rightSelected: '#search_rightSelected2',
                leftSelected: '#search_leftSelected2',
                leftAll: '#search_leftAll2'

            });
        });
    </script>
@endsection