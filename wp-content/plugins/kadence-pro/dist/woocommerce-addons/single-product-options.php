<?php
/**
 * Woocommerce Product Catalog Options.
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;

Theme_Customizer::add_settings(
	array(
		'info_product_sticky_add_to_cart' => array(
			'control_type' => 'kadence_title_control',
			'priority'     => 20,
			'section'      => 'product_layout',
			'label'        => esc_html__( 'Sticky Add To Cart', 'kadence-pro' ),
			'settings'     => false,
		),
		'product_sticky_add_to_cart' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'product_layout',
			'priority'     => 20,
			'default'      => kadence()->default( 'product_sticky_add_to_cart' ),
			'label'        => esc_html__( 'Enabled Sticky Add to Cart', 'kadence-pro' ),
			'input_attrs'  => array(
				'help' => esc_html__( 'Adds a Sticky Bar with add to cart when you scroll down the product page.', 'kadence-pro' ),
			),
			'transport'    => 'refresh',
		),
	)
);

