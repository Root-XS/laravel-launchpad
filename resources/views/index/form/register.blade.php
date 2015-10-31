	{{-- @see http://net.tutsplus.com/tutorials/php/authentication-with-laravel-4/ --}}
	{!! Form::dfOpen(['url' => 'auth/register']) !!}

		@if (!$errors->isEmpty() && !old('remember'))
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
		@endif

		<div class="form-group">
			{!! Form::xsText('firstname', '', ['placeholder' => 'First Name']) !!}
		</div>
		<div class="form-group">
			{!! Form::xsText('lastname', null, ['placeholder' => 'Last Name']) !!}
		</div>
		<div class="form-group">
			{!! Form::xsEmail('email', htmlspecialchars(Input::get('email')), ['placeholder' => 'Email Address']) !!}
		</div>
		<div class="form-group">
			{!! Form::xsPassword('password', ['placeholder' => 'Password']) !!}
		</div>
		<div class="form-group">
			{!! Form::honeypot('city', 'state') !!}
			By clicking "Register," you confirm your agreement with our
			<br class="hidden-sm hidden-md">
			<a href="/privacy" class="text-info" target="_blank">
				Privacy Policy <span class="glyphicon glyphicon-new-window"></span>
			</a> and
			<a href="/terms" class="text-info" target="_blank">
				Terms of Use <span class="glyphicon glyphicon-new-window"></span>
			</a>.
		</div>
		<div class="form-group">
			{!! Form::xsSubmit('Register', ['no-block' => true]) !!}
		</div>

	{!! Form::close() !!}
