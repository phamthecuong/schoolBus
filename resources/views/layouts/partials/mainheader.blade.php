<header id="header">
	<div id="logo-group">
		<span id="logo"><b>School Bus</b></span>
	</div>
	<div class="pull-right">
		
		<!-- collapse menu button -->
		<div id="hide-menu" class="btn-header pull-right">
			<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
		</div>
		<!-- end collapse menu -->
		
		<!-- #MOBILE -->
		<!-- Top menu profile link : this shows only when top menu is active -->
		<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
			<li class="">
				<a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
					<img src="{{ asset('sa/img/avatars/male.png') }}" alt="John Doe" class="online" />
				</a>
				<ul class="dropdown-menu pull-right">
					<li>
						<a href="/school/profile/{{Auth::user()->id}}/edit" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="{{ url('logout') }}" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
					</li>
					<!-- <li>
                            <a onclick="return userLogout()" href="#" class="padding-10 padding-top-5 padding-bottom-5"><i class="fa fa-sign-out fa-lg"></i>
                                <strong>{!! trans('general.Logout') !!}</strong></a>
                        </li> -->
				</ul>
			</li>
		</ul>

		<!-- logout button -->
		<div id="logout" class="btn-header transparent pull-right">
			<!-- <span> <a href="{{ url('logout') }}" title="{{ trans('header.signout') }}" data-action="userLogout" data-logout-msg="{{ trans('header.signout_text') }}"><i class="fa fa-sign-out"></i></a> </span> -->
			<span> <a href="#" onclick="return userLogout()"><i class="fa fa-sign-out"></i></a> </span>
		</div>

		<!-- end logout button -->

		<!-- multiple lang dropdown : find all flags in the flags page -->
		<!-- <ul class="header-dropdown-list hidden-xs">
			<li>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					@if (\App::getLocale() == "en")
					<img src="{{ asset('sa/img/blank.gif') }}" class="flag flag-us" alt="United States"> <span> English (US) </span> <i class="fa fa-angle-down"></i>
					@else
					<img src="{{ asset('sa/img/blank.gif') }}" class="flag flag-vn" alt="Vietnamese"> <span> Tiếng Việt (VI) </span> <i class="fa fa-angle-down"></i>
					@endif
				</a>
				<ul class="dropdown-menu pull-right">
					<li 
					@if (\App::getLocale() == "vi")
					class="active"
					@endif
					>
						<a href="{{ url('setLang/en') }}"><img src="{{ asset('sa/img/blank.gif') }}" class="flag flag-us" alt="United States"> English (US)</a>
					</li>
					<li
					@if (\App::getLocale() == "vi")
					class="active"
					@endif
					>
						<a href="{{ url('setLang/vi') }}"><img src="{{ asset('sa/img/blank.gif') }}" class="flag flag-vn" alt="Vietnamese"> Tiếng Việt (VI)</a>
					</li>
				</ul>
			</li>
		</ul> -->
		<!-- end multiple lang -->

	</div>
	<!-- end pulled right: nav area -->

</header>

<script>
function userLogout() {
	$.SmartMessageBox({
		title : "<?php echo e(trans('general.Logout')); ?> <span class='txt-color-orangeDark'><strong><?php echo \Auth::user()->email?></strong></span>?",
		content : "<?php echo e(trans('header.signout_text')); ?>",
		buttons : "[<?php echo e(trans('menu.no')); ?>][<?php echo e(trans('menu.yes')); ?>]"
	}, function(ButtonPressed) {
		if (ButtonPressed === "<?php echo e(trans('menu.yes')); ?>") {
			window.location = '/logout';
			return true;
		} else {
			return false;
		}
	});
	return false;
}
</script>