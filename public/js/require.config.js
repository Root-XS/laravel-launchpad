define(function(){
	require.config({
		//catchError: true, // prod only
		enforceDefine: true, // important for IE
		urlArgs: 'v=0.2', // cache buster! release # goes here
		waitSeconds: 5,
		paths: {
			// XS
			'component': 'module/component', // component shortcut

			// 3rd parties
			'bootstrap': [ // @todo: only compile the components we need
				'//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min',
				'lib/bootstrap.3.3.2.min'
			],
			'bootstrap-editable': 'lib/bootstrap-editable.min',
			'jquery': [
				'//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min', // jQuery 2.x doesn't support IE < 9
				'lib/jquery.1.11.1.min' // local fallback if CDN fails
			],
			'jquery.datetimepicker': 'lib/jquery.datetimepicker', // @todo do these need shims?
		},
		shim: {
			'bootstrap': {
				deps: ['jquery'],
				exports: '$.fn.modal'
			},
			'bootstrap-editable': {
				deps: ['jquery', 'bootstrap'],
				exports: '$.fn.editable'
			},
		}
	});
});
