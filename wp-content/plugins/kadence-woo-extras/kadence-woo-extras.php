<?php
/**
 * Plugin Name: Kadence WooCommerce Extras
 * Plugin URI: https://www.kadencewp.com/product/kadence-woo-extras/
 * Description: This plugin adds extra features for WooCommerce to help improve your online shops.
 * Version: 1.6.17
 * Author: Kadence WP
 * Author URI: https://kadencewp.com/
 * License: GPLv2 or later
 * Text Domain: kadence-woo-extras
 * WC requires at least: 4.0.0
 * WC tested up to: 4.7.0
 *
 * @package Kadence WooCommerce Extras
 */

// Useful global constants.
define( 'KADENCE_WOO_EXTRAS_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
define( 'KADENCE_WOO_EXTRAS_URL', plugin_dir_url( __FILE__ ) );
define( 'KADENCE_WOO_EXTRAS_VERSION', '1.6.17' );

require_once KADENCE_WOO_EXTRAS_PATH . 'classes/kadence-woo-extras-plugin-check.php';
require_once KADENCE_WOO_EXTRAS_PATH . 'classes/class-kadence-image-processing.php';
require_once KADENCE_WOO_EXTRAS_PATH . 'classes/class-kadence-woo-get-image.php';
require_once KADENCE_WOO_EXTRAS_PATH . 'classes/custom_functions.php';
require_once KADENCE_WOO_EXTRAS_PATH . 'admin/admin_options.php';
require_once KADENCE_WOO_EXTRAS_PATH . 'cmb/init.php';
require_once KADENCE_WOO_EXTRAS_PATH . 'classes/cmb2-conditionals/cmb2-conditionals.php';
require_once KADENCE_WOO_EXTRAS_PATH . 'classes/cmb2_select2/cmb_select2.php';

/**
 * Initalize Plugin
 */
function init_kadence_woo_extras() {
	if ( kadence_woo_extras_is_woo_active() ) {
		$kt_woo_extras = get_option( 'kt_woo_extras' );
		require_once KADENCE_WOO_EXTRAS_PATH . 'lib/variations/kt-variations-price.php';

		if ( isset( $kt_woo_extras['variation_swatches'] ) && '1' == $kt_woo_extras['variation_swatches'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/swatches/kt-variations-swatches.php';
		}
		if ( isset( $kt_woo_extras['product_gallery'] ) && '1' == $kt_woo_extras['product_gallery'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/gallery/kt-product-gallery.php';
		}
		if ( isset( $kt_woo_extras['size_charts'] ) && '1' == $kt_woo_extras['size_charts'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/sizechart/kt-size-chart.php';
		}
		if ( isset( $kt_woo_extras['kt_add_to_cart_text'] ) && '1' == $kt_woo_extras['kt_add_to_cart_text'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/add_to_cart_text/kt-add-to-cart-text.php';
		}
		if ( isset( $kt_woo_extras['kt_reviews'] ) && '1' == $kt_woo_extras['kt_reviews'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/reviews/reviews.php';
		}
		if ( isset( $kt_woo_extras['kt_cart_notice'] ) && '1' == $kt_woo_extras['kt_cart_notice'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/cartnotice/kt-cart-notice.php';
		}
		if ( isset( $kt_woo_extras['kt_extra_cat'] ) && '1' == $kt_woo_extras['kt_extra_cat'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/extracatdesc/kt-extra-cat-desc.php';
		}
		if ( isset( $kt_woo_extras['kt_checkout_editor'] ) && '1' == $kt_woo_extras['kt_checkout_editor'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/checkout_editor/kt-checkout-editor.php';
		}
		if ( isset( $kt_woo_extras['kt_affiliate_options'] ) && '1' == $kt_woo_extras['kt_affiliate_options'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/affiliate/kt-affiliate-options.php';
		}
		if ( isset( $kt_woo_extras['kt_product_brands_options'] ) && '1' == $kt_woo_extras['kt_product_brands_options'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/brands/class-kt-extra-brands.php';
		}
		if ( isset( $kt_woo_extras['kt_coupon_modal_checkout'] ) && '1' == $kt_woo_extras['kt_coupon_modal_checkout'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/checkout_coupon/kt-checkout-coupon.php';
		}
		if ( isset( $kt_woo_extras['kt_global_tabs'] ) && '1' == $kt_woo_extras['kt_global_tabs'] ) {
			require_once KADENCE_WOO_EXTRAS_PATH . 'lib/tabs/class-kadence-global-tabs.php';
		}
	}
}
add_action( 'plugins_loaded', 'init_kadence_woo_extras', 1 );

/**
 * Taxonomy Meta
 */
function kt_woo_extras_tax_class() {
	if ( class_exists( 'KT_WOO_EXTRAS_Taxonomy_Meta' ) ) {
		return;
	}
	require_once KADENCE_WOO_EXTRAS_PATH . 'classes/taxonomy-meta-class.php';
}
add_action( 'after_setup_theme', 'kt_woo_extras_tax_class', 1 );

/**
 * Plugin Updates
 */
function kt_woo_extras_updating() {
	require_once KADENCE_WOO_EXTRAS_PATH . 'kadence-update-checker/kadence-update-checker.php';
	require_once KADENCE_WOO_EXTRAS_PATH . 'admin/kadence-activation/kadence-plugin-api-manager.php';
	if ( is_multisite() ) {
		$show_local_activation = apply_filters( 'kadence_activation_individual_multisites', false );
		if ( $show_local_activation ) {
			if ( 'Activated' === get_option( 'kt_api_manager_kadence_woo_activated' ) ) {
				$Kadence_Woo_Extras_Update_Checker = Kadence_Update_Checker::buildUpdateChecker(
					'https://kernl.us/api/v1/updates/57a0dc911d25838411878099/',
					__FILE__,
					'kadence-woo-extras'
				);
			}
		} else {
			if ( 'Activated' === get_site_option( 'kt_api_manager_kadence_woo_activated' ) ) {
				$Kadence_Woo_Extras_Update_Checker = Kadence_Update_Checker::buildUpdateChecker(
					'https://kernl.us/api/v1/updates/57a0dc911d25838411878099/',
					__FILE__,
					'kadence-woo-extras'
				);
			}
		}
	} elseif ( 'Activated' === get_option( 'kt_api_manager_kadence_woo_activated' ) ) {
		$Kadence_Woo_Extras_Update_Checker = Kadence_Update_Checker::buildUpdateChecker(
			'https://kernl.us/api/v1/updates/57a0dc911d25838411878099/',
			__FILE__,
			'kadence-woo-extras'
		);
	}

}
add_action( 'after_setup_theme', 'kt_woo_extras_updating', 1 );

/**
 * Load Text Domain
 */
function kt_woo_extras_textdomain() {
	load_plugin_textdomain( 'kadence-woo-extras', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'kt_woo_extras_textdomain' );

