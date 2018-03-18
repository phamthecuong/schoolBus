@extends('app')

@section('sidemenu_class')
active
@endsection

@section('content')
 <div class="row">
<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
    <h1 class="page-title txt-color-blueDark">
        <i class="fa fa-book fa-fw "></i>
        {{ trans("class.class") }}
        <span>>
            {{ trans("class.list") }}
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
			@box_open(trans("backend.class_list"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/class',		
						'columns' => [			
							['data' => 'name', 'title' => trans('class.Name')],
							['data' => 'teacher', 'title' => trans('class.Teacher')],								
							['data' => 'created_at', 'title' => trans('class.Created_at')],					
							['data' => 'action', 'title' => trans('class.Action'),'sortable' => false, 'hasFilter' => false],
						]
					])
					<div class="widget-footer">
						<a href="{{url('school/class/create')}}" class="btn btn-primary header-btn">
							{{trans('class.add_new_class')}}
						</a>
					</div>

				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection

							
