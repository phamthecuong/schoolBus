	{!! Form::open(array('route' => $route, 'method' => 'put', 'onsubmit' => "return confirm('$confirm')")) !!}
		 @include ('custom.password',['name' => 'password', 'title' => trans('title.password')])
         @include ('custom.password',['name' => 'confirm_password', 'title' => trans('title.con
		<button class="btn btn-xs btn-danger" type="submit" >{{$title}}</button>
	{!!  Form::close() !!}
