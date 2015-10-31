@extends('layout.master')

@section('content')
	@if (Session::get('message'))
	<div class="alert alert-{{ Session::get('success') == 1 ? 'success' : 'danger' }}">
		{{ Session::get('message') }}
	</div>
	@endif

	<h2>{{ $oUser->fullname() }}'s Profile</h2>

	<div class="row">
		<div class="col-md-4">
			<a href="//gravatar.com/emails" title="Edit your Gravatar" target="_blank">
				{!! $oUser->avatar('lg') !!}
			</a>
		</div>
		<div class="col-md-8">
			<h3>
				{!! Form::editable('firstname', 'text', $oUser->firstname, 'First', null, $oUser->id) !!}
				{!! Form::editable('lastname', 'text', $oUser->lastname, 'Last', null, $oUser->id) !!}
			</h3>
			<p>
				{!! Form::editable('email', 'text', $oUser->email, 'Email', null, $oUser->id) !!}<br>
				{!! Form::editable('phone', 'text', $oUser->phone, 'Phone', null, $oUser->id) !!}<br>
			</p>
			<br>
			<h4>Settings</h4>
			<p>
				<b>Notify me of interactions on RootXS:</b>
				{!! Form::editable('notify', 'select', Helper::boolToAnswer($oUser->settings->notify, true), null, ["Yes","No"], $oUser->id) !!}
			</p>
		</div>
	</div>
@endsection
