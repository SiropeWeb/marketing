<?php
/**
 * Custom Functions
 *
 * @package Kadence Woo Extras.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Checks if Theme is Kadence is enabled
 */
function theme_is_kadence() {
	if ( class_exists( 'Kadence_API_Manager' ) ) {
		return true;
	}
	return false;
}

function kt_woo_get_srcset( $width, $height, $url, $id ) {
	if ( empty( $id ) || empty( $url ) ) {
		return;
	}
	$image_meta = get_post_meta( $id, '_wp_attachment_metadata', true );
	if ( empty( $image_meta['file'] ) ) {
		return;
	}
	// If possible add in our images on the fly sizes
	$ext = substr( $image_meta['file'], strrpos( $image_meta['file'], '.' ) );
	$pathflyfilename = str_replace( $ext, '-' . $width . 'x' . $height . '' . $ext, $image_meta['file'] );
	$pathretinaflyfilename = str_replace( $ext, '-' . $width . 'x' . $height . '@2x' . $ext, $image_meta['file'] );
	$flyfilename = basename( $image_meta['file'], $ext ) . '-' . $width . 'x' . $height . '' . $ext;
	$retinaflyfilename = basename( $image_meta['file'], $ext ) . '-' . $width . 'x' . $height . '@2x' . $ext;

	$upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];

	$flyfile = trailingslashit( $upload_dir ) . $pathflyfilename;
	$retinafile = trailingslashit( $upload_dir ) . $pathretinaflyfilename;
	if ( empty( $image_meta['sizes'] ) ) {
		$image_meta['sizes'] = array();}
	if ( file_exists( $flyfile ) ) {
		$kt_add_imagesize = array(
			'kt_on_fly' => array(
				'file' => $flyfilename,
				'width' => $width,
				'height' => $height,
				'mime-type' => $image_meta['sizes']['thumbnail']['mime-type'],
			),
		);
		$image_meta['sizes'] = array_merge( $image_meta['sizes'], $kt_add_imagesize );
	}
	if ( file_exists( $retinafile ) ) {
		$size = getimagesize( $retinafile );
		if ( ( $size[0] == 2 * $width ) && ( $size[1] == 2 * $height ) ) {
			$kt_add_imagesize_retina = array(
				'kt_on_fly_retina' => array(
					'file' => $retinaflyfilename,
					'width' => 2 * $width,
					'height' => 2 * $height,
					'mime-type' => $image_meta['sizes']['thumbnail']['mime-type'],
				),
			);
			$image_meta['sizes'] = array_merge( $image_meta['sizes'], $kt_add_imagesize_retina );
		}
	}
	if ( function_exists( 'wp_calculate_image_srcset' ) ) {
		$output = wp_calculate_image_srcset( array( $width, $height ), $url, $image_meta, $id );
	} else {
		$output = '';
	}
	return $output;
}
function kt_woo_get_srcset_output( $width, $height, $url, $id ) {
	$img_srcset = kt_woo_get_srcset( $width, $height, $url, $id );
	if ( ! empty( $img_srcset ) ) {
		$output = 'srcset="' . esc_attr( $img_srcset ) . '" sizes="(max-width: ' . esc_attr( $width ) . 'px) 100vw, ' . esc_attr( $width ) . 'px"';
	} else {
		$output = '';
	}
	return $output;
}


add_action( 'cmb2_render_kt_woo_text_number', 'kt_woo_small_render_text_number', 10, 5 );
function kt_woo_small_render_text_number( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	echo $field_type_object->input(
		array(
			'class' => 'cmb2-text-small',
			'type' => 'number',
			'step' => 'any',
		)
	);
}
add_filter( 'cmb2_sanitize_kt_woo_text_number', 'kt_woo_small_sanitize_text_number', 10, 2 );
function kt_woo_small_sanitize_text_number( $null, $new ) {
	// $new = preg_replace( "/[^0-9]/", "", $new );
	return $new;
}
function kt_woo_small_render_text_vote_up( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	echo $field_type_object->input(
		array(
			'class' => 'cmb2-text-small',
			'type' => 'number',
		)
	);
}
add_action( 'cmb2_render_text_vote_up', 'kt_woo_small_render_text_vote_up', 10, 5 );
function kt_woo_sanitize_text_vote_up_callback( $override_value, $value, $post_id ) {
	$votes = get_post_meta( $post_id, '_kt_review_votes', true );
	$old = get_post_meta( $post_id, '_kt_review_upvotes', true );
	if ( $old >= $value ) {
		$new = $old - $value;
		$newvotes = $votes - $new;
	} else {
		$new = $value - $old;
		$newvotes = $votes + $new;
	}
	update_post_meta( $post_id, '_kt_review_votes', $newvotes );
	return $value;
}
add_filter( 'cmb2_sanitize_text_vote_up', 'kt_woo_sanitize_text_vote_up_callback', 10, 3 );
function kt_woo_small_render_text_vote_down( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	echo $field_type_object->input(
		array(
			'class' => 'cmb2-text-small',
			'type' => 'number',
		)
	);
}
add_action( 'cmb2_render_text_vote_down', 'kt_woo_small_render_text_vote_down', 10, 5 );
function kt_woo_sanitize_text_vote_down_callback( $override_value, $value, $post_id ) {
	$votes = get_post_meta( $post_id, '_kt_review_votes', true );
	$old = get_post_meta( $post_id, '_kt_review_downvotes', true );
	if ( $old >= $value ) {
		$new = $old - $value;
		$newvotes = $votes + $new;
	} else {
		$new = $value - $old;
		$newvotes = $votes - $new;
	}
	update_post_meta( $post_id, '_kt_review_votes', $newvotes );
	return $value;
}
add_filter( 'cmb2_sanitize_text_vote_down', 'kt_woo_sanitize_text_vote_down_callback', 10, 3 );

