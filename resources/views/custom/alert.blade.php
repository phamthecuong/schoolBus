@if(\Session::has('confirm'))
<div class="alert alert-block alert-success">
	<a class="close" data-dismiss="alert" href="#">×</a>
	<p>
		{{\Session::get('confirm')}}
	</p>
</div>

@endif