@extends('app')

@section('sidemenu_admin')
active
@endsection

@section('content')
 <div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
		    <i class="fa fa-edit fa-fw "></i>
		    {{ trans("account.account") }}
		    <span>>
		        {{ trans("account.list") }}
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
			@box_open(trans("account.account"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/acount',		
						'columns' => [			
							['data' => 'id', 'title' => trans('account.No')],					
							['data' => 'email', 'title' => trans('account.Email')],											
							['data' => 'created_at', 'title' => trans('account.Created_at')],					
							['data' => 'action', 'title' => trans('account.Action'),'hasFilter'=> false],					
						]
					])
					<div class="widget-footer">
						<a href="{{url('admin/account/create')}}" class="btn btn-primary header-btn">
							{{trans('account.add_new_acount')}}
						</a>
					</div>
				</div>			
			</div>				
			@box_close				
		</article>					
	</div>						
</section>	

@endsection

							