function kt_woo_get_post_options( $query_args ) {

	$args = wp_parse_args(
		$query_args,
		array(
			'post_type'   => 'post',
			'numberposts' => -1,
		)
	);

	$posts = get_posts( $args );

	$post_options = array();
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_options[ $post->ID ] = $post->post_title;
		}
	}

	return $post_options;
}

function kt_woo_coupon_posts_options() {
	$posts = kt_woo_get_post_options(
		array(
			'post_type' => 'shop_coupon',
			'numberposts' => -1,
		)
	);
	$posts['0'] = __( 'Select a Coupon', 'kadence-woo-extras' );
	return $posts;
}
function kt_woo_product_posts_options() {
	$posts = kt_woo_get_post_options(
		array(
			'post_type' => 'product',
			'numberposts' => -1,
		)
	);
	$posts['0'] = __( 'Select a Product', 'kadence-woo-extras' );
	return $posts;
}
function kt_woo_product_posts_options_muiti() {
	$posts = kt_woo_get_post_options(
		array(
			'post_type' => 'product',
			'numberposts' => -1,
		)
	);
	return $posts;
}
function kt_woo_size_chart_posts_options() {
	$posts = kt_woo_get_post_options(
		array(
			'post_type' => 'kt_size_chart',
			'numberposts' => -1,
		)
	);
	$posts['0'] = __( 'None', 'kadence-woo-extras' );
	return $posts;
}
function kt_woo_change_cmb2_styles() {
	wp_deregister_style( 'cmb2-styles' );
	wp_register_style( 'cmb2-styles', KADENCE_WOO_EXTRAS_URL . '/cmb/css/cmb2.css' );
}
// add_action('init', 'kt_woo_change_cmb2_styles', 20);

function kt_get_term_options( $field ) {
	$args = $field->args( 'get_terms_args' );
	$args = is_array( $args ) ? $args : array();

	$args = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );

	$taxonomy = $args['taxonomy'];

	$terms = (array) cmb2_utils()->wp_at_least( '4.5.0' )
		? get_terms( $args )
		: get_terms( $taxonomy, $args );

	// Initate an empty array
	$term_options = array();
	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$term_options[ $term->term_id ] = $term->name;
		}
	}

	return $term_options;
}
function kt_woo_default_placeholder_image() {
	return apply_filters( 'kt_woo_default_placeholder_image', 'http://placehold.it/' );
}

function kt_woo_get_image_array( $width = null, $height = null, $crop = true, $class = null, $alt = null, $id = null, $placeholder = false, $fallback = 'full' ) {
	if ( empty( $id ) ) {
		$id = get_post_thumbnail_id();
	}
	if ( ! empty( $id ) ) {
		$kt_woo_get_image = KT_WOO_Get_Image::getInstance();
		$image = $kt_woo_get_image->process( $id, $width, $height, $fallback );
		if ( empty( $alt ) ) {
			$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		}
		$return_array = array(
			'src' => $image[0],
			'width' => $image[1],
			'height' => $image[2],
			'srcset' => $image[3],
			'class' => $class,
			'alt' => $alt,
			'full' => $image[4],
		);
	} else if ( empty( $id ) && $placeholder == true ) {
		if ( empty( $height ) ) {
			$height = $width;
		}
		if ( empty( $width ) ) {
			$width = $height;
		}
		$return_array = array(
			'src' => kt_woo_default_placeholder_image() . $width . 'x' . $height . '?text=Image+Placeholder',
			'width' => $width,
			'height' => $height,
			'srcset' => '',
			'class' => $class,
			'alt' => $alt,
			'full' => kt_woo_default_placeholder_image() . $width . 'x' . $height . '?text=Image+Placeholder',
		);
	} else {
		$return_array = array(
			'src' => '',
			'width' => '',
			'height' => '',
			'srcset' => '',
			'class' => '',
			'alt' => '',
			'full' => '',
		);
	}

	return $return_array;
}

function kt_woo_get_full_image_output( $width = null, $height = null, $crop = true, $class = null, $alt = null, $id = null, $placeholder = false, $lazy = false, $schema = true, $extra = null ) {
	$img = kt_woo_get_image_array( $width, $height, $crop, $class, $alt, $id, $placeholder );
	if ( $lazy ) {
		   $image_src_output = 'src="' . esc_url( $img['src'] ) . '"';
	} else {
		$image_src_output = 'src="' . esc_url( $img['src'] ) . '"';
	}
	$extras = '';
	if ( is_array( $extra ) ) {
		foreach ( $extra as $key => $value ) {
			$extras .= esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
		}
	} else {
		$extras = $extra;
	}
	if ( ! empty( $img['src'] ) && $schema == true ) {
		$output = '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
		$output .= '<img ' . $image_src_output . ' width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" ' . $img['srcset'] . ' class="' . esc_attr( $img['class'] ) . '" itemprop="contentUrl" alt="' . esc_attr( $img['alt'] ) . '" ' . $extras . '>';
		$output .= '<meta itemprop="url" content="' . esc_url( $img['src'] ) . '">';
		$output .= '<meta itemprop="width" content="' . esc_attr( $img['width'] ) . 'px">';
		$output .= '<meta itemprop="height" content="' . esc_attr( $img['height'] ) . 'px">';
		$output .= '</div>';
		return $output;

	} elseif ( ! empty( $img['src'] ) ) {
		return '<img ' . $image_src_output . ' width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" ' . $img['srcset'] . ' class="' . esc_attr( $img['class'] ) . '" alt="' . esc_attr( $img['alt'] ) . '" ' . $extras . '>';
	} else {
		return null;
	}
}

