@extends('app')
@section('sidemenu_class_transfer')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
    <div class="col-lg-12">
	@if (Session::has('flash_message'))
            <div class="alert alert-{!! Session::get('flash_level') !!}">
                {!! Session::get('flash_message') !!}
            </div>
        @endif      
    </div>
    
     <article class="col-lg-12 col-md-12">
        @box_open(trans("title.class_transfer"))
        <div>
            <div class="widget-body ">
            	
                {!! Form::open(array('url' => "school/class_transfer", 'method' => "post", 'files' => true, 'id' => 'test')) !!}
                <div class="row">
                	<article class="col-lg-6 col-md-6">
                		{!! Form::lbSelect('old_year', null, App\Models\Classes::allToOptionYear(), trans('title.from_year'), ['class'=>'form-control old_year'])!!} 
		                {!! Form::lbSelect('old_class', null, [['name' => trans('validate.choose'), 'value' => '' ]], trans('title.from_class'), ['class'=>'form-control old_class'] )!!}
		                <table class="table table-striped table-bordered" id ="example">
                            <thead>
                                <tr>
                                    <th><input class="check_all" type="checkbox"/></th>
                                    <th>{{ trans('student.name') }}</th>
                                </tr>
                            </thead>
                            <tbody class="old_student">                  
                            </tbody>
                        </table>
                	</article>

                	<article class="col-lg-6 col-md-6">
                		{!! Form::lbSelect('new_year', null, App\Models\Classes::allToOptionYear(), trans('title.to_year'), ['class'=>'form-control new_year'])!!} 
		                {!! Form::lbSelect('new_class', null, [['name' => trans('validate.choose'), 'value' => '' ]], trans('title.to_class'), ['class'=>'form-control new_class'] )!!}
		                <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"/></th>
                                    <th>{{ trans('student.name') }}</th>
                                </tr>
                            </thead>
                            <tbody class="new_student">
                            </tbody>
                        </table>
                	</article>
                </div>

                <div class="widget-footer ">
                {!! Form::lbSubmit(trans('general.add_new_class')) !!}
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
        @box_close
     </article>
</div>
</section>

@endsection

@push('css')
@endpush
@push('script')
    <script type="text/javascript">
    $(document).ready(function(){
        $(document).on('change','.old_year',function(){
            var year = $(this).val();
            var div = $(this).parents();
            var op = " ";
            $.ajax({
                type:'get',
                url:'{!!URL::to("ajax/class_transfer/findclass")!!}',
                data:{'year':year},
                success:function(data){
                    op+='<option value="0" selected disabled>{{ trans('validate.choose') }}</option>';
                    for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                   // op+='<option value="'+data[i]['users'][0]['id']+'">'+data[i]['users'][0]['name']+'</option>';
                   }
                   $('.old_student').empty();
                   div.find('.old_class').html(" ");
                   div.find('.old_class').append(op);
                },
                error:function(){

                }
            });
        });

        $(document).on('change','.old_class',function(){

            var class_id = $(this).val();        
            var div = $(this).parents();
            var op = " ";
            $.ajax({
                type:'get',
                url:'{!!URL::to("school/payment/findstudent")!!}',
                data:{'id':class_id},
                success:function(data){
                	$('.check_all').prop('checked', false);
                    //op+='<tr><td><input type="checkbox"/></td>' + '<td>'+ data[i].full_name + '</td></tr>';
                    for(var i=0;i<data.length;i++){
                    	 op+='<tr><td><input type="checkbox" name="data['+ data[i].id +']"/></td>' + '<td>'+ data[i].full_name + '</td></tr>';
                    // op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                   // op+='<option value="'+data[i]['users'][0]['id']+'">'+data[i]['users'][0]['name']+'</option>';
                   }

                   div.find('.old_student').html(" ");
                   div.find('.old_student').append(op);
                },
                error:function(){

                }
            });
        });

        $('.check_all').click(function (e) {
		    $(this).closest('table').find('tr td input:checkbox').prop('checked', this.checked);
		});

		$(document).on('change','.new_year',function(){
            var year = $(this).val();
            var div = $(this).parents();
            var op = " ";
            $.ajax({
                type:'get',
                url:'{!!URL::to("ajax/class_transfer/findclass")!!}',
                data:{'year':year},
                success:function(data){
                    op+='<option value="0" selected disabled>{{ trans('validate.choose') }}</option>';
                    for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                   // op+='<option value="'+data[i]['users'][0]['id']+'">'+data[i]['users'][0]['name']+'</option>';
                   }
                   $('.new_student').empty();
                   div.find('.new_class').html(" ");
                   div.find('.new_class').append(op);
                },
                error:function(){

                }
            });
        });

        $(document).on('change','.new_class',function(){

            var class_id = $(this).val();        
            var div = $(this).parents();
            var op = " ";
            $.ajax({
                type:'get',
                url:'{!!URL::to("school/payment/findstudent")!!}',
                data:{'id':class_id},
                success:function(data){
                	$('.check_all_new').prop('checked', false);
                    //op+='<tr><td><input type="checkbox"/></td>' + '<td>'+ data[i].full_name + '</td></tr>';
                    for(var i=0;i<data.length;i++){
                    	 op+='<tr><td><input type="checkbox"/></td>' + '<td>'+ data[i].full_name + '</td></tr>';
                   }

                   div.find('.new_student').html(" ");
                   div.find('.new_student').append(op);
                },
                error:function(){

                }
            });
        });

        $('.check_all_new').click(function (e) {
		    $(this).closest('table').find('tr td input:checkbox').prop('checked', this.checked);
		});

    });
</script>

@endpush