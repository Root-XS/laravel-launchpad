@extends('layout.master')

@section('content')
	<div class="row">
		<div class="col-md-6 col-md-push-3">
			<h3>Change your password:</h3>
			@if (Session::get('errors'))
			<div class="alert alert-danger">
				<ul>
					@foreach (Session::get('errors') as $strError)
					<li>{{ $strError }}</li>
					@endforeach
			</div>
			@endif
			{!! Form::xsOpen(['url' => action('ProfileController@postPassword')]) !!}
				<div class="form-group">
					{!! Form::xsPassword('old_password', ['placeholder' => 'Current Password']) !!}
				</div>
				<div class="form-group">
					{!! Form::xsPassword('password', ['placeholder' => 'New Password']) !!}
				</div>
				<div class="form-group">
					{!! Form::xsPassword('password_confirmation', ['placeholder' => 'Confirm New Password']) !!}
				</div>
				<div class="form-group">
					{!! Form::xsSubmit('Update Password') !!}
				</div>
			{!! Form::close() !!}
		</div>
	</div>
@endsection
