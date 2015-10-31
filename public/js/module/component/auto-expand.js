/**
 * Auto-expand an element to fit its contents.
 *
 * @see http://jsfiddle.net/gLhCk/5/
 */
define(['jquery'], function($){
	$('.auto-expand').each(function(){
		$(this).get(0).addEventListener('keyup', function() {
			this.style.overflow = 'hidden';
			this.style.height = 0;
			this.style.height = this.scrollHeight + 'px';
		}, false);
	});
});
