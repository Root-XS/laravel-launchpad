@extends('layout.master')

@section('content')
	<div class="row">
		<div class="col-md-6 col-md-push-3">
		@if (Session::get('status'))
			<div class="alert alert-success">{{ Session::get('status') }}</div>
		@else
			<h3>Enter your email address to reset your password.</h3>
			@if (Session::get('error'))
			<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			{!! Form::xsOpen(['url' => action('Auth\PasswordController@postEmail')]) !!}
				<div class="form-group">
					{!! Form::xsEmail('email', Input::get('email'), ['placeholder' => 'Email Address']) !!}
				</div>
				<div class="form-group">
					{!! Form::xsSubmit('Send Reset Link') !!}
				</div>
			{!! Form::close() !!}
		@endif
		</div>
	</div>
@endsection
