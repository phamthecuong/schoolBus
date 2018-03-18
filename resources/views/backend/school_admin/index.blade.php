@extends('app')

@section('sidemenu_school')
active
@endsection

@section('content')
 <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-eye fa-fw "></i>
                {{ trans("school_admin.school_admin") }}
                <span>>
                    {{ trans("school_admin.list") }}
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
			@include('custom.alert')				
			@box_open(trans("school_admin.school_admin"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => "/ajax/school_admin/{$school_id}",		
						'columns' => [			
							['data' => 'id', 'title' => trans('school_admin.No')],					
							['data' => 'full_name', 'title' => trans('school_admin.Name')],											
							['data' => 'email', 'title' => trans('school_admin.Email')],											
							['data' => 'created_at', 'title' => trans('school_admin.created_at')],					
							['data' => 'action', 'title' => trans('school_admin.action'),'hasFilter'=> false],					
						]
					])
					<div class="widget-footer">
						<a href='{{url("admin/school/$school_id/admin/create")}}' class="btn btn-primary header-btn">
							{{trans('school_admin.add_new_school_admin')}}
						</a>
					</div>
				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection

							
