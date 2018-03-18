@extends('app')
@section('sidemenu_student')
active
@endsection

@section('content')

 <div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-users fa-fw "></i>
            {{ trans("general.student") }}
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
			@box_open(trans("backend.student_title"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/student',		
						'columns' => [			
							['data' => 'full_name', 'title' => trans('student.full_name')],
							['data' => 'sex', 'title' => trans('student.gender')],
							['data' => 'address', 'title' => trans('student.address')],
							['data' => 'departure.name', 'title' => trans('student.departure')],
							['data' => 'departure2.name', 'title' => trans('student.departure_2')],
							['data' => 'code', 'title' => trans('student.code')],						
							['data' => 'created_at', 'title' => trans('student.Created_at')],
							['data' => 'class_name', 'title' => trans('student.class'), 'hasFilter'=> false, 'sortable' => false],
							['data' => 'bus_name', 'title' => trans('student.bus_number'), 'hasFilter'=> false, 'sortable' => false],
							['data' => 'action', 'title' => trans('student.Action'), 'hasFilter'=> false, 'sortable' => false],					
						]
					])
					<div class="widget-footer">
						<a href="{{url('school/student/create')}}" class="btn btn-primary header-btn">
							{!! trans('title.add_new_student') !!}
						</a>
					</div>
				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection
@push('css')
<style>
	table.dataTable thead th { min-width: 80px; }
</style>
@endpush
							
