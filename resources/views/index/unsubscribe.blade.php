		<div class="text-center">
			@if ($errors->has('email'))
			<h2>Oops!</h2>
			<h3>{{ $errors->first('email') }}</h3>
			@else
			<h2>You have been unsubscribed.</h2>
			<h3>We have removed <b>{{ $strEmail }}</b> from our mailing list.</h3>
			@endif
		</div>
