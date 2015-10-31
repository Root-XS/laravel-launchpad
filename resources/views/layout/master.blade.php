<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<title>
		RootXS.
		@if ('production' !== App::environment())
		({{ App::environment() }})
		@endif
	</title>
	<meta name="description" content="DESCRIPTION GOES HERE">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{!! HTML::style('/css/styles.css?v=0.3', [], Config::get('app.forceHttps')) !!}
	<!--[if lt IE 9]>
	{!! HTML::script('//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') !!}
	{!! HTML::script('//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') !!}
	<![endif]-->
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:300,400,700);
		body {
			font-family:'Lato', sans-serif;
		}
	</style>
</head>

<body>
	@if (@$bGuestHome)
	<img src="/img/jumbotron.jpg">
	@endif
	<div id="body">

		<header role="navigation">
			<div class="container">

				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="/">
						<img src="/img/logo.jpg">
					</a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					@include('layout.nav')
				</div>

			</div>
		</header>

		@if (@$bGuestHome)
		@yield('content')
		@else
		<br><br>
		<div class="container">
			@yield('content')
		</div>
		@endif
	</div>

	<footer>
		<div class="container">
			Copyright &copy; {{ date('Y') }}
			<b>{!! HTML::link('http://runawayranch.org', 'Runaway Ranch', ['target' => '_blank'], false) !!}</b>
			<br>
			{!! HTML::link('/privacy', 'Privacy Policy') !!} |
			{!! HTML::link('/terms', 'Terms of Use') !!} |
			{!! HTML::link('/help', 'Help') !!}
		</div>
	</footer>

	{!! @$js !!}
	{!! HTML::script('/js/require.js?v=0.1', ['data-main' => '/js/main', 'async' => ''], Config::get('app.forceHttps')) !!}
	@include('layout.tracking')

</body>
</html>
