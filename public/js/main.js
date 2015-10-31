require(['require.config'], function(){
	require(['jquery', 'bootstrap'], function($){
		// @todo global stuff?

		// Autoload page-specific JS
		if (window.rxs_controller) {
			require(
				['domReady!', 'module/' + window.rxs_controller + '/' + window.rxs_action],
				function(doc){},
				function(){} // eliminates one console error
			);
		}
	});
});
