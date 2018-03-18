@extends('app')

@section('sidemenu_contact_school')
active
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
            @if (Session::has('flash_message'))
                <div class="alert alert-{!! Session::get('flash_level') !!}">
                    {!! Session::get('flash_message') !!}
                </div>
            @endif      
    </div>
</div>
  
<section id="widget-grid">							
	<div class="row">						
		<article class="col-lg-12">					
			@box_open(trans("contact.contact"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/contact_school',		
						'columns' => [		
							['data' => 'title', 'title' => trans('contact.title')],
							['data' => 'message', 'title' => trans('contact.message')],
							['data'	=> 'status_info', 'title' => trans('contact.status_info')],
							['data' => 'email', 'title' => trans('contact.parent_email')],
							['data' => 'created_at', 'title' => trans('contact.created_at')],
							['data'	=> 'action', 'title' => trans('contact.action'), 'hasFilter'=> false],	
						]
					])

				</div>			
			</div>				
			@box_close
		</article>					
	</div>						
</section>	

@endsection

							
