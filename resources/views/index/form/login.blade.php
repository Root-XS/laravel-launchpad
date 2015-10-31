	{{-- @see http://net.tutsplus.com/tutorials/php/authentication-with-laravel-4/ --}}
	{!! Form::xsOpen(['url' => 'auth/login']) !!}
		@if (!$errors->isEmpty() && old('remember'))
		<div class="alert alert-danger">{{ $errors->first() }}</div>
		@endif
		{!! Form::hidden('remember', 1) !!}
		<div class="form-group">
			{!! Form::xsEmail('email', null, ['placeholder' => 'Email Address']) !!}
		</div>
		<div class="form-group">
			{!! Form::xsPassword('password', ['placeholder' => 'Password']) !!}
		</div>
		<div class="form-group">
			{!! Form::xsSubmit('Login') !!}
		</div>
		<div class="text-center">
			<a href="/password/email">Forgot password?</a>
		</div>
	{!! Form::close() !!}
