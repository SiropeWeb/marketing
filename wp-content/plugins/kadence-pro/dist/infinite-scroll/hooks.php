<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;

/**
 * Filter the archive attributes.
 *
 * @param string $attributes archive infinite attributes.
 */
function infinite_posts( $attributes = '' ) {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) {
		if ( ( kadence()->option( 'infinite_posts' ) && ! is_search() && 'post' === get_post_type() ) || ( kadence()->option( 'infinite_search' ) && is_search() && ! is_post_type_archive( 'product' ) ) || ( kadence()->option( 'infinite_custom' ) && ! is_search() && 'post' !== get_post_type() ) ) {
			wp_enqueue_script( 'kadence-infinite-scroll' );
			$attributes = '{ "path": ".next.page-numbers", "append": ".entry", "hideNav": ".pagination", "status": ".page-load-status" }';
		}
	}
	return $attributes;
}
add_filter( 'kadence_archive_infinite_attributes', 'Kadence_Pro\infinite_posts', 5 );

/**
 * Filter the archive attributes.
 *
 * @param string $attributes archive infinite attributes.
 */
function infinite_scroll_products( $attributes = '' ) {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) {
		if ( kadence()->option( 'infinite_products' ) ) {
			wp_enqueue_script( 'kadence-infinite-scroll' );
			$attributes = '{ "path": ".next.page-numbers", "append": ".entry", "hideNav": ".woocommerce-pagination", "status": ".page-load-status" }';
		}
	}
	return $attributes;
}
add_filter( 'kadence_product_archive_infinite_attributes', 'Kadence_Pro\infinite_scroll_products', 5 );

/**
 * Output after archive loop.
 */
function infinite_scroll_html() {
	if ( ( kadence()->option( 'infinite_posts' ) && ! is_search() && 'post' === get_post_type() ) || ( kadence()->option( 'infinite_search' ) && is_search() && ! is_post_type_archive( 'product' ) ) || ( kadence()->option( 'infinite_custom' ) && ! is_search() && 'post' !== get_post_type() ) ) {
		echo '<style>.kt-loader-ellips{font-size:20px;position:relative;width:4em;height:1em;margin:10px auto}.kt-loader-ellips__dot{display:block;width:1em;height:1em;border-radius:.5em;background: var(--global-palette5);position:absolute;animation-duration:.5s;animation-timing-function:ease;animation-iteration-count:infinite}.kt-loader-ellips__dot:nth-child(1),.kt-loader-ellips__dot:nth-child(2){left:0}.kt-loader-ellips__dot:nth-child(3){left:1.5em}.kt-loader-ellips__dot:nth-child(4){left:3em}@keyframes loaderReveal{from{transform:scale(.001)}to{transform:scale(1)}}@keyframes loaderSlide{to{transform:translateX(1.5em)}}.kt-loader-ellips__dot:nth-child(1){animation-name:loaderReveal}.kt-loader-ellips__dot:nth-child(2),.kt-loader-ellips__dot:nth-child(3){animation-name:loaderSlide}.kt-loader-ellips__dot:nth-child(4){animation-name:loaderReveal;animation-direction:reverse}.page-load-status {display: none;padding-top: 20px;text-align: center;color: var(--global-palette4);}</style>';
		echo '<div class="page-load-status"><div class="kt-loader-ellips infinite-scroll-request"><span class="kt-loader-ellips__dot"></span><span class="kt-loader-ellips__dot"></span><span class="kt-loader-ellips__dot"></span><span class="kt-loader-ellips__dot"></span></div><p class="infinite-scroll-last">' . esc_html( kadence()->option( 'infinite_end_of_content' ) ) . '</p><p class="infinite-scroll-error">' . esc_html( kadence()->option( 'infinite_end_of_content' ) ) . '</p></div>';
	}
}
add_action( 'get_template_part_template-parts/content/pagination', 'Kadence_Pro\infinite_scroll_html', 50 );

/**
 * Output after archive loop.
 */
function infinite_scroll_product_html() {
	if ( kadence()->option( 'infinite_products' ) ) {
		echo '<style>.kt-loader-ellips{font-size:20px;position:relative;width:4em;height:1em;margin:10px auto}.kt-loader-ellips__dot{display:block;width:1em;height:1em;border-radius:.5em;background: var(--global-palette5);position:absolute;animation-duration:.5s;animation-timing-function:ease;animation-iteration-count:infinite}.kt-loader-ellips__dot:nth-child(1),.kt-loader-ellips__dot:nth-child(2){left:0}.kt-loader-ellips__dot:nth-child(3){left:1.5em}.kt-loader-ellips__dot:nth-child(4){left:3em}@keyframes loaderReveal{from{transform:scale(.001)}to{transform:scale(1)}}@keyframes loaderSlide{to{transform:translateX(1.5em)}}.kt-loader-ellips__dot:nth-child(1){animation-name:loaderReveal}.kt-loader-ellips__dot:nth-child(2),.kt-loader-ellips__dot:nth-child(3){animation-name:loaderSlide}.kt-loader-ellips__dot:nth-child(4){animation-name:loaderReveal;animation-direction:reverse}.page-load-status {display: none;padding-top: 20px;text-align: center;color: var(--global-palette4);}</style>';
		echo '<div class="page-load-status"><div class="kt-loader-ellips infinite-scroll-request"><span class="kt-loader-ellips__dot"></span><span class="kt-loader-ellips__dot"></span><span class="kt-loader-ellips__dot"></span><span class="kt-loader-ellips__dot"></span></div><p class="infinite-scroll-last">' . esc_html( kadence()->option( 'infinite_end_of_content' ) ) . '</p><p class="infinite-scroll-error">' . esc_html( kadence()->option( 'infinite_end_of_content' ) ) . '</p></div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'Kadence_Pro\infinite_scroll_product_html', 15 );
