@extends('layout.master')

@section('content')
	<div class="outer-container index-transparent">
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<h1>Tagline goes here.</h1>
				</div>
				<div class="col-md-5">
					<br>
					@include('index.form.register')
				</div>
			</div>
		</div>
	</div>

	<div class="outer-container bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-push-2">
					<br><br><br>
					<blockquote>
						<h1>Description sub-header.</h1>
						<p class="lead">
							Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
							Nunc nec mi at sem accumsan aliquet.<br>
							Praesent maximus dictum odio at consectetur.
						</p>
					</blockquote>
					<br><br>
				</div>
			</div>
		</div>
	</div>

	<div class="outer-container index-transparent">
		<div class="container">
			<br><br>
			<div class="row text-center">
				<div class="col-md-4">
					<div class="well">
						<h2>
							<span class="glyphicon glyphicon-list-alt"></span><br>
							Feature 1
						</h2>
						<div class="well">
							<em>
								Feature 1 description.
							</em>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="well">
						<h2>
							<span class="glyphicon glyphicon-bullhorn"></span><br>
							Feature 2
						</h2>
						<div class="well">
							<em>
								Feature 2 description.
							</em>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="well">
						<h2>
							<span class="glyphicon glyphicon-user"></span><br>
							Feature 3
						</h2>
						<div class="well">
							<em>
								Feature 3 description.
							</em>
						</div>
					</div>
				</div>
			</div>
			<br>
		</div>
	</div>

	<div class="outer-container bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-push-3">
					<br><br><br><br>
					<blockquote class="no-margin">
						Final callout.<br>
						Can't pass this up!
					</blockquote>
					<br><br><br><br>
				</div>
			</div>
		</div>
	</div>
@endsection
