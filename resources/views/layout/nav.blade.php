<?php
$dropdownAttribs = [
	'class' => 'dropdown-toggle',
	'data-toggle' => 'dropdown',
];
?>		<nav>
			<ul class="nav navbar-nav navbar-right">

				@if (Auth::check())
				<li class="dropdown">
					{!! HTML::decode(HTML::link('#', 'My Account <b class="caret"></b>', $dropdownAttribs)) !!}
					<ul class="dropdown-menu" role="menu">
						<li>{!! HTML::link('profile', 'Edit Profile') !!}</li>
						<li>{!! HTML::link('profile/password', 'Change Password') !!}</li>
						<li class="divider"></li>
						<li>{!! HTML::link('auth/logout', 'Logout') !!}</li>
					</ul>
				</li>
				@else
				<li class="dropdown">
					{!! HTML::decode(HTML::link('#', 'Login <b class="caret"></b>', $dropdownAttribs)) !!}
					<ul class="dropdown-menu" role="form">
						<li>@include('index.form.login')</li>
					</ul>
				</li>
				@endif

			</ul>
		</nav>
