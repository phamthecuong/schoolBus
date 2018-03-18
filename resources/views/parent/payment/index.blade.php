@extends('app')

@section('sidemenu_payment')
active
@endsection

@section('content')

<section id="widget-grid">							
	<div class="row">						
		<article class="col-lg-12">					
			@box_open(trans("title.payment"))				
			<div>				
				<div class="widget-body" id="post_container">			
				    @include("layouts.elements.table", [
						'url' => '/ajax/parent_payment',		
						'columns' => [		
							['data' => 'student_name', 'title' => trans('payment.name')],
							['data' => 'description', 'title' => trans('payment.description')],
							['data' => 'amount', 'title' => trans('payment.amount')],
							['data' => 'month', 'title' => trans('payment.month')],
							['data' => 'year', 'title' => trans('payment.year')],	
							['data' => 'payment_date', 'title' => trans('title.payment_date')],						
						]
					])

				</div>			
			</div>				
			@box_close
		</article>					
	</div>						
</section>	

@endsection

							
