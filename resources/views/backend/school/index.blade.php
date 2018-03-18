@extends('app')

@section('sidemenu_school')
active
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>
            {{ trans("general.school") }}
            <span>>
                {{ trans("general.list") }}
        </span>
        </h1>
    </div>
</div>
<section id="widget-grid">							
	<div class="row">						
		<article class="col-lg-12">	
			@include('custom.alert')				
			@box_open(trans("school.school"))				
			<div>				
				<div class="widget-body no-padding" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/school',		
						'columns' => [								
							['data' => 'name', 'title' => trans('school.name')],										
							['data' => 'address', 'title' => trans('school.address')],									
							['data' => 'phone_numbers', 'title' => trans('school.phone')],
							['data' => 'created_at', 'title' => trans('school.created_at')],	
							['data' => 'action', 'title' => trans('school.action'),'hasFilter'=> false],
						]
					])
					<div class="widget-footer">
						<a href="{{url('admin/school/create')}}" class="btn btn-primary header-btn">
							{{trans('school.add_new_school')}}
						</a>
					</div>
				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection
