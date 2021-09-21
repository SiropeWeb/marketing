<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function wc_dropdown_variation_attribute_options( $args = array() ) {
	kt_variation_swatches_attribute_options( $args );
}
function kad_wc_radio_variation_attribute_options( $args = array() ) {
    kt_variation_swatches_attribute_options( $args );
}
// This determines which output to use:
function kt_variation_swatches_attribute_options($args) {
	$args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
	    'options' => false,
	    'attribute' => false,
	    'product' => false,
	    'selected' => false,
	    'name' => '',
	    'id' => '',
	    'class' => '',
	    'show_option_none' => __( 'Choose an option', 'woocommerce' )
		) );
	global $post;
    $key = md5( sanitize_title( $args['attribute']  ) );
	$type = get_post_meta( $post->ID, '_kt_variation_swatch_type', true );
	if(!empty($type)) {
	    if(!isset($type[$key]['display_type']) || $type[$key]['display_type'] == 'default') {
	        global $kt_woo_extras;
	        if(isset($kt_woo_extras['swatches_type'])) {
	            $kt_type = $kt_woo_extras['swatches_type'];
	        } else {
	            $kt_type = 'dropdown';
	        }
		} else {
	        $kt_type = $type[$key]['display_type'];
	    }
	} else {
		global $kt_woo_extras;
        if(isset($kt_woo_extras['swatches_type'])) {
            $kt_type = $kt_woo_extras['swatches_type'];
        } else {
            $kt_type = 'dropdown';
        }
	}
    // Get selected value.
    if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
        $selected_key = 'attribute_' . sanitize_title( $args['attribute'] );
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        $args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] );
        // phpcs:enable WordPress.Security.NonceVerification.Recommended
    }
    if( $kt_type == 'radio_box' ) {
		$args['class'] = 'kt-no-select2';
		echo '<div class="kt-radio-variation-container">';
		kt_variable_swatch_wc_dropdown_variation_attribute_options($args);
		kt_wc_radio_variation_attribute_options($args);
		echo '</div>';
	} elseif($kt_type == 'color_image' ) {
        $args['class'] = 'kt-no-select2';
        echo '<div class="kt-radio-variation-container">';
        kt_variable_swatch_wc_dropdown_variation_attribute_options($args);
        kt_wc_color_image_variation_attribute_options($args);
        echo '</div>';
    } elseif( $kt_type == 'taxonomy' ) {
        $args['class'] = 'kt-no-select2';
        echo '<div class="kt-radio-variation-container">';
        kt_variable_swatch_wc_dropdown_variation_attribute_options($args);
        kt_wc_color_image_tax_variation_attribute_options($args);
        echo '</div>';
    } else {
		kt_variable_swatch_wc_dropdown_variation_attribute_options($args);
	}
}
function kt_wc_radio_variation_attribute_options(  $args = array() ) {
    $args = wp_parse_args( $args, array(
      'options'          => false,
      'attribute'        => false,
      'product'          => false,
      'selected'         => false,
      'name'             => '',
      'id'               => ''
    ) );
    $options   = $args['options'];
    $product   = $args['product'];
    $attribute = $args['attribute'];
    $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
    $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
      $attributes = $product->get_variation_attributes();
      $options    = $attributes[ $attribute ];
    }
    echo '<fieldset id="' . esc_attr( $id ) .'" class="kad_radio_variations" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
    if ( ! empty( $options ) ) {
      if ( $product && taxonomy_exists( $attribute ) ) {
        // Get terms if this is a taxonomy - ordered. We need the names too.
        $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
        foreach ( $terms as $term ) {
          if ( in_array( $term->slug, $options ) ) {
            echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '"><input type="radio" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" value="' . esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $args['selected'] ), $term->slug, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '" name="'. sanitize_title($name).'">' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</label>';
          }
        }
      } else {
        foreach ( $options as $option ) {
          echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'"><input type="radio" value="' . esc_attr( $option ) . '" ' . checked( $args['selected'], $option, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'" name="'. sanitize_title($name).'">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</label>';
        }
      }
    }
    echo '</fieldset>';
  }
  function kt_wc_color_image_variation_attribute_options(  $args = array() ) {
    $args = wp_parse_args( $args, array(
      'options'          => false,
      'attribute'        => false,
      'product'          => false,
      'selected'         => false,
      'name'             => '',
      'id'               => ''
    ) );
    $options   = $args['options'];
    $product   = $args['product'];
    $attribute = $args['attribute'];
    $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
    $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
      $attributes = $product->get_variation_attributes();
      $options    = $attributes[ $attribute ];
    }
    $key = md5( sanitize_title( $args['attribute']  ) );
    global $post, $kt_woo_extras;
    $type           = get_post_meta( $post->ID, '_kt_variation_swatch_type', true );
    $type_options   = get_post_meta( $post->ID, '_kt_variation_swatch_type_options', true );
    // Label Size
    if(!empty($type)) {
	    if(!isset($type[$key]['display_size']) || $type[$key]['display_size'] == 'default') {
	        if(isset($kt_woo_extras['swatches_size'])) {
	            $size = $kt_woo_extras['swatches_size'];
	        } else {
	            $size = '60';
	        }
	    } else {
	        $size = $type[$key]['display_size'];
	    }
	} else {
		if(isset($kt_woo_extras['swatches_size'])) {
            $size = $kt_woo_extras['swatches_size'];
        } else {
            $size = '60';
        }
	}
    $label_size = 'width:'.$size.'px; height:'.$size.'px;';
    // Show Label?
    if(!empty($type)) {
	    if(!isset($type[$key]['display_label']) || $type[$key]['display_label'] == 'default') {
	        if(isset($kt_woo_extras['swatches_label'])) {
	            $label = $kt_woo_extras['swatches_label'];
	        } else {
	            $label = 'false';
	        }
	    } else {
	        $label = $type[$key]['display_label'];
	    }
	} else {
		if(isset($kt_woo_extras['swatches_label'])) {
            $label = $kt_woo_extras['swatches_label'];
        } else {
            $label = 'false';
        }
	}

    echo '<fieldset id="' . esc_attr( $id ) .'" class="kad_radio_variations kt-image-color-sw" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
    if ( ! empty( $options ) ) {
      if ( $product && taxonomy_exists( $attribute ) ) {
        // Get terms if this is a taxonomy - ordered. We need the names too.
        $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
        foreach ( $terms as $term ) {
          if ( in_array( $term->slug, $options ) ) {
            $option_key =  md5( $term->slug );
            // color or Image?
            if(!empty($type_options)) {
	            if(isset($type_options[$key][$option_key]['type']) && $type_options[$key][$option_key]['type'] == 'image') {
	                if(isset($type_options[$key][$option_key]['image']) && !empty($type_options[$key][$option_key]['image']) ) {
	                    $image_id = $type_options[$key][$option_key]['image_id'];
	                } else {
	                    $image_id = null;
	                }
	                $color = '';
	                $data_color = '';
	                $image = true;
	            } else {
	                if(isset($type_options[$key][$option_key]['color']) && !empty($type_options[$key][$option_key]['color']) ) {
	                    $color = 'background:'.$type_options[$key][$option_key]['color'].';'; 
	                    $data_color = 'data-color="'.$type_options[$key][$option_key]['color'].'"';
	                } else {
	                    $color = 'background:#999999;'; 
	                    $data_color = 'data-color="#999999"';
	                } 
	                 $image = false;
	            }
	        } else {
                $color = 'background:#999999;'; 
                $data_color = 'data-color="#999999"';
                $image = false;
	        }
            echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '" '.$data_color.'>';
            if($label == 'above'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name', $term->name ).'</span>';
            }
            if($label == 'tooltip'){
                $tooldata = 'data-toggle="tooltip" data-placement="top" data-original-title="'.esc_attr(apply_filters( 'woocommerce_variation_option_name', $term->name )).'"';
            } else {
                $tooldata = '';
            }
            echo '<span class="kt_color_sw_span" style="'.$color .' '. $label_size.'" '.$tooldata.'>';
            if($image) {
            	echo kt_woo_get_full_image_output( $size, $size, true, 'kt-sw-img', sanitize_title( $term->name ), $image_id, false, false, false );
            }
            echo '</span>';
            echo '<input type="radio" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" value="' . esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $args['selected'] ), $term->slug, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '" name="'. sanitize_title($name).'">';
            if($label == 'below'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name', $term->name ).'</span>';
            }
            echo '</label>';
          }
        }
      } else {
        foreach ( $options as $option ) {
            // color or Image?
            $option_key = md5( sanitize_title( strtolower( $option ) ) );
            if(isset($type_options[$key][$option_key]['type']) && $type_options[$key][$option_key]['type'] == 'image') {
                if(isset($type_options[$key][$option_key]['image']) && !empty($type_options[$key][$option_key]['image']) ) {
                    $image_id = $type_options[$key][$option_key]['image_id'];
                } else {
                    $image_id = null;
                }
                $color = '';
                $data_color = '';
                $image = true;
            } else {
                if(isset($type_options[$key][$option_key]['color']) && !empty($type_options[$key][$option_key]['color']) ) {
                    $color = 'background:'.$type_options[$key][$option_key]['color'].';'; 
                    $data_color = 'data-color="'.$type_options[$key][$option_key]['color'].'"';
                } else {
                    $color = 'background:#999999;'; 
                    $data_color = 'data-color="#999999"';
                } 
                 $image = false;
            }
            echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'" '.$data_color.'>';
            if($label == 'above'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name',$option ).'</span>';
            }
             if($label == 'tooltip'){
                $tooldata = 'data-toggle="tooltip" data-placement="top" data-original-title="'.esc_attr(apply_filters( 'woocommerce_variation_option_name', $option )).'"';
            } else {
                $tooldata = '';
            }
             echo '<span class="kt_color_sw_span" style="'.$color .' '. $label_size.'" '.$tooldata.'>';
            if($image) {
            	echo kt_woo_get_full_image_output( $size, $size, true, 'kt-sw-img', sanitize_title( $option ), $image_id, false, false, false );
            }
            echo '</span>';
            echo '<input type="radio" value="' . esc_attr( $option ) . '" ' . checked( $args['selected'], $option, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'" name="'. sanitize_title($name).'">';
            if($label == 'below'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name', $option ).'</span>';
            }
          echo '</label>';
        }
      }
    }
    echo '</fieldset>';
  }
  function kt_wc_color_image_tax_variation_attribute_options(  $args = array() ) {
    $args = wp_parse_args( $args, array(
      'options'          => false,
      'attribute'        => false,
      'product'          => false,
      'selected'         => false,
      'name'             => '',
      'id'               => ''
    ) );
    $options   = $args['options'];
    $product   = $args['product'];
    $attribute = $args['attribute'];
    $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
    $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
      $attributes = $product->get_variation_attributes();
      $options    = $attributes[ $attribute ];
    }
    $key = md5( sanitize_title( $args['attribute']  ) );
    global $post, $kt_woo_extras;
    $type           = get_post_meta( $post->ID, '_kt_variation_swatch_type', true );
    $type_options   = get_post_meta( $post->ID, '_kt_variation_swatch_type_options', true );
    // Label Size
    if(!isset($type[$key]['display_size']) || $type[$key]['display_size'] == 'default') {
        if(isset($kt_woo_extras['swatches_size'])) {
            $size = $kt_woo_extras['swatches_size'];
        } else {
            $size = '60';
        }
    } else {
        $size = $type[$key]['display_size'];
    }
    $label_size = 'width:'.$size.'px; height:'.$size.'px;';
    // Show Label?
    if(!isset($type[$key]['display_label']) || $type[$key]['display_label'] == 'default') {
        if(isset($kt_woo_extras['swatches_label'])) {
            $label = $kt_woo_extras['swatches_label'];
        } else {
            $label = 'false';
        }
    } else {
        $label = $type[$key]['display_label'];
    }

    echo '<fieldset id="' . esc_attr( $id ) .'" class="kad_radio_variations kt-image-color-sw" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
    if ( ! empty( $options ) ) {
      if ( $product && taxonomy_exists( $attribute ) ) {
        // Get terms if this is a taxonomy - ordered. We need the names too.
        $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
        foreach ( $terms as $term ) {
          if ( in_array( $term->slug, $options ) ) {
            $cat_term_id = $term->term_id;
            $meta = get_option('kt_woo_extras_tax_swatch_type');
            if (empty($meta)){ 
                $meta = array();
            }
            if (!is_array($meta)) {
                $meta = (array) $meta;
            }
            $meta = isset($meta[$cat_term_id]) ? $meta[$cat_term_id] : array();
            // color or Image?
            if(isset($meta['kt_woo_extras_swatch_type']) && $meta['kt_woo_extras_swatch_type'] == 'image') {
                if(isset($meta['kt_woo_extras_swatch_image']) && !empty($meta['kt_woo_extras_swatch_image']) ) {
                    $image_array = $meta['kt_woo_extras_swatch_image'];
                    $image_id = $image_array[0];
                    
                } else {
                    $image_id = null;
                }
                $color = '';
                $data_color = '';
                $image = true;
            } else {
                if(isset($meta['kt_woo_extras_swatch_color']) && !empty($meta['kt_woo_extras_swatch_color']) ) {
                    $color = 'background:'.$meta['kt_woo_extras_swatch_color'].';'; 
                    $data_color = 'data-color="'.$meta['kt_woo_extras_swatch_color'].'"';
                } else {
                    $color = 'background:#999999;'; 
                    $data_color = 'data-color="#999999"';
                } 
                 $image = false;
            }
            echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '" '.$data_color.'>';
            if($label == 'above'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name', $term->name ).'</span>';
            }
            if($label == 'tooltip'){
                $tooldata = 'data-toggle="tooltip" data-placement="top" data-original-title="'.esc_attr(apply_filters( 'woocommerce_variation_option_name', $term->name )).'"';
            } else {
                $tooldata = '';
            }
            echo '<span class="kt_color_sw_span" style="'.$color .' '. $label_size.'" '.$tooldata.'>';
            if($image) {
            	echo kt_woo_get_full_image_output($size, $size, true, 'kt-sw-img', sanitize_title($term->name), $image_id, false, false, false);
            }
            echo '</span>';
            echo '<input type="radio" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" value="' . esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $args['selected'] ), $term->slug, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( $term->slug ) . '" name="'. sanitize_title($name).'">';
            if($label == 'below'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name', $term->name ).'</span>';
            }
            echo '</label>';
          }
        }
      } else {
        foreach ( $options as $option ) {
            // color or Image?
            $option_key = md5( sanitize_title( strtolower( $option ) ) );
            if(isset($type_options[$key][$option_key]['type']) && $type_options[$key][$option_key]['type'] == 'image') {
                if(isset($type_options[$key][$option_key]['image']) && !empty($type_options[$key][$option_key]['image']) ) {
                    $image_id = $type_options[$key][$option_key]['image_id'];
                } else {
                   $image_id = null;
                }
                $color = '';
                $data_color = '';
                $image = true;
            } else {
                if(isset($type_options[$key][$option_key]['color']) && !empty($type_options[$key][$option_key]['color']) ) {
                    $color = 'background:'.$type_options[$key][$option_key]['color'].';'; 
                    $data_color = 'data-color="'.$type_options[$key][$option_key]['color'].'"';
                } else {
                    $color = 'background:#999999;'; 
                    $data_color = 'data-color="#999999"';
                } 
                 $image = false;
            }
            echo '<label for="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'" '.$data_color.'>';
            if($label == 'above'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name',$option ).'</span>';
            }
             if($label == 'tooltip'){
                $tooldata = 'data-toggle="tooltip" data-placement="top" data-original-title="'.esc_attr(apply_filters( 'woocommerce_variation_option_name', $option )).'"';
            } else {
                $tooldata = '';
            }
             echo '<span class="kt_color_sw_span" style="'.$color .' '. $label_size.'" '.$tooldata.'>';
            if($image) {
               echo kt_woo_get_full_image_output($size, $size, true, 'kt-sw-img', sanitize_title( $option ), $image_id, false, false, false);
            }
            echo '</span>';
            echo '<input type="radio" value="' . esc_attr( $option ) . '" ' . checked( $args['selected'], $option, false ) . ' id="'. esc_attr( sanitize_title($name) ) . esc_attr( sanitize_title( $option ) ) .'" name="'. sanitize_title($name).'">';
            if($label == 'below'){
                echo '<span class="label_text">'.apply_filters( 'woocommerce_variation_option_name', $option ).'</span>';
            }
          echo '</label>';
        }
      }
    }
    echo '</fieldset>';
  }

  function kt_variable_swatch_wc_dropdown_variation_attribute_options( $args = array() ) {
        $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
            'options'          => false,
            'attribute'        => false,
            'product'          => false,
            'selected'         => false,
            'name'             => '',
            'id'               => '',
            'class'            => '',
            'show_option_none' => __( 'Choose an option', 'kadence-woo-extras' )
        ) );
        $options   = $args['options'];
        $product   = $args['product'];
        $attribute = $args['attribute'];
        $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class     = $args['class'];

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }
        global $kt_woo_extras;
        if(isset($kt_woo_extras['choose_option_text']) && !empty($kt_woo_extras['choose_option_text']) ) {
            $args['show_option_none'] = $kt_woo_extras['choose_option_text'];
        }
        if( 'kt-no-select2' == $class ) {
        	$html = '<select class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
        } else {
        	$html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
        }

        if ( $args['show_option_none'] ) {
            $html .= '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
        }

        if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options ) ) {
                        $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                }
            }
        }

        $html .= '</select>';

        echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html );
    }