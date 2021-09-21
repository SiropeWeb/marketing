jQuery(document).ready(function ($) {

	/**
	 * Product gallery class.
	 */
	var KadenceProductGallery = function( $target ) {
		this.$target = $target;
		var slick_speed = $('#pr-slick').data('slick-speed'),
		slick_animation = $('#pr-slick').data('slick-animation'),
		slick_animation_speed = $('#pr-slick').data('slick-animation-speed'),
		slick_arrows = $('#pr-slick').data('slick-arrows'),
		slick_vlayout = $('#pr-slick').data('slick-vlayout'),
		slick_thumbs_show = $('#pr-slick').data('slick-thumb-show'),
		slick_thumbs_center = $('#pr-slick').data('slick-thumb-center'),
		slick_auto = $('#pr-slick').data('slick-auto'),
		visible_captions = $('#pr-slick').data('visible-captions'),
		$zoom = $('#pr-slick').data('zoom-active'),
		$type = $('#pr-slick').data('zoom-type'),
		$items = $('#pr-slick').data('gallery-items');

		if($zoom == '1' && !KadenceGalleryisMobile.any() && $(window).width() > 790) {
			var pauseonhover = true;
		} else {
			var pauseonhover = false;
		}
		if ( slick_vlayout == true ) {
			function kt_thumb_slideHeight() {
				var imageheight = $('#pr-slick .slick-track').height();
				$('#pr-thumbnails').css('height', imageheight );
			}
			kt_thumb_slideHeight();
			$('#pr-slick').on('init', function(slick) {
				setTimeout(function() {
				  kt_thumb_slideHeight();
				}, 100);
				setTimeout(function() {
				var currentimageheight = $('#pr-slick .slick-track').height();
				var thumbheight = $('#pr-thumbnails .slick-slide').height();
				var thumbnail_height = thumbheight * slick_thumbs_show;
				if( $('#pr-thumbnails').length && thumbnail_height > currentimageheight) {
					var new_slick_thumbs_show = Math.round(currentimageheight/thumbheight);
					var new_thumb_height = Math.round(currentimageheight/new_slick_thumbs_show);
					$('#pr-thumbnails .slick-slide').each( function() {
						$(this).css('max-height', new_thumb_height );
					});

					$('#pr-thumbnails').slick('slickSetOption', 'slidesToShow', new_slick_thumbs_show, true);
				}
				}, 200);
			});
			function kt_thumb_reinit_height() {
				kt_thumb_slideHeight();
				setTimeout(function() {
				var currentimageheight = $( '#pr-slick .slick-track' ).height();
				var thumbheight = $('#pr-thumbnails').height();
				var thumb_height = $('#pr-thumbnails .slick-slide').height();
				var old_size = thumb_height * slick_thumbs_show;
				var thumbnail_height = Math.round( thumbheight / slick_thumbs_show );
				if( $('#pr-thumbnails').length && old_size > currentimageheight) {
					var new_slick_thumbs_show = Math.round(currentimageheight/thumb_height);
					var new_thumb_height = Math.round(currentimageheight/new_slick_thumbs_show);
					$('#pr-thumbnails .slick-slide').each( function() {
						$(this).css('max-height', new_thumb_height );
					});
					$('#pr-thumbnails').slick('slickSetOption', 'slidesToShow', new_slick_thumbs_show, true);
				}
				if( $('#pr-thumbnails').length && old_size < currentimageheight) {
					console.log('test');
					var new_slick_thumbs_show = Math.round(currentimageheight/thumbnail_height);
					var new_thumb_height = Math.round(currentimageheight/new_slick_thumbs_show);
					$('#pr-thumbnails .slick-slide').each( function() {
						$(this).css('max-height', new_thumb_height );
					});
					$('#pr-thumbnails').slick('slickSetOption', 'slidesToShow', new_slick_thumbs_show, true);
				}
				}, 100);
			}

			var ktwooresizeTimer;
			$(window).resize(function() {
	            clearTimeout(ktwooresizeTimer);
	            ktwooresizeTimer = setTimeout(kt_thumb_reinit_height, 100);
	        });

		}

		$( '#pr-slick' ).on('init', function(slick) {
            $('.kt-slick-slider').addClass('kt-loaded');
            $('.product_image').removeClass('loading');
        });
		 $( '#pr-slick' ).slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: slick_arrows,
			speed: slick_animation_speed,
			fade: slick_animation,
			pauseOnHover: pauseonhover,
			autoplay: slick_auto,
			autoplaySpeed: slick_speed,
			asNavFor: '#pr-thumbnails'
		});
		$('#pr-thumbnails').slick({
			vertical: slick_vlayout,
			slidesToShow: slick_thumbs_show,
			slidesToScroll: 1,
			asNavFor: '#pr-slick',
			dots: false,
			centerPadding: '0px',
			arrows: true,
			centerMode: slick_thumbs_center,
			focusOnSelect: true,
		});

		if($zoom == '1' && !KadenceGalleryisMobile.any() && $(window).width() > 790) {
			function init_product_zoom() {

				$('.zoomContainer').remove();
				$('#pr-slick .slick-current img').removeData('elevateZoom');
				$('#pr-slick .slick-current img').removeData('zoomImage');
				$('#pr-slick .slick-current img').elevateZoom({
					zoomType: $type,
					cursor: "crosshair",
					zoomWindowFadeIn: 300,
					zoomWindowFadeOut: 300
				});
			}
			$('#pr-slick').on('beforeChange', function(event, slick, currentSlide, nextSlide){
				if(currentSlide != nextSlide) {
					$('.zoomContainer').remove();
				}
			});
			$('#pr-slick').on('afterChange', function(event){
				//console.log('test');
				init_product_zoom(); 
			});
			var ktwoozoomresizeTimer;
			$(window).resize(function() {
	            clearTimeout(ktwoozoomresizeTimer);
	            ktwoozoomresizeTimer = setTimeout(init_product_zoom, 200);
	        });
				init_product_zoom();
		}
	}
	var KadenceGalleryisMobile = {
		Android: function() {
		    return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
		    return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
		    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
		    return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
		    return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
        	return (KadenceGalleryisMobile.Android() || KadenceGalleryisMobile.BlackBerry() || KadenceGalleryisMobile.iOS() || KadenceGalleryisMobile.Opera() || KadenceGalleryisMobile.Windows());
    	}

    };
	$('.variations_form').find( '.single_variation' ).on( 'show_variation', function( event, variation, purchasable ) {

		var $type = $('#pr-slick').data('zoom-type');
		// Change zoom image
		if ( variation && variation.image.src && variation.image.src.length > 1 ) {
				// See if the gallery has an image with the same original src as the image we want to switch to.
				var galleryHasImage = $('#pr-slick').find( '.slick-slide img[data-o_src="' + variation.image.src + '"]' ).length > 0;
				// If the gallery has the image, reset the images. We'll scroll to the correct one.
				if ( galleryHasImage ) {
					kt_wc_variations_image_reset();
				}
				// See if gallery has a matching image we can slide to.
				var gal_non_retina = variation.image.gallery_thumbnail_src.replace( variation.image.gallery_thumbnail_src_w + 'x' + variation.image.gallery_thumbnail_src_h, Math.floor( variation.image.gallery_thumbnail_src_w / 2 ) + 'x' + Math.floor( variation.image.gallery_thumbnail_src_h / 2 ) );
				var slideToImage = $('#pr-thumbnails').find( '.slick-slide img[src="' + gal_non_retina + '"]' );
				if ( slideToImage.length == 0 ) {
					var slideToImage = $('#pr-thumbnails').find( '.slick-slide img[src="' + variation.image.gallery_thumbnail_src + '"]' );
				}

				if ( slideToImage.length > 0 ) {
					//var galIndex = slideToImage.parent().data( 'slick-index' ) .trigger( 'click' );
					slideToImage.parent().trigger( 'click' );
					if( $('#pr-slick').data('zoom-active') == '1' && !KadenceGalleryisMobile.any()) {
						$('.zoomContainer').remove();
						$('#pr-slick .slick-current img').removeData('elevateZoom');
						$('#pr-slick .slick-current img').removeData('zoomImage');
						$('#pr-slick .slick-current img').elevateZoom({
							zoomType: $type,
							cursor: "crosshair",
							zoomWindowFadeIn: 300,
							zoomWindowFadeOut: 300
						});
					}
					return;
				}

				// Go to first slide
				var slideCount = $( '#pr-slick' ).slick("getSlick").slideCount;
				if ( 1 < slideCount ) {
					$( '#pr-slick' ).slick( 'slickGoTo', 0 );
				}
				$('#pr-slick .slick-current img').kt_wc_set_variation_attr( 'data-zoom-image', variation.image.full_src );
				$('#pr-slick .slick-current img').kt_wc_set_variation_attr( 'src', variation.image.src );
				if(variation.image.srcset) {
					$('#pr-slick .slick-current img').kt_wc_set_variation_attr('srcset', variation.image.srcset);
				} else {
					$('#pr-slick .slick-current img').kt_wc_set_variation_attr('srcset', '');
				}
				if(variation.image.title) {
					$('#pr-slick .slick-current img').kt_wc_set_variation_attr('title', variation.image.title);
				} else {
					$('#pr-slick .slick-current img').kt_wc_set_variation_attr('title', '');
				}
				if(variation.image.caption) {
					$('#pr-slick .slick-current img').kt_wc_set_variation_attr('data-caption', variation.image.caption);
				} else {
					$('#pr-slick .slick-current img').kt_wc_set_variation_attr('data-caption', '');
				}
				if(visible_captions = 'true') {
					if(variation.image.caption) {
						$('#pr-slick .slick-current .sp-gal-image-caption').html( variation.image.caption );
						$('#pr-slick .slick-current .sp-gal-image-caption').fadeIn();
					} else {
						$('#pr-slick .slick-current .sp-gal-image-caption').fadeOut();
					}
				}
				$('#pr-slick .slick-current img').kt_wc_set_variation_attr('alt', variation.image.alt);
				$('#pr-slick .slick-current a').kt_wc_set_variation_attr('href', variation.image.full_src);
				$( '#pr-thumbnails .slick-current img' ).kt_wc_set_variation_attr( 'src', variation.image.gallery_thumbnail_src );
				$( '#pr-thumbnails .slick-current img' ).kt_wc_set_variation_attr( 'srcset', '' );

				if( $('#pr-slick').data('zoom-active') == '1' && !KadenceGalleryisMobile.any()) {
					$('.zoomContainer').remove();
					$('#pr-slick .slick-current img').removeData('elevateZoom');
					$('#pr-slick .slick-current img').removeData('zoomImage');
					$('#pr-slick .slick-current img').elevateZoom({
						zoomType: $type,
						cursor: "crosshair",
						zoomWindowFadeIn: 300,
						zoomWindowFadeOut: 300
					});
				}
			
		} else {
			kt_wc_variations_image_reset();
		}
	});
	function kt_wc_variations_image_reset() {
		$('#pr-thumbnails .slick-current img').kt_wc_reset_variation_attr( 'src' );
		$('#pr-thumbnails .slick-current img').kt_wc_reset_variation_attr( 'srcset' );
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('data-caption');
		if(visible_captions = 'true') {
			if ( undefined !== $('#pr-slick .slick-current img').attr( 'data-o_data-caption' ) ) {
				$('#pr-slick .slick-current .sp-gal-image-caption').html($('#pr-slick .slick-current img').attr( 'data-o_data-caption' ));
				$('#pr-slick .slick-current .sp-gal-image-caption').fadeIn();
			} else {
				$('#pr-slick .slick-current .sp-gal-image-caption').fadeOut();
			}
		}
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('title');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('data-zoom-image');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('src');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('srcset');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('alt');
		$('#pr-slick .slick-current a').kt_wc_reset_variation_attr('href');
		var $type = $('#pr-slick').data('zoom-type');
		if( $('#pr-slick').data('zoom-active') == '1' && !KadenceGalleryisMobile.any()) {
			var slideCount = $( '#pr-slick' ).slick("getSlick").slideCount;
			if ( 1 < slideCount ) {
				$( '#pr-slick' ).slick( 'slickGoTo', 0 );
			}
			$('.zoomContainer').remove();
			$('#pr-slick .slick-current img').removeData('elevateZoom');
			$('#pr-slick .slick-current img').removeData('zoomImage');
			$('#pr-slick .slick-current img').elevateZoom({
				zoomType: $type,
				cursor: "crosshair",
				zoomWindowFadeIn: 300,
				zoomWindowFadeOut: 300
			});
		}
	};
	$.fn.kt_wc_set_variation_attr = function( attr, value ) {
		if ( undefined === this.attr( 'data-o_' + attr ) ) {
			this.attr( 'data-o_' + attr, ( ! this.attr( attr ) ) ? '' : this.attr( attr ) );
		}
		this.attr( attr, value );
	};
	$.fn.kt_wc_reset_variation_attr = function( attr ) {
		if ( undefined !== this.attr( 'data-o_' + attr ) ) {
			this.attr( attr, this.attr( 'data-o_' + attr ) );
		}
	};
	$('.variations_form').on( 'click', '.reset_variations', function() {
		// cahnge thumbnail
		$('#pr-thumbnails .slick-current img').kt_wc_reset_variation_attr( 'src' );
		$('#pr-thumbnails .slick-current img').kt_wc_reset_variation_attr( 'srcset' );
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('data-zoom-image');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('src');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('srcset');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('alt');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('title');
		$('#pr-slick .slick-current img').kt_wc_reset_variation_attr('data-caption');
		if(visible_captions = 'true') {
			if ( undefined !== $('#pr-slick .slick-current img').attr( 'data-o_data-caption' ) ) {
				$('#pr-slick .slick-current .sp-gal-image-caption').html($('#pr-slick .slick-current img').attr( 'data-o_data-caption' ));
				$('#pr-slick .slick-current .sp-gal-image-caption').fadeIn();
			} else {
				$('#pr-slick .slick-current .sp-gal-image-caption').fadeOut();
			}
		}
		$('#pr-slick .slick-current a').kt_wc_reset_variation_attr('href');
		$('#pr-slick .slick-current a').kt_wc_reset_variation_attr('title');

		var $type = $('#pr-slick').data('zoom-type');
		if( $('#pr-slick').data('zoom-active') == '1' && !KadenceGalleryisMobile.any()) {
			var slideCount = $( '#pr-slick' ).slick("getSlick").slideCount;
			if ( 1 < slideCount ) {
				$( '#pr-slick' ).slick( 'slickGoTo', 0 );
			}
			$('.zoomContainer').remove();
			$('#pr-slick .slick-current img').removeData('elevateZoom');
			$('#pr-slick .slick-current img').removeData('zoomImage');
			$('#pr-slick .slick-current img').elevateZoom({
				zoomType: $type,
				cursor: "crosshair",
				zoomWindowFadeIn: 300,
				zoomWindowFadeOut: 300
			});
		}
	});
	/**
	 * Function to call kt_wc_product_gallery on jquery selector.
	 */
	$.fn.kt_wc_product_gallery = function() {
		new KadenceProductGallery( this );
		return this;
	};
	/*
	 * Initialize all galleries on page.
	 */
	$( '.ga-slick-init' ).each( function() {
		$( this ).kt_wc_product_gallery();
	} );
	// init lightbox
	$.extend(true, $.magnificPopup.defaults, {
			tClose: '',
			tLoading: kpg_lightbox.loading, // Text that is displayed during loading. Can contain %curr% and %total% keys
			gallery: {
				tPrev: '', // Alt text on left arrow
				tNext: '', // Alt text on right arrow
				tCounter: kpg_lightbox.of, // Markup for "1 of 7" counter
			},
			image: {
				tError: kpg_lightbox.error, // Error message when image could not be loaded
				titleSrc: function(item) {
					return item.el.find('img').attr('alt');
					}
				}
		});
	$('.kad-light-gallery.kt-slick-slider').each(function(){
		$(this).find('.slick-slide:not(.slick-cloned) a[data-rel^="lightbox"]:not([target="_blank"])').magnificPopup({
			type: 'image',
			gallery: {
				enabled:true
				},
				image: {
					titleSrc: function(item) {
						return item.el.find('img').attr('data-caption');
					}
				}
			});
		});
});

