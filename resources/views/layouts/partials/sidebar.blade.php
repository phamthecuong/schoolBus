<aside id="left-panel">

	<!-- User info -->
	<div class="login-info">
		<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
			
			<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
				@if (isset(Auth::user()->profile->avatar_id))
				<img src="{{ asset('lbmedia/' . \Auth::user()->profile->avatar_id) }}" alt="me" class="online" /> 
				@endif
				<span>
					@if (Auth::user())
					{{ Auth::user()->email }}
					@endif
				</span>
				<i class="fa fa-angle-down"></i>
			</a> 
			
		</span>
	</div>
	<nav>
		<ul>
			@stack("sidebar")
		</ul>
		
	</nav>
	

	<span class="minifyme" data-action="minifyMenu"> 
		<i class="fa fa-arrow-circle-left hit"></i> 
	</span>

</aside>