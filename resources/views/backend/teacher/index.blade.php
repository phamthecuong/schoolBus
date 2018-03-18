@extends('app')

@section('sidemenu_teacher')
active
@endsection

@section('content')

 <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-user-secret fa-fw "></i>
                {{ trans("teacher.teacher") }}
                <span>>
                    {{ trans("teacher.list") }}
            </span>
            </h1>
        </div>
    </div>
<section id="widget-grid">							
	<div class="row">						
		<article class="col-lg-12">		
			@include('custom.alert')			
			@box_open(trans("teacher.teacher_list"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/teacher',		
						'columns' => [			
							['data' => 'full_name', 'title' => trans('teacher.Full_ame')],
							['data' => 'phone_number', 'title' => trans('teacher.phone_number')],
							['data' => 'class_name', 'title' => trans('teacher.class')],
							['data' => 'email', 'title' => trans('teacher.Email')],
							['data' => 'address', 'title' => trans('teacher.Address')],									
							['data' => 'created_at', 'title' => trans('teacher.Created_at')],					
							['data' => 'action', 'title' => trans('teacher.Action'),'sortable' => false, 'hasFilter' => false],
						]
					])
					<div class="widget-footer">
						<a href="{{url('school/teacher/create')}}" class="btn btn-primary header-btn">
							{{trans('teacher.add_new_teacher')}}
						</a>
					</div>
				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection

							
