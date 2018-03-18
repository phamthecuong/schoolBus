@extends('app')

@section('sidemenu_parent')
active
@endsection

@section('content')
 <div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i>
            {{ trans("general.parent") }}
            <span>>
                {{ trans("general.list") }}
        </span>
        </h1>
    </div>
</div>
<section id="widget-grid">							
	<div class="row">
		<div class="col-lg-12">
	        @if (Session::has('flash_message'))
	            <div class="alert alert-{!! Session::get('flash_level') !!}">
	                {!! Session::get('flash_message') !!}
	            </div>
	        @endif      
	    </div>
		<article class="col-lg-12">				
			@box_open(trans("backend.parent_list"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/parent',		
						'columns' => [								
							['data' => 'full_name', 'title' => trans('title.Name')],								
							['data' => 'address', 'title' => trans('title.Address')],							
							['data' => 'phone_number', 'title' => trans('title.Phone_number')],						
							['data' => 'user_email', 'title' => trans('title.Email'), 'hasFilter'=> false, 'sortable' => false],
							['data' => 'child_name', 'title' => trans('title.children'), 'hasFilter'=> false, 'sortable' => false],
							['data' => 'bus_name', 'title' => trans('title.trip_bus'), 'hasFilter'=> false, 'sortable' => false],
							['data' => 'created_at', 'title' => trans('title.Created_at')],
							['data' => 'action', 'title' => trans('title.Action'), 'hasFilter'=> false, 'sortable' => false],					
						]
					])
					<div class="widget-footer">
						<a href="{{url('school/parent/create')}}" class="btn btn-primary header-btn">
							{{trans('parent.add_new_parent')}}
						</a>
					</div>
				</div>			
			</div>				
			@box_close
		</article>					
	</div>						
</section>	

@endsection

							
