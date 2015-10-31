@extends('layout.master')

@section('content')
	<div class="row">
		<div class="col-md-6 col-md-push-3">
			<h3>Reset your password:</h3>
			@if (Session::get('errors'))
			<div class="alert alert-danger">
				<ul>
					@foreach (Session::get('errors')->all() as $strError)
					<li>{{ $strError }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			{!! Form::xsOpen(['url' => action('Auth\PasswordController@postReset')]) !!}
				{!! Form::hidden('token', $token) !!}
				<div class="form-group">
					{!! Form::xsEmail('email', Input::get('email'), ['placeholder' => 'Email Address']) !!}
				</div>
				<div class="form-group">
					{!! Form::xsPassword('password', ['placeholder' => 'New Password']) !!}
				</div>
				<div class="form-group">
					{!! Form::xsPassword('password_confirmation', ['placeholder' => 'Confirm New Password']) !!}
				</div>
				<div class="form-group">
					{!! Form::xsSubmit('Reset Password') !!}
				</div>
			{!! Form::close() !!}
		</div>
	</div>
@endsection
