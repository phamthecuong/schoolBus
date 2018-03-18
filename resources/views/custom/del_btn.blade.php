	{!! Form::open(array('route' => $route, 'method' => 'delete', 'onsubmit' => "return confirm('$confirm')")) !!}
		<button class="btn btn-xs btn-danger" type="submit" >{{$title}}</button>
	{!!  Form::close() !!}
