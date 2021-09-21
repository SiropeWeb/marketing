<?php
// This overrides woocommerce
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', 'kt_size_chart_plugin_loaded' );

function kt_size_chart_plugin_loaded() {

	class kt_size_chart {

		public function __construct() {
				add_action( 'init', array( $this, 'kt_woo_size_chart_post' ), 10 );
			if ( is_admin() ) {
				add_action( 'do_meta_boxes', array( $this, 'kt_woo_remove_revolution_slider_meta_boxes' ), 10 );
			}
				require_once KADENCE_WOO_EXTRAS_PATH . 'lib/sizechart/class-size-chart-widget.php';

				add_filter( 'cmb2_admin_init', array( $this, 'kt_woo_size_chart_metaboxes' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'kt_size_chart_enqueue_scripts' ) );
				add_action( 'woocommerce_before_single_product', array( $this, 'kt_product_size_chart' ) );
				add_action( 'widgets_init', array( $this, 'widgets_init' ) );
				add_shortcode( 'kt_size_chart', array( $this, 'output_shortcode' ) );

		}
		public function kt_size_chart_enqueue_scripts() {
			if ( is_singular( 'product' ) ) {
				wp_enqueue_style( 'kadence_size_chart_css', KADENCE_WOO_EXTRAS_URL . 'lib/sizechart/css/kt_size_chart.css', false, KADENCE_WOO_EXTRAS_VERSION );
				wp_register_script( 'kadence_size_chart', KADENCE_WOO_EXTRAS_URL . 'lib/sizechart/js/min/kt_size_chart-min.js', array( 'jquery' ), KADENCE_WOO_EXTRAS_VERSION, true );
				wp_enqueue_script( 'kadence_size_chart' );
			}
			add_filter( 'kt_size_chart_content', 'do_blocks', 9 );
			add_filter( 'kt_size_chart_content', 'wptexturize' );
			add_filter( 'kt_size_chart_content', 'convert_smilies' );
			add_filter( 'kt_size_chart_content', 'convert_chars' );
			add_filter( 'kt_size_chart_content', 'wpautop' );
			add_filter( 'kt_size_chart_content', 'shortcode_unautop' );
			add_filter( 'kt_size_chart_content', 'prepend_attachment' );
			add_filter( 'kt_size_chart_content', 'wp_make_content_images_responsive' );
		}
		public function widgets_init() {
			if ( class_exists( 'Kadence_Woo_Template_Builder' ) ) {
				register_widget( 'kwt_size_chart_widget' );
			}
		}
		public function kt_woo_size_chart_post() {
			$sizechartlabels = array(
				'name'               => __( 'Size Chart', 'kadence-woo-extras' ),
				'singular_name'      => __( 'Size Chart Item', 'kadence-woo-extras' ),
				'add_new'            => __( 'Add New Chart', 'kadence-woo-extras' ),
				'add_new_item'       => __( 'Add New Chart', 'kadence-woo-extras' ),
				'edit_item'          => __( 'Edit Chart', 'kadence-woo-extras' ),
				'new_item'           => __( 'New Chart', 'kadence-woo-extras' ),
				'all_items'          => __( 'All Charts', 'kadence-woo-extras' ),
				'view_item'          => __( 'View Chart', 'kadence-woo-extras' ),
				'search_items'       => __( 'Search Chart', 'kadence-woo-extras' ),
				'not_found'          => __( 'No Chart found', 'kadence-woo-extras' ),
				'not_found_in_trash' => __( 'No Charts found in Trash', 'kadence-woo-extras' ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Size Chart', 'kadence-woo-extras' ),
			);

			$chartargs = array(
				'labels'              => $sizechartlabels,
				'public'              => true,
				'show_ui'             => true,
				'exclude_from_search' => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'rewrite'             => false,
				'has_archive'         => false,
				'capability_type'     => 'post',
				'hierarchical'        => false,
				'menu_position'       => null,
				'show_in_rest'        => true,
				'menu_icon'           => 'dashicons-media-spreadsheet',
				'supports'            => array( 'title', 'editor' ),
			);

			register_post_type( 'kt_size_chart', $chartargs );
		}
		public function kt_woo_remove_revolution_slider_meta_boxes() {
			remove_meta_box( 'mymetabox_revslider_0', 'kt_size_chart', 'normal' );
		}

		public function kt_woo_hex2rgb( $hex ) {
			$hex = str_replace( '#', '', $hex );

			if ( strlen( $hex ) == 3 ) {
				$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}
			$rgb = array( $r, $g, $b );
			// return implode(",", $rgb); // returns the rgb values separated by commas
			return $rgb; // returns an array with the rgb values
		}
		public function kt_product_size_chart() {
			global $post;
			$chart_id = get_post_meta( $post->ID, '_kt_woo_size_chart_assign', true );
			if ( ! empty( $chart_id ) && $chart_id != '0' ) {
				$chartid = $chart_id;
			} else {
				$chartid = $this->kt_product_size_chart_from_cat( $post->ID );
			}
			if ( ! empty( $chartid ) && $chartid != false ) {
				$chart_placement = get_post_meta( $chartid, '_kt_woo_size_placement', true );
				if ( $chart_placement == 'aftercart' ) {
					add_action( 'woocommerce_single_product_summary', array( $this, 'kt_product_size_chart_output' ), 32 );
				} elseif ( $chart_placement == 'beforecart' ) {
					add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'kt_product_size_chart_output' ), 10 );
				} elseif ( $chart_placement == 'afterdesc' ) {
					add_action( 'woocommerce_single_product_summary', array( $this, 'kt_product_size_chart_output' ), 25 );
				} elseif ( $chart_placement == 'beforedesc' ) {
					add_action( 'woocommerce_single_product_summary', array( $this, 'kt_product_size_chart_output' ), 15 );
				} elseif ( $chart_placement == 'aftermeta' ) {
					add_action( 'woocommerce_single_product_summary', array( $this, 'kt_product_size_chart_output' ), 45 );
				} elseif ( $chart_placement == 'builder' ) {
					add_action( 'kadence_woocommerce_builder_sizechart', array( $this, 'kt_product_size_chart_output' ), 45 );
				} elseif ( $chart_placement == 'tab' ) {
					add_filter( 'woocommerce_product_tabs', array( $this, 'kt_woo_size_chart_tab' ) );
				}
			}
		}
		/**
		 * Output Shortcode
		 *
		 * @param array $atts shortcode attributes
		 */
		public function output_shortcode( $atts ) {
			$chart = shortcode_atts(
				array(
					'id'          => '',
				),
				$atts
			);
			ob_start();
				$this->kt_product_size_chart_output( $chart['id'] );
			$output = ob_get_clean();
			return '<div class="kt_size_chart_shortcode">' . $output . '</div>';
		}
		/**
		 * Output chart
		 *
		 * @param string $id specific chart id.
		 */
		public function kt_product_size_chart_output( $id = null ) {
			global $product;
			if ( ! empty( $id ) ) {
				$chart_id = $id;
			} else {
				if ( isset( $product ) && ! empty( $product ) ) {
					$chart_id = get_post_meta( $product->get_id(), '_kt_woo_size_chart_assign', true );
					if ( ! empty( $chart_id ) && '0' != $chart_id ) {
						$chartid = $chart_id;
					} else {
						$chartid = $this->kt_product_size_chart_from_cat( $product->get_id() );
					}
				} else {
					$chart_id = '';
				}
			}
			if ( empty( $chartid ) || false === $chartid ) {
				return '';
			}
			$btn_text             = get_post_meta( $chartid, '_kt_woo_btn_text', true );
			$modal_close_text     = get_post_meta( $chartid, '_kt_woo_modal_close_text', true );
			$btn_color            = get_post_meta( $chartid, '_kt_woo_btn_txt_color', true );
			$btn_background       = get_post_meta( $chartid, '_kt_woo_btn_background_color', true );
			$btn_color_hover      = get_post_meta( $chartid, '_kt_woo_btn_txt_color_hover', true );
			$btn_background_hover = get_post_meta( $chartid, '_kt_woo_btn_background_color_hover', true );
			$btn_border           = get_post_meta( $chartid, '_kt_woo_btn_border', true );
			$btn_radius           = get_post_meta( $chartid, '_kt_woo_btn_radius', true );
			$btn_border_color     = get_post_meta( $chartid, '_kt_woo_btn_border_color', true );
			$btn_border_hover     = get_post_meta( $chartid, '_kt_woo_btn_border_color_hover', true );
			$responsive_table     = get_post_meta( $chartid, '_kt_woo_responsive_table', true );
			$title_color          = get_post_meta( $chartid, '_kt_woo_header_txt_color', true );
			$content_color        = get_post_meta( $chartid, '_kt_woo_content_txt_color', true );
			$background_color     = get_post_meta( $chartid, '_kt_woo_background_color', true );
			$b_overlay_color      = get_post_meta( $chartid, '_kt_woo_background_overlay_color', true );
			$elementor_page       = get_post_meta( $chartid, '_elementor_edit_mode', true );
			if ( ! empty( $btn_radius ) ) {
				$btn_radius = 'border-radius:' . $btn_radius . 'px;';
			} else {
				$btn_radius = '';
			}
			if ( ! empty( $btn_border ) ) {
				$btn_border = 'border-width:' . $btn_border . 'px;';
			} else {
				$btn_border = '';
			}
			if ( ! empty( $b_overlay_color ) ) {
				$b_overlay_color = $this->kt_woo_hex2rgb( $b_overlay_color );
				$b_overlay_color = 'rgba(' . $b_overlay_color[0] . ', ' . $b_overlay_color[1] . ', ' . $b_overlay_color[2] . ', 0.5);';
				$b_overlay_color = 'background:' . $b_overlay_color;
			} else {
				$b_overlay_color = 'background: rgba(0,0,0,.5)';
			}
			if ( ! empty( $background_color ) ) {
				$background_color = 'background:' . $background_color;
			} else {
				$background_color = '';
			}
			if ( ! empty( $content_color ) ) {
				$content_color = 'color:' . $content_color;
			} else {
				$content_color = '';
			}
			if ( ! empty( $title_color ) ) {
				$title_color = 'color:' . $title_color;
			} else {
				$title_color = '';
			}
			if ( isset( $responsive_table ) && $responsive_table == 'false' ) {
				$table_class = 'kt-size-table-nonresponsive';
			} else {
				$table_class = 'kt-size-table-responsive';
			}
			if ( empty( $modal_close_text ) ) {
				$modal_close_text = __( 'Close', 'kt_woo_extras' );
			}
			if ( empty( $btn_text ) ) {
				$btn_text = __( 'Size Chart', 'kt_woo_extras' );
			}
			$js_out = 'onMouseOver="';
			if ( ! empty( $btn_background_hover ) ) {
				$js_out .= 'this.style.background=\'' . esc_attr( $btn_background_hover ) . '\'';
			}
			if ( ! empty( $btn_background_hover ) && ( ! empty( $btn_color_hover ) || ! empty( $btn_border_hover ) ) ) {
				 $js_out .= ',';
			}
			if ( ! empty( $btn_color_hover ) ) {
				$js_out .= 'this.style.color=\'' . esc_attr( $btn_color_hover ) . '\'';
			}
			if ( ! empty( $btn_color_hover ) && ! empty( $btn_border_hover ) ) {
				 $js_out .= ',';
			}
			if ( ! empty( $btn_border_hover ) ) {
				$js_out .= 'this.style.borderColor=\'' . esc_attr( $btn_border_hover ) . '\'';
			}
			$js_out .= '" onMouseOut="';
			if ( ! empty( $btn_background ) ) {
				$js_out .= 'this.style.background=\'' . esc_attr( $btn_background ) . '\'';
				$backcolor = $btn_background;
			} else {
				$js_out .= 'this.style.background=\'#777\'';
				$backcolor = '#777';
			}
			if ( ! empty( $btn_color ) ) {
				$js_out .= ',this.style.color=\'' . esc_attr( $btn_color ) . '\'';
				$color = $btn_color;
			} else {
				$js_out .= ',this.style.color=\'#fff\'';
				$color = '#fff';
			}
			if ( ! empty( $btn_border_color ) ) {
				$js_out .= ',this.style.borderColor=\'' . esc_attr( $btn_border_color ) . '\'';
				$border_color = $btn_border_color;
			} else {
				$js_out .= ',this.style.borderColor=\'transparent\'';
				$border_color = 'transparent';
			}
			$js_out .= '"';
			ob_start(); ?>
				<button class="kad-btn kt-size-btn kad-btn-primary kt-modal-btn" style="background-color:<?php echo esc_attr( $backcolor ); ?>; color:<?php echo esc_attr( $color ); ?>; border-color:<?php echo esc_attr( $border_color ); ?>; <?php echo esc_attr( $btn_border ); ?> <?php echo esc_attr( $btn_radius ); ?>" data-toggle="modal" data-target="#kt-modal-<?php echo esc_attr( $chartid ); ?>" <?php echo $js_out; ?>><?php echo esc_html( $btn_text ); ?></button>

				<div class="modal fade kt-size-modal <?php echo esc_attr( $table_class ); ?>" id="kt-modal-<?php echo esc_attr( $chartid ); ?>" style="<?php echo esc_attr( $b_overlay_color ); ?>" tabindex="-1" role="dialog" aria-labelledby="#kt-modal-label-<?php echo esc_attr( $chartid ); ?>" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content" style="<?php echo esc_attr( $background_color ); ?>">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 style="<?php echo esc_attr( $title_color ); ?>" class="modal-title" id="kt-modal-label-<?php echo esc_attr( $chartid ); ?>"><?php echo get_the_title( $chartid ); ?></h4>
					  </div>
					  <div class="modal-body" style="<?php echo esc_attr( $content_color ); ?>">
						<?php
						if ( $elementor_page && is_callable( 'Elementor\Plugin::instance' ) ) {
							$elementor_instance = Elementor\Plugin::instance();
							echo $elementor_instance->frontend->get_builder_content_for_display( $chartid );
						} else {
							echo apply_filters( 'kt_size_chart_content', do_shortcode( get_post_field( 'post_content', $chartid ) ) );
						}
						?>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="kad-btn" data-dismiss="modal"><?php echo esc_html( $modal_close_text ); ?></button>
					  </div>
					</div>
				  </div>
				</div>
			<?php
			 $output = ob_get_contents();
			ob_end_clean();
			echo $output;
		}
		public function kt_woo_size_chart_tab_content() {
			global $post;
			$chart_id = get_post_meta( $post->ID, '_kt_woo_size_chart_assign', true );
			if ( ! empty( $chart_id ) && $chart_id != '0' ) {
				$chartid = $chart_id;
			} else {
				$chartid = $this->kt_product_size_chart_from_cat( $post->ID );
			}
			if ( ! empty( $chartid ) && $chartid != false ) {
				$responsive_table = get_post_meta( $chartid, '_kt_woo_responsive_table', true );
			}
			if ( isset( $responsive_table ) && $responsive_table == false ) {
				$table_class = 'kt-size-table-nonresponsive';
			} else {
				$table_class = 'kt-size-table-responsive';
			}
			echo '<div class="' . esc_attr( $table_class ) . ' kt_woo_size_chart_tab_content">';
			echo apply_filters( 'kt_size_chart_content', get_post_field( 'post_content', $chartid ) );
			echo '</div>';
		}
		public function kt_woo_size_chart_tab( $tabs ) {
			global $post;
			$chart_id = get_post_meta( $post->ID, '_kt_woo_size_chart_assign', true );
			if ( ! empty( $chart_id ) && $chart_id != '0' ) {
				$chartid = $chart_id;
			} else {
				$chartid = $this->kt_product_size_chart_from_cat( $post->ID );
			}
			$priority = get_post_meta( $chartid, '_kt_woo_tab_priority', true );
			if ( ! empty( $priority ) ) {
				$tab_priority = $priority;
			} else {
				$tab_priority = 35;
			}
			$tabs['kt_size_chart_tab'] = array(
				'title' => get_the_title( $chartid ),
				'priority' => $tab_priority,
				'callback' => array( $this, 'kt_woo_size_chart_tab_content' ),
			);
			return $tabs;
		}
		/**
		 * Output Shortcode
		 *
		 * @param string $id Post ID.
		 */
		public function kt_product_size_chart_from_cat( $id ) {
			$product_cats   = wp_get_post_terms( $id, 'product_cat' );
			$pterms         = array();
			$final_chart_id = false;
			if ( $product_cats && ! is_wp_error( $product_cats ) ) {
				foreach ( $product_cats as $product_cat ) {
					$pterms[] = $product_cat->term_id;
				}
			}
			if ( empty( $pterms ) ) {
				return false;
			}
			$charts = get_posts(
				array(
					'post_type'      => 'kt_size_chart',
					'posts_per_page' => '100',
					'posts_status'   => 'publish',
					'orderby'        => 'ID',
					'order'          => 'DESC',
				)
			);
			if ( ! empty( $charts ) ) {
				foreach ( $charts as $chart ) {
					$chart_cats = get_the_terms( $chart->ID, 'product_cat' );
					$cterms     = array();
					if ( $chart_cats && ! is_wp_error( $chart_cats ) ) {
						foreach ( $chart_cats as $chart_cat ) {
							$cterms[] = $chart_cat->term_id;
						}
					}
					if ( ! empty( $cterms ) ) {
						if ( is_array( $cterms ) && is_array( $pterms ) ) {
							if ( array_intersect( $pterms, $cterms ) ) {
								$final_chart_id = $chart->ID;
							}
							if ( false !== $final_chart_id ) {
								break;
							}
						}
					}
				}
			}
			return $final_chart_id;
		}

		public function kt_woo_size_chart_metaboxes() {

			$prefix = '_kt_woo_';
			$kt_woo_size_chart = new_cmb2_box(
				array(
					'id'            => $prefix . 'size_chart',
					'title'         => __( 'Size Chart Settings', 'kadence-woo-extras' ),
					'object_types'  => array( 'kt_size_chart' ), // Post type
				)
			);

			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Placement', 'kadence-woo-extras' ),
					'id'            => $prefix . 'size_placement',
					'type'          => 'select',
					'options'          => array(
						'aftercart'     => __( 'Button after "add to cart"', 'kadence-woo-extras' ),
						'beforecart'    => __( 'Button before "add to cart"', 'kadence-woo-extras' ),
						'afterdesc'     => __( 'Button after "product short description"', 'kadence-woo-extras' ),
						'beforedesc'    => __( 'Button before "product short description"', 'kadence-woo-extras' ),
						'aftermeta'     => __( 'Button after "product meta"', 'kadence-woo-extras' ),
						'tab'           => __( 'Add as Tab, no button', 'kadence-woo-extras' ),
						'builder'       => __( 'Template builder widget', 'kadence-woo-extras' ),
					),
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Title Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'header_txt_color',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Text Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'content_txt_color',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Background Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'background_color',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Overlay Background Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'background_overlay_color',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Text Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_txt_color',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Hover Text Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_txt_color_hover',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Background', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_background_color',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Hover Background', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_background_color_hover',
					'type'          => 'colorpicker',
					'attributes' => array(
						'data-colorpicker' => json_encode(
							array(
								// Iris Options set here as values in the 'data-colorpicker' array
								'color' => true,
							)
						),
					),
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Border Radius', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_radius',
					'default'       => '0',
					'type'          => 'kt_woo_text_number',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Border Width', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_border',
					'default'       => '0',
					'type'          => 'kt_woo_text_number',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Border Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_border_color',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Hover Border Color', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_border_color_hover',
					'type'          => 'colorpicker',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal Button Text', 'kadence-woo-extras' ),
					'id'            => $prefix . 'btn_text',
					'type'          => 'text',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Modal "Close" Text', 'kadence-woo-extras' ),
					'id'            => $prefix . 'modal_close_text',
					'type'          => 'text',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'If tab, set priority', 'kadence-woo-extras' ),
					'desc'      => __( 'This determines where the tab is placed in your product tabs', 'kadence-woo-extras' ),
					'id'            => $prefix . 'tab_priority',
					'default'       => '35',
					'type'          => 'kt_woo_text_number',
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'      => __( 'Chart Categories', 'kadence-woo-extras' ),
					'desc'      => __( 'Select categories for chart to auto appear on', 'kadence-woo-extras' ),
					'id'        => $prefix . 'chart_cats',
					'type'      => 'taxonomy_multicheck',
					'taxonomy'  => 'product_cat', // Taxonomy Slug
					'inline'    => true, // Toggles display to inline
				)
			);
			$kt_woo_size_chart->add_field(
				array(
					'name'          => __( 'Responsive tables?', 'kadence-woo-extras' ),
					'id'            => $prefix . 'responsive_table',
					'desc'      => __( 'You can add a table into your content area using the shortcode button', 'kadence-woo-extras' ),
					'type'          => 'select',
					'options'          => array(
						'true'     => __( 'True', 'kadence-woo-extras' ),
						'false'    => __( 'False', 'kadence-woo-extras' ),
					),
				)
			);

			$kt_woo_size_chart_list  = new_cmb2_box(
				array(
					'id'            => $prefix . 'size_chart_list',
					'title'         => __( 'Product Size Chart', 'kadence-woo-extras' ),
					'object_types'  => array( 'product' ),
					'context'    => 'side',
					'priority'   => 'low',
				)
			);
			$kt_woo_size_chart_list->add_field(
				array(
					'name'          => __( 'Assign Size Chart', 'kadence-woo-extras' ),
					'desc'      => __( 'Choose a size chart to assign to product.', 'kadence-woo-extras' ),
					'id'            => $prefix . 'size_chart_assign',
					'type'          => 'select',
					'default'       => '0',
					'options_cb'    => 'kt_woo_size_chart_posts_options',
				)
			);

		}
	}

	$GLOBALS['kt_size_chart'] = new kt_size_chart();
}
