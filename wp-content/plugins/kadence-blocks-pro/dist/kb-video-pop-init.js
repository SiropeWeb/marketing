/**
 * Video Pop
 */
jQuery( document ).ready( function( $ ) {
	$( '.kadence-video-popup-link.kadence-video-type-external' ).magnificPopup( {
		type: 'iframe',
		removalDelay: 400,
		mainClass: 'mfp-kt-blocks',
		iframe: {
			patterns: {
				youtu: {
					index: 'youtu.be',
					id: function( url ) {		
						// Capture everything after the hostname, excluding possible querystrings.
						var m = url.match( /^.+youtu.be\/([^?]+)/ );
						if ( null !== m ) {
							return m[1];
						}
						return null;
					},
					// Use the captured video ID in an embed URL. 
					// Add/remove querystrings as desired.
					src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
				},
				youtubenocookiewatch: {
					index: 'youtube-nocookie.com/watch',
					id: 'v=',		
					// 	// Capture everything after the hostname, excluding possible querystrings.
					// 	var m = url.match( /^.+youtube-nocookie.com\/([^?]+)/ );
					// 	if ( null !== m ) {
					// 		return m[1];
					// 	}
					// 	return null;
					// },
					// Use the captured video ID in an embed URL. 
					// Add/remove querystrings as desired.
					src: '//www.youtube-nocookie.com/embed/%id%?autoplay=1&rel=0'
				},
				youtubenocookie: {
					index: 'youtube-nocookie.com/embed',
					id: function( url ) {		
						// Capture everything after the hostname, excluding possible querystrings.
						var m = url.match( /^.+youtube-nocookie.com\/([^?]+)/ );
						if ( null !== m ) {
							return m[1];
						}
						return null;
					},
					// // Use the captured video ID in an embed URL. 
					// // Add/remove querystrings as desired.
					src: '//www.youtube-nocookie.com/%id%?autoplay=1&rel=0'
				}
			}
		},
		callbacks: {
			beforeOpen: function() {
				this.st.iframe.markup = '<div class="mfp-with-anim">' + this.st.iframe.markup + '</div>';
				this.st.mainClass = this.st.mainClass + ' kadence-vpop-anim-' + this.st.el.attr( 'data-effect' ) + ' ' + this.st.el.attr( 'data-popup-class' );
			},
		},
	} );
	$( '.kadence-video-popup-link.kadence-video-type-local' ).each( function() {
		var id   = $( this ).attr( 'data-popup-id' );
		var auto = $( this ).attr( 'data-popup-auto' );
		$( this ).magnificPopup( {
			mainClass: 'mfp-kt-blocks',
			removalDelay: 400,
			items: {
				src: '#' + id,
				type: 'inline',
			},
			callbacks: {
				beforeOpen: function() {
					this.st.mainClass = this.st.mainClass + ' kadence-vpop-anim-' + this.st.el.attr( 'data-effect' ) + ' ' + this.st.el.attr( 'data-popup-class' );
				},
				open: function() {
					// Play video on open:
					if ( 'true' == auto ) {
						$( this.content ).find( 'video' )[ 0 ].play();
					}
				},
				close: function() {
					// Pause video on close:
					$( this.content ).find( 'video' )[ 0 ].pause();
				},
			},
		} );
	} );
} );
