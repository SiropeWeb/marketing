<?php
/**
 * Load Admin Settings
 *
 * @package Kadence WooCommerce Extras
 */

add_action( 'after_setup_theme', 'kt_woo_extra_run_redux', 1 );
function kt_woo_extra_run_redux() {
	if ( class_exists( 'Redux' ) ) {
		return;
	}
	require_once( KADENCE_WOO_EXTRAS_PATH . '/admin/redux/framework.php' );
}
add_action( 'after_setup_theme', 'kt_woo_extra_add_sections', 2 );
function kt_woo_extra_add_sections() {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}

	$opt_name = 'kt_woo_extras';
	$args = array(
		'opt_name'             => $opt_name,
		'display_name'         => 'Kadence Woocommerce Extras',
		'display_version'      => '',
		'menu_type'            => 'menu',
		'allow_sub_menu'       => true,
		'menu_title'           => __( 'Woo Extras', 'kadence-woo-extras' ),
		'page_title'           => __( 'Kadence Woocommerce Extras', 'kadence-woo-extras' ),
		'google_api_key'       => 'AIzaSyALkgUvb8LFAmrsczX56ZGJx-PPPpwMid0',
		'google_update_weekly' => false,
		'async_typography'     => false,
		'admin_bar'            => false,
		'dev_mode'             => false,
		'use_cdn'              => false,
		'update_notice'        => false,
		'customizer'           => false,
		'forced_dev_mode_off'  => true,
		'page_permissions'     => 'manage_options',
		'menu_icon'            => 'dashicons-cart',
		'show_import_export'   => false,
		'save_defaults'        => true,
		'page_slug'            => 'ktwoopoptions',
		'ajax_save'            => true,
		'default_show'         => false,
		'default_mark'         => '',
		'footer_credit' => __( 'Thank you for using Kadence Woocommerce Extras by <a href="https://kadencewp.com/" target="_blank">Kadence WP</a>.', 'kadence-woo-extras' ),
		'hints'                => array(
			'icon'          => 'kt-icon-question',
			'icon_position' => 'right',
			'icon_color'    => '#444',
			'icon_size'     => 'normal',
			'tip_style'     => array(
				'color'   => 'dark',
				'shadow'  => true,
				'rounded' => false,
				'style'   => '',
			),
			'tip_position'  => array(
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array(
				'show' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'mouseover',
				),
				'hide' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'click mouseleave',
				),
			),
		),
	);

	$args['share_icons'][] = array(
		'url' => 'https://www.facebook.com/KadenceWP',
		'title' => 'Follow Kadence WP on Facebook',
		'icon' => 'dashicons dashicons-facebook',
	);
	$args['share_icons'][] = array(
		'url' => 'https://www.twitter.com/KadenceWP',
		'title' => 'Follow Kadence WP on Twitter',
		'icon' => 'dashicons dashicons-twitter',
	);
	$args['share_icons'][] = array(
		'url' => 'https://www.instagram.com/KadenceWP',
		'title' => 'Follow Kadence WP on Instagram',
		'icon' => 'dashicons dashicons-format-image',
	);
	$args['share_icons'][] = array(
		'url' => 'http://www.youtube.com/c/KadenceWP',
		'title' => 'Follow Kadence WP on YouTube',
		'icon' => 'dashicons dashicons-video-alt3',
	);

	// Add content after the form.
	// $args['footer_text'] = '';

	Redux::setArgs( $opt_name, $args );
	Redux::setSection(
		$opt_name,
		array(
			'icon' => 'dashicons-art',
			'icon_class' => 'dashicons',
			'id' => 'kt_woo_extra_swatches',
			'title' => __( 'Variation Swatches', 'kadence-woo-extras' ),
			'desc' => '',
			'fields' => array(
				array(
					'id' => 'variation_swatches',
					'type' => 'switch',
					'title' => __( 'Enable swatches control with variations', 'kadence-woo-extras' ),
					'subtitle' => __( 'This allows you to display images or colors for variation choices', 'kadence-woo-extras' ),
					'default' => 1,
				),
				array(
					'id' => 'swatches_type',
					'type' => 'select',
					'title' => __( 'Choose a default swatch type', 'kadence-woo-extras' ),
					'options' => array(
						'dropdown' => __( 'Dropdown Select Box', 'kadence-woo-extras' ),
						'radio_box' => __( 'Radio Boxes', 'kadence-woo-extras' ),
						'color_image' => __( 'Image and Color swatchs', 'kadence-woo-extras' ),
						'taxonomy' => __( 'Taxonomy defined', 'kadence-woo-extras' ),
					),
					'subtitle' => __( 'This can be overridden in each product.', 'kadence-woo-extras' ),
					'default' => 'dropdown',
					'width' => 'width:60%',
					'required' => array( 'variation_swatches', '=', '1' ),
				),
				array(
					'id' => 'swatches_label',
					'type' => 'select',
					'title' => __( 'Choose a default swatch label option', 'kadence-woo-extras' ),
					'subtitle' => __( 'This can be overridden in each product.', 'kadence-woo-extras' ),
					'options' => array(
						'false' => __( 'No label', 'kadence-woo-extras' ),
						'above' => __( 'Show above', 'kadence-woo-extras' ),
						'below' => __( 'Show below', 'kadence-woo-extras' ),
						'tooltip' => __( 'Show above on hover', 'kadence-woo-extras' ),
					),
					'default' => 'false',
					'width' => 'width:60%',
					'required' => array( 'variation_swatches', '=', '1' ),
				),
				array(
					'id' => 'swatches_size',
					'type' => 'select',
					'title' => __( 'Choose a default swatch size option', 'kadence-woo-extras' ),
					'subtitle' => __( 'This can be overridden in each product.', 'kadence-woo-extras' ),
					'options' => array(
						'16' => __( '16x16px', 'kadence-woo-extras' ),
						'30' => __( '30x30px', 'kadence-woo-extras' ),
						'40' => __( '40x40px', 'kadence-woo-extras' ),
						'50' => __( '50x50px', 'kadence-woo-extras' ),
						'60' => __( '60x60px', 'kadence-woo-extras' ),
						'75' => __( '75x75px', 'kadence-woo-extras' ),
						'90' => __( '90x90px', 'kadence-woo-extras' ),
						'120' => __( '120x120px', 'kadence-woo-extras' ),
						'150' => __( '150x150px', 'kadence-woo-extras' ),
					),
					'default' => '50',
					'width' => 'width:60%',
					'required' => array( 'variation_swatches', '=', '1' ),
				),
				array(
					'id' => 'choose_option_text',
					'type' => 'text',
					'title' => __( 'Dropdown "Choose an option" text', 'kadence-woo-extras' ),
					'subtitle' => __( 'Standard: Choose an option', 'kadence-woo-extras' ),
					'required' => array( 'variation_swatches', '=', '1' ),
				),
			),
		)
	);

	Redux::setSection(
		$opt_name,
		array(
			'icon' => 'dashicons-format-gallery',
			'icon_class' => 'dashicons',
			'id' => 'kt_woo_extra_gallery',
			'title' => __( 'Product Gallery', 'kadence-woo-extras' ),
			'desc' => '',
			'fields' => array(
				array(
					'id' => 'product_gallery',
					'type' => 'switch',
					'title' => __( 'Enable Product Slider Gallery', 'kadence-woo-extras' ),
					'subtitle' => __( 'This changes the woocommerce product image and gallery into a slider', 'kadence-woo-extras' ),
					'default' => 1,
				),
				array(
					'id' => 'ga_slider_layout',
					'type' => 'image_select',
					'title' => __( 'Choose a gallery type', 'kadence-woo-extras' ),
					'options' => array(
						'above' => array(
							'alt' => 'Thumbnails Below Image',
							'img' => KADENCE_WOO_EXTRAS_URL . '/lib/gallery/img/gallery-type-above.png',
						),
						'left' => array(
							'alt' => 'Thumbnails beside slider on the right',
							'img' => KADENCE_WOO_EXTRAS_URL . '/lib/gallery/img/gallery-type-left.png',
						),
						'right' => array(
							'alt' => 'Thumbnails beside slider on the left',
							'img' => KADENCE_WOO_EXTRAS_URL . '/lib/gallery/img/gallery-type-right.png',
						),
					),
					'default' => 'above',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_image_width',
					'type' => 'slider',
					'title' => __( 'Product image width', 'kadence-woo-extras' ),
					'default'       => '465',
					'min'       => '200',
					'customizer' => false,
					'step'      => '1',
					'max'       => '1200',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_image_ratio',
					'type' => 'select',
					'title' => __( 'Product image ratio', 'kadence-woo-extras' ),
					'options' => array(
						'square' => __( 'Square 1:1', 'kadence-woo-extras' ),
						'portrait' => __( 'Portrait 3:4', 'kadence-woo-extras' ),
						'landscape' => __( 'Landscape 4:3', 'kadence-woo-extras' ),
						'landscape32' => __( 'Landscape 3:2', 'kadence-woo-extras' ),
						'widelandscape' => __( 'Wide Landscape 4:2', 'kadence-woo-extras' ),
						'custom' => __( 'Custom', 'kadence-woo-extras' ),
					),
					'default' => 'square',
					'width' => 'width:60%',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_image_height',
					'type' => 'slider',
					'title' => __( 'Product image Height', 'kadence-woo-extras' ),
					'default'       => '465',
					'min'       => '200',
					'customizer' => false,
					'step'      => '1',
					'max'       => '1200',
					'required' => array( 'ga_image_ratio', '=', 'custom' ),
				),
				array(
					'id' => 'ga_thumb_columns',
					'type' => 'slider',
					'title' => __( 'Select how many thumbnail columns are visible at a time', 'kadence-woo-extras' ),
					'default'       => '6',
					'min'       => '2',
					'customizer' => false,
					'step'      => '1',
					'max'       => '10',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_thumb_image_ratio',
					'type' => 'select',
					'title' => __( 'Product thumbnail image ratio', 'kadence-woo-extras' ),
					'options' => array(
						'square' => __( 'Square 1:1', 'kadence-woo-extras' ),
						'portrait' => __( 'Portrait 3:4', 'kadence-woo-extras' ),
						'landscape' => __( 'Landscape 4:3', 'kadence-woo-extras' ),
						'landscape32' => __( 'Landscape 3:2', 'kadence-woo-extras' ),
						'widelandscape' => __( 'Wide Landscape 4:2', 'kadence-woo-extras' ),
						'inherit' => __( 'Inherit from image', 'kadence-woo-extras' ),
					),
					'default' => 'square',
					'width' => 'width:60%',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_trans_type',
					'type' => 'select',
					'title' => __( 'Slider transition', 'kadence-woo-extras' ),
					'options' => array(
						'false' => __( 'Slide', 'kadence-woo-extras' ),
						'true' => __( 'Fade', 'kadence-woo-extras' ),
					),
					'default' => 'false',
					'width' => 'width:60%',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_slider_transtime',
					'type' => 'slider',
					'title' => __( 'Slider transition speed', 'kadence-woo-extras' ),
					'subtitle' => __( 'How long the transition takes, in milliseconds.', 'kadence-woo-extras' ),
					'default'   => '500',
					'min'       => '100',
					'step'      => '100',
					'max'       => '4000',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_slider_autoplay',
					'type' => 'select',
					'title' => __( 'Slider auto play', 'kadence-woo-extras' ),
					'options' => array(
						'false' => __( 'False', 'kadence-woo-extras' ),
						'true' => __( 'True', 'kadence-woo-extras' ),
					),
					'default' => 'false',
					'width' => 'width:60%',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_slider_pausetime',
					'type' => 'slider',
					'title' => __( 'Slider pause time', 'kadence-woo-extras' ),
					'subtitle' => __( 'How long to pause on each slide, in milliseconds.', 'kadence-woo-extras' ),
					'default'   => '7000',
					'min'       => '3000',
					'step'      => '1000',
					'max'       => '12000',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_slider_arrows',
					'type' => 'select',
					'title' => __( 'Show slide arrows on product image?', 'kadence-woo-extras' ),
					'options' => array(
						'false' => __( 'False', 'kadence-woo-extras' ),
						'true' => __( 'True', 'kadence-woo-extras' ),
					),
					'default' => 'false',
					'width' => 'width:60%',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_show_caption',
					'type' => 'select',
					'title' => __( 'Show Caption overlay on images', 'kadence-woo-extras' ),
					'options' => array(
						'false' => __( 'False', 'kadence-woo-extras' ),
						'true' => __( 'True', 'kadence-woo-extras' ),
					),
					'default' => 'false',
					'width' => 'width:60%',
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_zoom',
					'type' => 'switch',
					'title' => __( 'Enable Product Image Hover Zoom', 'kadence-woo-extras' ),
					'subtitle' => __( 'This allows you to magnify product images without having to click to a lightbox. Note that slider arrows do not show with zoom on.', 'kadence-woo-extras' ),
					'default' => 0,
					'required' => array( 'product_gallery', '=', '1' ),
				),
				array(
					'id' => 'ga_zoom_type',
					'type' => 'select',
					'title' => __( 'Show image magnification on top of image or beside image?', 'kadence-woo-extras' ),
					'options' => array(
						'window' => __( 'Magnify window is beside of image', 'kadence-woo-extras' ),
						'inner' => __( 'Magnify window is on top of image', 'kadence-woo-extras' ),
					),
					'default' => 'window',
					'width' => 'width:60%',
					'required' => array(
						array( 'product_gallery', '=', '1' ),
						array( 'ga_zoom', '=', '1' ),
					),
				),
			),
		)
	);
	Redux::setSection(
		$opt_name,
		array(
			'icon' => 'dashicons-chart-bar',
			'icon_class' => 'dashicons',
			'id' => 'kt_woo_extra_cart_notice',
			'title' => __( 'Cart Notices', 'kadence-woo-extras' ),
			'desc' => '',
			'fields' => array(
				array(
					'id' => 'kt_cart_notice',
					'type' => 'switch',
					'title' => __( 'Enable Cart Notices', 'kadence-woo-extras' ),
					'desc' => __( 'This gives you options to add specialized notices to the cart page to entice with specials and upsell products', 'kadence-woo-extras' ),
					'default' => 0,
				),
			),
		)
	);
	Redux::setSection(
		$opt_name,
		array(
			'icon' => 'dashicons-media-spreadsheet',
			'icon_class' => 'dashicons',
			'id' => 'kt_woo_extra_sizechart',
			'title' => __( 'Size Charts', 'kadence-woo-extras' ),
			'desc' => '',
			'fields' => array(
				array(
					'id' => 'size_charts',
					'type' => 'switch',
					'title' => __( 'Enable Size Charts', 'kadence-woo-extras' ),
					'default' => 1,
				),
			),
		)
	);
	Redux::setSection(
		$opt_name,
		array(
			'icon' => 'dashicons-products',
			'icon_class' => 'dashicons',
			'id' => 'kt_woo_extra_variations',
			'title' => __( 'Variation Options', 'kadence-woo-extras' ),
			'desc' => '',
			'fields' => array(
				array(
					'id' => 'variation_price_info',
					'type' => 'info',
					'desc' => __( ' Customize variation price output', 'kadence-woo-extras' ),
				),
				array(
					'id' => 'variation_price',
					'type' => 'select',
					'title' => __( 'Variation Price Output', 'kadence-woo-extras' ),
					'options' => array(
						'normal' => __( 'Show Price Range ($lowest - $highest)', 'kadence-woo-extras' ),
						'lowprice' => __( 'Show lowest price only', 'kadence-woo-extras' ),
						'highprice' => __( 'Show highest price only', 'kadence-woo-extras' ),
					),
					'default' => 'normal',
					'width' => 'width:60%',
				),
				array(
					'id' => 'before_variation_price',
					'type' => 'text',
					'title' => __( 'Archive Text Before Variation Price', 'kadence-woo-extras' ),
					'subtitle' => __( 'Example: From:', 'kadence-woo-extras' ),
					'required' => array( 'variation_price', '!=', 'normal' ),
				),
				array(
					'id' => 'after_variation_price',
					'type' => 'text',
					'title' => __( 'Archive Text After Variation Price', 'kadence-woo-extras' ),
					'subtitle' => __( 'Example: Base Price', 'kadence-woo-extras' ),
					'required' => array( 'variation_price', '!=', 'normal' ),
				),
			),
		)
	);
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-cart',
				'icon_class' => 'dashicons',
				'id' => 'kt_woo_extra_add_to_cart',
				'title' => __( 'Add to cart text', 'kadence-woo-extras' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'kt_add_to_cart_text',
						'type' => 'switch',
						'title' => __( 'Enable Custom add to cart text', 'kadence-woo-extras' ),
						'subtitle' => __( 'This allows you to change the text for the add to cart buttons', 'kadence-woo-extras' ),
						'default' => 1,
					),
					array(
						'id' => 'add_to_cart_text_info',
						'type' => 'info',
						'desc' => __( 'Archive Add to Cart', 'kadence-woo-extras' ),
						'required' => array( 'kt_add_to_cart_text', '=', '1' ),
					),
					array(
						'id' => 'add_to_cart_text',
						'type' => 'text',
						'title' => __( 'Simple Product', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: Add to cart', 'kadence-woo-extras' ),
						'required' => array( 'kt_add_to_cart_text', '=', '1' ),
					),
					array(
						'id' => 'variable_add_to_cart_text',
						'type' => 'text',
						'title' => __( 'Variable Product', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: Select options', 'kadence-woo-extras' ),
						'required' => array( 'kt_add_to_cart_text', '=', '1' ),
					),
					array(
						'id' => 'grouped_add_to_cart_text',
						'type' => 'text',
						'title' => __( 'Grouped Product', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: View Products', 'kadence-woo-extras' ),
						'required' => array( 'kt_add_to_cart_text', '=', '1' ),
					),
					array(
						'id' => 'out_add_to_cart_text',
						'type' => 'text',
						'title' => __( 'Out of Stock Product', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: Read More', 'kadence-woo-extras' ),
						'required' => array( 'kt_add_to_cart_text', '=', '1' ),
					),
					array(
						'id' => 'single_add_to_cart_text_info',
						'type' => 'info',
						'desc' => __( 'Single Add to Cart', 'kadence-woo-extras' ),
						'required' => array( 'kt_add_to_cart_text', '=', '1' ),
					),
					array(
						'id' => 'single_add_to_cart_text',
						'type' => 'text',
						'title' => __( 'All Products', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: Add to cart', 'kadence-woo-extras' ),
						'required' => array( 'kt_add_to_cart_text', '=', '1' ),
					),
				),
			)
		);
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-text',
				'icon_class' => 'dashicons',
				'id' => 'kt_woo_extra_cat_desc',
				'title' => __( 'Extra Category Description', 'kadence-woo-extras' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'kt_extra_cat',
						'type' => 'switch',
						'title' => __( 'Enable Extra Category Description Box', 'kadence-woo-extras' ),
						'desc' => __( 'This gives you an extra description box for each category to allow you to place content below the products on your category page', 'kadence-woo-extras' ),
						'default' => 1,
					),
				),
			)
		);
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-star-filled',
				'icon_class' => 'dashicons',
				'id' => 'kt_woo_extra_reviews',
				'title' => __( 'Advanced Reviews', 'kadence-woo-extras' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'kt_reviews',
						'type' => 'switch',
						'title' => __( 'Enable Advanced Reviews', 'kadence-woo-extras' ),
						'subtitle' => __( 'This allows you to change the review order as well as have titles and vote on reviews.', 'kadence-woo-extras' ),
						'default' => 0,
					),
					array(
						'id' => 'kt_reviews_order',
						'type' => 'select',
						'title' => __( 'Select Review Order', 'kadence-woo-extras' ),
						'options' => array(
							'votes_desc' => __( 'Order by most helpful with fallback newest first', 'kadence-woo-extras' ),
							'desc' => __( 'Newest First', 'kadence-woo-extras' ),
							'asc' => __( 'Oldest First', 'kadence-woo-extras' ),
						),
						'default' => 'votes_desc',
						'width' => 'width:60%',
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_review_title',
						'type' => 'switch',
						'title' => __( 'Enable Review Titles', 'kadence-woo-extras' ),
						'subtitle' => __( 'This allows you to have titles for reviews.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_review_consent',
						'type' => 'switch',
						'title' => __( 'Enable Review Consent Checkbox', 'kadence-woo-extras' ),
						'subtitle' => __( 'This adds a consent to privacy policy checkbox', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_reviews_multi',
						'type' => 'switch',
						'title' => __( 'When using polylang or WPML show reviews in every language.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_review_voting',
						'type' => 'switch',
						'title' => __( 'Enable Review Voting', 'kadence-woo-extras' ),
						'subtitle' => __( 'This allows users to upvote reviews that are more helpful.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'vote_loggedin_only',
						'type' => 'switch',
						'title' => __( 'Voters must be logged in?', 'kadence-woo-extras' ),
						'subtitle' => __( 'With this turned on only logged in users will be able to vote.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_review_overview',
						'type' => 'switch',
						'title' => __( 'Show Review Overview?', 'kadence-woo-extras' ),
						'subtitle' => __( 'With this turned on there will be overview of all reviews.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_review_overview_highlight',
						'type' => 'color',
						'customizer' => false,
						'title' => __( 'Overview highlight color', 'kadence-woo-extras' ),
						'default' => '#2d5c88',
						'validate' => 'color',
						'transparent' => false,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_reviews_featured',
						'type' => 'switch',
						'title' => __( 'Enabled Featured Reviews', 'kadence-woo-extras' ),
						'subtitle' => __( 'With this turned on you can set reviews to be featured and show at the top of your list.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_reviews_limited',
						'type' => 'switch',
						'title' => __( 'Enabled Load More for Reviews', 'kadence-woo-extras' ),
						'subtitle' => __( 'With this turned on reviews will only load the inital amount and a load more button will show.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_reviews', '=', '1' ),
					),
					array(
						'id' => 'kt_reviews_limited_readmore',
						'type' => 'text',
						'title' => __( 'Read More Reviews Button Text', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: Read More Reviews', 'kadence-woo-extras' ),
						'required' => array(
							array( 'kt_reviews', '=', '1' ),
							array( 'kt_reviews_limited', '=', '1' ),
						),
					),
					array(
						'id'       => 'kt_reviews_limited_count',
						'type'     => 'slider',
						'title'    => __( 'Load More inital and per load amount', 'kadence-woo-extras' ),
						'default'  => '10',
						'min'      => '2',
						'step'     => '1',
						'max'      => '100',
						'required' => array(
							array( 'kt_reviews', '=', '1' ),
							array( 'kt_reviews_limited', '=', '1' ),
						),
					),
					array(
						'id' => 'kt_review_convert',
						'type' => 'raw',
						'full_width' => false,
						'title' => __( 'Convert previous reviews to Advanced Reviews', 'kadence-woo-extras' ),
						'subtitle' => __( 'This will make convert all your previous reviews into advanced.', 'kadence-woo-extras' ),
						'content'  => '<a class="button-primary kt-review-convert" href="#">Convert Reviews</a><div class="convert-info"><p></p></div>',
						'required' => array( 'kt_reviews', '=', '1' ),
					),
				),
			)
		);
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-forms',
				'icon_class' => 'dashicons',
				'id' => 'kt_woo_extra_checkout_editor',
				'title' => __( 'Checkout Editor', 'kadence-woo-extras' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'kt_checkout_editor',
						'type' => 'switch',
						'title' => __( 'Enable Checkout Fields Editor', 'kadence-woo-extras' ),
						'subtitle' => __( 'This gives you total control over your checkout fields and allows you to add custom fields. Controls at woocommerce > checkout manager.', 'kadence-woo-extras' ),
						'default' => 1,
					),
				),
			)
		);
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-external',
				'icon_class' => 'dashicons',
				'id' => 'kt_woo_extra_affiliate_options',
				'title' => __( 'Affiliate Product Options', 'kadence-woo-extras' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'kt_affiliate_options',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate Options', 'kadence-woo-extras' ),
						'subtitle' => __( 'This gives you control to add direct links for product images and archive action buttons.', 'kadence-woo-extras' ),
						'default' => 0,
					),
					array(
						'id' => 'affiliate_archive_info',
						'type' => 'info',
						'desc' => __( 'Product Archive Settings', 'kadence-woo-extras' ),
						'required' => array( 'kt_affiliate_options', '=', '1' ),
					),
					array(
						'id' => 'kt_aa_image_link',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate link for Product Images', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product image link to the affilate instead of the single product page.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_affiliate_options', '=', '1' ),
					),
					array(
						'id' => 'kt_aa_image_link_target',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate Product Image link to opens new browser tab', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product image link open a new tab.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_aa_image_link', '=', '1' ),
					),
					array(
						'id' => 'kt_aa_title_link',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate link for Product Title', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product title link to the affilate instead of the single product page.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_aa_image_link', '=', '1' ),
					),
					array(
						'id' => 'kt_aa_title_link_target',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate Product Title link to opens new browser tab', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product title link open a new tab.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_aa_title_link', '=', '1' ),
					),
					array(
						'id' => 'kt_aa_action_link_target',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate Button link to open new browser tab', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product button link open a new tab.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_affiliate_options', '=', '1' ),
					),
					array(
						'id' => 'affiliate_single_info',
						'type' => 'info',
						'desc' => __( 'Product Single Settings', 'kadence-woo-extras' ),
						'required' => array( 'kt_affiliate_options', '=', '1' ),
					),
					array(
						'id' => 'kt_single_aa_image_link',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate link for Product Images', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product image link to the affiliate instead of the lightbox.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_affiliate_options', '=', '1' ),
					),
					array(
						'id' => 'kt_single_aa_image_link_target',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate Product Image link to opens new browser tab', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product image link open a new tab.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_single_aa_image_link', '=', '1' ),
					),
					array(
						'id' => 'kt_single_aa_action_link_target',
						'type' => 'switch',
						'title' => __( 'Enable Affiliate Button link to open new browser tab', 'kadence-woo-extras' ),
						'desc' => __( 'This makes the product button link open a new tab.', 'kadence-woo-extras' ),
						'default' => 1,
						'required' => array( 'kt_affiliate_options', '=', '1' ),
					),
				),
			)
		);
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-tag',
				'icon_class' => 'dashicons',
				'id' => 'kt_woo_extra_brands_options',
				'title' => __( 'Product Brands', 'kadence-woo-extras' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'kt_product_brands_options',
						'type' => 'switch',
						'title' => __( 'Enable Product Brands Options', 'kadence-woo-extras' ),
						'subtitle' => __( 'This adds a new Taxonomy for Products', 'kadence-woo-extras' ),
						'default' => 0,
					),
					array(
						'id' => 'product_brands_singular',
						'type' => 'text',
						'title' => __( 'Singular Custom Name', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: Product Brand', 'kadence-woo-extras' ),
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_plural',
						'type' => 'text',
						'title' => __( 'Plural Custom Name', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: Product Brands', 'kadence-woo-extras' ),
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_slug',
						'type' => 'text',
						'title' => __( 'URL Custom slug', 'kadence-woo-extras' ),
						'subtitle' => __( 'Default: product-brands (lowercase, no spaces)', 'kadence-woo-extras' ),
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_output_info',
						'type' => 'info',
						'desc' => __( 'Product Brands Single Output settings', 'kadence-woo-extras' ),
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_single_output',
						'type' => 'select',
						'title' => __( 'Single Product Page Output', 'kadence-woo-extras' ),
						'options' => array(
							'none' => __( 'None', 'kadence-woo-extras' ),
							'above_title' => __( 'Above Title', 'kadence-woo-extras' ),
							'above_price' => __( 'Below Title, Above Price', 'kadence-woo-extras' ),
							'above_excerpt' => __( 'Below Price, Above Short Description', 'kadence-woo-extras' ),
							'above_addtocart' => __( 'Below Short Description, Above Add to cart', 'kadence-woo-extras' ),
							'above_meta' => __( 'Below Add to cart, Above meta content', 'kadence-woo-extras' ),
							'below_meta' => __( 'Below meta content', 'kadence-woo-extras' ),
						),
						'default' => 'none',
						'width' => 'width:60%',
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_single_output_style',
						'type' => 'select',
						'title' => __( 'Show as text or image', 'kadence-woo-extras' ),
						'options' => array(
							'image' => __( 'Image', 'kadence-woo-extras' ),
							'text' => __( 'Text', 'kadence-woo-extras' ),
						),
						'default' => 'image',
						'width' => 'width:60%',
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_single_output_width',
						'type' => 'text',
						'title' => __( 'Single Product Page Brand Image Width', 'kadence-woo-extras' ),
						'type' => 'slider',
						'default'   => '200',
						'min'       => '40',
						'step'      => '2',
						'max'       => '1400',
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_single_output_cropped',
						'type' => 'switch',
						'title' => __( 'Enable to Hard Crop Image', 'kadence-woo-extras' ),
						'subtitle' => __( 'If enabled you can force a specific height to hard crop to.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_single_output_height',
						'type' => 'text',
						'title' => __( 'Single Product Page Brand Image Height', 'kadence-woo-extras' ),
						'type' => 'slider',
						'default'   => '200',
						'min'       => '40',
						'step'      => '2',
						'max'       => '1400',
						'required' => array(
							array( 'kt_product_brands_options', '=', '1' ),
							array( 'product_brands_single_output_cropped', '=', '1' ),
						),
					),
					array(
						'id' => 'product_brands_single_link',
						'type' => 'switch',
						'title' => __( 'Link Image to Brand Page', 'kadence-woo-extras' ),
						'subtitle' => __( 'Adds Link to brand archive page from image.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_archive_output_info',
						'type' => 'info',
						'desc' => __( 'Product Brands Archive Output settings', 'kadence-woo-extras' ),
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_archive_output',
						'type' => 'select',
						'title' => __( 'Archive Page Output', 'kadence-woo-extras' ),
						'options' => array(
							'none' => __( 'None', 'kadence-woo-extras' ),
							'above_image' => __( 'Above image', 'kadence-woo-extras' ),
							'above_title' => __( 'Below image, Above title', 'kadence-woo-extras' ),
							'above_excerpt' => __( 'Below title, Above excerpt', 'kadence-woo-extras' ),
							'above_price' => __( 'Below excerpt, Above price', 'kadence-woo-extras' ),
							'above_addtocart' => __( 'Below price, Above add to cart', 'kadence-woo-extras' ),
							'below_addtocart' => __( 'Below add to cart', 'kadence-woo-extras' ),
						),
						'default' => 'none',
						'width' => 'width:60%',
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_archive_output_style',
						'type' => 'select',
						'title' => __( 'Show as text or image', 'kadence-woo-extras' ),
						'options' => array(
							'image' => __( 'Image', 'kadence-woo-extras' ),
							'text' => __( 'Text', 'kadence-woo-extras' ),
						),
						'default' => 'image',
						'width' => 'width:60%',
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_archive_output_width',
						'type' => 'text',
						'title' => __( 'Archive Page Brand Image Width', 'kadence-woo-extras' ),
						'type' => 'slider',
						'default'   => '200',
						'min'       => '40',
						'step'      => '2',
						'max'       => '1400',
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_archive_output_cropped',
						'type' => 'switch',
						'title' => __( 'Enable to Hard Crop Image', 'kadence-woo-extras' ),
						'subtitle' => __( 'If enabled you can force a specific height to hard crop to.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),
					array(
						'id' => 'product_brands_archive_output_height',
						'type' => 'text',
						'title' => __( 'Archive Page Brand Image Height', 'kadence-woo-extras' ),
						'type' => 'slider',
						'default'   => '200',
						'min'       => '40',
						'step'      => '2',
						'max'       => '1400',
						'required' => array(
							array( 'kt_product_brands_options', '=', '1' ),
							array( 'product_brands_archive_output_cropped', '=', '1' ),
						),
					),
					array(
						'id' => 'product_brands_archive_link',
						'type' => 'switch',
						'title' => __( 'Link Image to Brand Page', 'kadence-woo-extras' ),
						'subtitle' => __( 'Adds Link to brand archive page from image.', 'kadence-woo-extras' ),
						'default' => 0,
						'required' => array( 'kt_product_brands_options', '=', '1' ),
					),

				),
			)
		);
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-money',
				'icon_class' => 'dashicons',
				'id' => 'kt_woo_extra_coupon_modal',
				'title' => __( 'Checkout Coupon Modal', 'kadence-woo-extras' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'kt_coupon_modal_checkout',
						'type' => 'switch',
						'title' => __( 'Enable Coupon Checkout Modal.', 'kadence-woo-extras' ),
						'subtitle' => __( 'This allows you to display the coupon input field as a link in the order overview and opens a modal to input coupon.', 'kadence-woo-extras' ),
						'default' => 0,
					),
					array(
						'id' => 'checkout_coupon_link_placement',
						'type' => 'select',
						'title' => __( 'Coupon Modal Link Placement', 'kadence-woo-extras' ),
						'options' => array(
							'before_review' => __( 'Before Order Review', 'kadence-woo-extras' ),
							'before_table_total' => __( 'In Order Review table before total', 'kadence-woo-extras' ),
							'after_table_total' => __( 'In Order Review table after total', 'kadence-woo-extras' ),
							'between_review_payment' => __( 'Between Order Review and Payment', 'kadence-woo-extras' ),
							'after_payment' => __( 'After Payment', 'kadence-woo-extras' ),
						),
						'default' => 'between_review_payment',
						'width' => 'width:60%',
						'required' => array( 'kt_coupon_modal_checkout', '=', '1' ),
					),
					array(
						'id' => 'checkout_coupon_pre',
						'type' => 'text',
						'title' => __( 'Pre Modal Link Text', 'kadence-woo-extras' ),
						'subtitle' => __( 'Standard: Have a promo code?', 'kadence-woo-extras' ),
						'default' => __( 'Have a promo code?', 'kadence-woo-extras' ),
						'required' => array( 'kt_coupon_modal_checkout', '=', '1' ),
					),
					array(
						'id' => 'checkout_coupon_link',
						'type' => 'text',
						'title' => __( 'Modal Link Text', 'kadence-woo-extras' ),
						'subtitle' => __( 'Standard: Click here to enter your code.', 'kadence-woo-extras' ),
						'default' => __( 'Click here to enter your code.', 'kadence-woo-extras' ),
						'required' => array( 'kt_coupon_modal_checkout', '=', '1' ),
					),
					array(
						'id' => 'checkout_coupon_desc',
						'type' => 'text',
						'title' => __( 'Modal Description', 'kadence-woo-extras' ),
						'subtitle' => __( 'Standard: If you have a promo code, please apply it below.', 'kadence-woo-extras' ),
						'default' => __( 'If you have a promo code, please apply it below.', 'kadence-woo-extras' ),
						'required' => array( 'kt_coupon_modal_checkout', '=', '1' ),
					),
					array(
						'id' => 'checkout_coupon_placeholder',
						'type' => 'text',
						'title' => __( 'Input Placeholder', 'kadence-woo-extras' ),
						'subtitle' => __( 'Standard: Promo code', 'kadence-woo-extras' ),
						'default' => __( 'Promo code', 'kadence-woo-extras' ),
						'required' => array( 'kt_coupon_modal_checkout', '=', '1' ),
					),
					array(
						'id' => 'checkout_coupon_apply',
						'type' => 'text',
						'title' => __( 'Apply Button', 'kadence-woo-extras' ),
						'subtitle' => __( 'Standard: Apply Code', 'kadence-woo-extras' ),
						'default' => __( 'Apply Code', 'kadence-woo-extras' ),
						'required' => array( 'kt_coupon_modal_checkout', '=', '1' ),
					),
				),
			)
		);
		Redux::setSection(
			$opt_name,
			array(
				'icon'       => 'dashicons-welcome-widgets-menus',
				'icon_class' => 'dashicons',
				'id'         => 'kt_woo_extra_global_tab',
				'title'      => __( 'Global Tabs', 'kadence-woo-extras' ),
				'desc'       => '',
				'fields'     => array(
					array(
						'id'       => 'kt_global_tabs',
						'type'     => 'switch',
						'title'    => __( 'Enable Global Tabs', 'kadence-woo-extras' ),
						'subtitle' => __( 'This allows you to create tabs and apply them globally to products', 'kadence-woo-extras' ),
						'default'  => 0,
					),
					array(
						'id' => 'global_tabs_output_info',
						'type' => 'info',
						'desc' => __( 'Find Global Tabs under the "Products" Menu item in your admin.', 'kadence-woo-extras' ),
						'required' => array( 'kt_global_tabs', '=', '1' ),
					),
				),
			)
		);
	Redux::setExtensions( 'kt_woo_extras', KADENCE_WOO_EXTRAS_PATH . '/admin/options_assets/extensions/' );
}
function kt_woo_extras_override_redux_css() {
	wp_dequeue_style( 'redux-admin-css' );
	wp_register_style( 'ksp-redux-custom-css', KADENCE_WOO_EXTRAS_URL . 'admin/options_assets/css/style.css', false, 101 );
	wp_enqueue_style( 'ksp-redux-custom-css' );
	wp_dequeue_style( 'redux-elusive-icon' );
	wp_dequeue_style( 'redux-elusive-icon-ie7' );
}

add_action( 'redux-enqueue-kt_woo_extras', 'kt_woo_extras_override_redux_css' );
