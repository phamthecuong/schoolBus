@extends('app')

@section('sidemenu_driver')
active
@endsection

@section('content')

 <div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user-plus fa-fw "></i>
            {{ 'Giám sát viên' }}
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
			@include('custom.alert')						
			@box_open('Danh sách giám sát viên trong hệ thống')				
			<div>
				<div class="widget-body no-padding" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/driver',		
						'columns' => [			
							['data' => 'full_name', 'title' => trans('title.Name')],
							['data' => 'phone_number', 'title' => trans('title.Phone_number')],
							['data' => 'user_email', 'title' => trans('title.Email')],
							['data' => 'bus_name', 'title' => trans('title.bus_name')],
							['data' => 'bus_number', 'title' => trans('title.bus_number')],
							['data' => 'created_at', 'title' => trans('title.Created_at')],
							['data' => 'action', 'title' => trans('title.Action'), 'hasFilter'=> false, 'sortable' => false],					
						]
					])
					<div class="widget-footer">
						<a href="{{url('school/driver/create')}}" class="btn btn-primary header-btn">
							{{trans('backend.add_new_driver')}}
						</a>
					</div>
				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection

							
