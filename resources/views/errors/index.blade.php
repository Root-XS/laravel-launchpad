@extends('layout.master')

@section('content')
	<div class="text-center">
		<h2>Oops!</h2>
		<h3>
			<?php
			switch ($exception->getCode()) {
				case 500 :
					echo 'There was an error on our end. Please let us know what happened!';
					break;
				case 404 :
				case 0 :
					echo 'That page does not exist.';
					break;
				default :
					echo $exception->getMessage();
					break;
			}
			?>
		</h3>
	</div>
	@unless (App::environment('production'))
	<pre>{{ $exception->getTraceAsString() }}</pre>
	@endunless
@endsection
