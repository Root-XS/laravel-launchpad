@extends('emails.layout.master')

@section('content')
	<h2>Password Reset</h2>

	<p>Please complete this form to reset your password:</p>
	<p>{{ url('password/reset/' . $token) }}</p>
	<p>If you did not request a password reset, please ignore this email.</p>
@endsection
