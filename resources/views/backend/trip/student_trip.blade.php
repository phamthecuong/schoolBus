@extends('app')

@section('sidemenu_trip')
active
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>
            {{ trans("general.student_trip") }}
            <span>>
                {{ trans("general.list") }}
        </span>
        </h1>
    </div>
</div>
<section id="widget-grid">							
	<div class="row">						
		<article class="col-lg-12">	
			@box_open(trans("trip.index"))				
			<div>				
				<div class="widget-body no-padding" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => "/ajax/student_trip/$trip_id",		
						'columns' => [								
							['data' => 'id', 'title' => trans('student.No')],					
							['data' => 'full_name', 'title' => trans('student.full_name')],					
							['data' => 'address', 'title' => trans('student.address')],						
							['data' => 'code', 'title' => trans('student.code')],						
						]
					])
					
				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection
