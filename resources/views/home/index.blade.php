@extends('layout.master')

@section('content')
	@if (Session::get('success'))
	<div class="alert alert-success alert-dismissable">
		{{ Session::get('success') }}
	</div>
	@endif

	<h2>{{ Auth::user()->fullname() }}'s Home</h2>

	<div class="row">
		<div class="col-md-8">
		</div>
		<div class="col-md-4 panel">
		</div>
	</div>

@endsection
