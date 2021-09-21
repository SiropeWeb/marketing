import KadenceMegaMenu from './modal-settings.js';
const { render } = wp.element;

 (function($){
    "use strict";
    
    // $( document ).ready( function () {
    //     $( '.kadence-mega-options-button' ).each(function() {
	// 		$( this ).click( function (e) {
	// 			e.preventDefault();
	// 			var id = $(this).data('item-id');
	// 			ReactDOM.render( <KadenceMegaMenu id={ id } />, document.querySelector( '#kadence-mega-options-panel' ) );
	// 		});
	// 	});
	// });
	$( document ).ready( function () {
        $( '.kadence-menu-options' ).each(function() {
			var item_id = $(this).data('item-id');
			var nav_id = $(this).data('nav-id');
			ReactDOM.render( <KadenceMegaMenu item_id={ item_id } nav_id={ nav_id } />, this );
		});
    });
})(jQuery);