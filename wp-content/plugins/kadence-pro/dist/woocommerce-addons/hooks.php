<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use function apply_filters;

/**
 * Function to call in page content.
 */
function kadence_pro_custom_shop_page() {
	$shop_page = get_post( wc_get_page_id( 'shop' ) );
	echo apply_filters( 'the_content', $shop_page->post_content );
}
add_action( 'kadence_pro_woocommerce_shop_page_content', 'Kadence_Pro\kadence_pro_custom_shop_page' );


/**
 * Checks if popout is enabled and if so makes sure the action is running.
 */
function add_off_canvas_filter_toggle() {
	if ( ! kadence()->option( 'product_archive_shop_filter_popout' ) ) {
		return;
	}
	add_action( 'wp_footer', 'Kadence_Pro\product_archive_widget_popup' );
	echo '<div class="kadence-shop-top-item kadence-woo-offcanvas-filter-area filter-toggle-open-container">';
	?>
		<button id="filter-toggle" class="filter-toggle-open drawer-toggle filter-toggle-style-<?php echo esc_attr( kadence()->option( 'product_archive_shop_filter_style' ) ); ?>" aria-label="<?php esc_attr_e( 'Open panel', 'kadence-pro' ); ?>" data-toggle-target="#filter-drawer" data-toggle-body-class="showing-filter-drawer" aria-expanded="false" data-set-focus=".filter-toggle-close">
		<?php
		$label = kadence()->option( 'product_archive_shop_filter_label' );
		if ( ( ! empty( kadence()->option( 'product_archive_shop_filter_icon' ) ) && 'none' !== kadence()->option( 'product_archive_shop_filter_icon' ) ) || is_customize_preview() ) {
			?>
			<span class="filter-toggle-icon"><?php shop_filter_toggle_icon(); ?></span>
			<?php
		}
		if ( ! empty( $label ) || is_customize_preview() ) {
			?>
			<span class="filter-toggle-label"><?php echo esc_html( $label ); ?></span>
			<?php
		}
		?>
	</button>
	</div>
	<?php
}
add_action( 'kadence_woocommerce_before_shop_loop_top_row', 'Kadence_Pro\add_off_canvas_filter_toggle' );


/**
 * Widget Popup Toggle
 */
function shop_filter_toggle_icon() {
	$icon = kadence()->option( 'product_archive_shop_filter_icon' );
	if ( $icon !== 'none' ) {
		kadence()->print_icon( $icon, '', false );
	}
}
/**
 * Check to see if we should load widget areas in the customizer in case the user may need them.
 */
function check_for_woo_widget_areas() {
	if ( is_customize_preview() ) {
		if ( ! kadence()->option( 'product_archive_shop_filter_popout' ) ) {
			add_action( 'wp_footer', 'Kadence_Pro\product_archive_widget_popup' );
		}
	}
}
add_action( 'wp_footer', 'Kadence_Pro\check_for_woo_widget_areas', 5 );
/**
 * Widget Popup Drawer
 */
function product_archive_widget_popup() {
	?>
	<div id="filter-drawer" class="popup-drawer popup-drawer-layout-<?php echo esc_attr( kadence()->option( 'product_filter_widget_layout' ) ); ?> popup-drawer-side-<?php echo esc_attr( kadence()->option( 'product_filter_widget_side' ) ); ?>" data-drawer-target-string="#filter-drawer">
		<div class="drawer-overlay" data-drawer-target-string="#filter-drawer"></div>
		<div class="drawer-inner">
			<div class="drawer-header">
				<button class="filter-toggle-close drawer-toggle" aria-label="<?php esc_attr_e( 'Close panel', 'kadence-pro' ); ?>"  data-toggle-target="#filter-drawer" data-toggle-body-class="showing-filter-drawer" aria-expanded="false" data-set-focus=".filter-toggle-open">
					<?php kadence()->print_icon( 'close', '', false ); ?>
				</button>
			</div>
			<div class="drawer-content">
				<?php do_action( 'kadence_before_product_off_canvas_filter' ); ?>
				<div class="widget-area product-filter-widgets product-filter-link-style-<?php echo esc_attr( ( kadence()->option( 'product_off_canvas_link_style' ) ? kadence()->option( 'product_off_canvas_link_style' ) : 'normal' ) ); ?>">
					<?php dynamic_sidebar( 'product-filter' ); ?>
				</div>
				<?php do_action( 'kadence_after_product_off_canvas_filter' ); ?>
			</div>
		</div>
	</div>
	<?php
}
/**
 * Archive active top filter.
 */
function archive_loop_top_active_filter() {
	if ( kadence()->option( 'product_archive_shop_filter_active_top' ) ) {
		global $wp_query;
		if ( 0 === $wp_query->found_posts || ! woocommerce_products_will_display() ) {
			return;
		}
		$args = array(
			'title' => '',
		);
		echo '<div class="kadence-shop-active-filters">';
		the_widget( 'WC_Widget_Layered_Nav_Filters', $args );
		if ( kadence()->option( 'product_archive_shop_filter_active_remove_all' ) ) {
			$filterreset = wp_unslash( $_SERVER['REQUEST_URI'] );
			if ( strpos( $filterreset, '?filter_' ) !== false | strpos( $filterreset, '?min_price' ) !== false | strpos( $filterreset, '?max_price' ) ) {
				$filterreset = strtok( $filterreset, '?' );
				echo '<div class="kadence-clear-filters-container widget"><ul><li><a href="' . esc_url( $filterreset ) . '">' . esc_html__( 'Remove all filters', 'kadence-pro' ) . '</a></li></ul></div>';
			}
		}
		echo '</div>';
	}
}
add_action( 'woocommerce_before_shop_loop', 'Kadence_Pro\archive_loop_top_active_filter', 80 );
/**
 * Single Product Sticky Add to Cart.
 */
function single_product_sticky_add_to_cart() {
	if ( is_product() && kadence()->option( 'product_sticky_add_to_cart' ) ) {
		global $post;
		$product = wc_get_product( $post->ID );
		echo '<div class="kadence-sticky-add-to-cart vs-md-false vs-sm-false kadence-sticky-add-to-cart-header item-at-start">';
		echo '<div class="site-container">';
			echo '<div class="kadence-sticky-add-to-cart-content">';
				echo '<div class="kadence-sticky-add-to-cart-title-wrap">';
					echo wp_kses_post( woocommerce_get_product_thumbnail() );
					echo '<h2 class="kadence-sticky-add-to-cart-title">' . wp_kses_post( get_the_title() ) . '</h2>';
				echo '</div>';
				echo '<div class="kadence-sticky-add-to-cart-action-wrap">';
				if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'subscription' ) ) {
					echo '<span class="kadence-sticky-add-to-cart-action-price price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
					woocommerce_template_single_add_to_cart();
				} elseif ( $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) || $product->is_type( 'variable-subscription' ) ) {
					echo '<span class="kadence-sticky-add-to-cart-action-price price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
					echo '<a href="#product-' . esc_attr( $product->get_ID() ) . '" class="single_link_to_cart_button button alt">' . esc_html( $product->add_to_cart_text() ) . '</a>';
				} else {
					woocommerce_template_single_add_to_cart();
				}
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '</div>';
	}
}

add_action( 'wp_footer', 'Kadence_Pro\single_product_sticky_add_to_cart', 50 );
