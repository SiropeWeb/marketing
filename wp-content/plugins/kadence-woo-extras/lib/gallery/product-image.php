<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $woocommerce, $product, $kt_woo_extras;
$shortcode = get_post_meta( $post->ID, '_kt_woo_gallery_shortcode', true );
if ( isset( $shortcode ) && ! empty( $shortcode ) ) {
	 echo '<div class="images woocommerce-product-gallery kad-light-gallery kt-shortcode-gallery"><div class="product_image">';
		echo apply_filters( 'the_content', $shortcode );
	 echo '</div></div>';
} else {
	if ( isset( $kt_woo_extras['ga_image_width'] ) ) {
		$productimgwidth = $kt_woo_extras['ga_image_width'];
	} else {
		$productimgwidth = 465;
	}
	if ( isset( $kt_woo_extras['ga_image_ratio'] ) ) {
		$img_ratio = $kt_woo_extras['ga_image_ratio'];
	} else {
		$img_ratio = 'square';
	}
	if ( $img_ratio == 'custom' ) {
		if ( isset( $kt_woo_extras['ga_image_height'] ) ) {
			$productimgheight = $kt_woo_extras['ga_image_height'];
		} else {
			$productimgheight = 465;
		}
	} elseif ( $img_ratio == 'portrait' ) {
				$tempproductimgheight = $productimgwidth * 1.35;
				$productimgheight = floor( $tempproductimgheight );
	} elseif ( $img_ratio == 'landscape' ) {
				$tempproductimgheight = $productimgwidth / 1.35;
				$productimgheight = floor( $tempproductimgheight );
	} elseif ( $img_ratio == 'landscape32' ) {
				$tempproductimgheight = $productimgwidth / 1.5;
				$productimgheight = floor( $tempproductimgheight );
	} elseif ( $img_ratio == 'widelandscape' ) {
				$tempproductimgheight = $productimgwidth / 2;
				$productimgheight = floor( $tempproductimgheight );
	} else {
				$productimgheight = $productimgwidth;
	}
	if ( isset( $kt_woo_extras['ga_slider_layout'] ) ) {
		$layout = $kt_woo_extras['ga_slider_layout'];
		if ( $layout == 'left' || $layout == 'right' ) {
			$vlayout = 'true';
		} else {
			$vlayout = 'false';
		}
	} else {
		$layout = 'above';
		$vlayout = 'false';
	}

	if ( isset( $kt_woo_extras['ga_thumb_columns'] ) ) {
		$thumb_columns = $kt_woo_extras['ga_thumb_columns'];
	} else {
		$thumb_columns = 7;
	}
	if ( $vlayout == 'true' ) {
		$thumb_productimgheight = floor( $productimgheight / $thumb_columns ) - 2;
	} else {
		$thumb_productimgwidth = floor( $productimgwidth / $thumb_columns );
	}
	if ( isset( $kt_woo_extras['ga_thumb_image_ratio'] ) ) {
		$thumb_img_ratio = $kt_woo_extras['ga_thumb_image_ratio'];
	} else {
		$thumb_img_ratio = 'square';
	}

	if ( $vlayout == 'true' ) {
		if ( $thumb_img_ratio == 'portrait' ) {
					$tempproductimgwidth = $thumb_productimgheight / 1.35;
					$thumb_productimgwidth = floor( $tempproductimgwidth );
		} elseif ( $thumb_img_ratio == 'landscape' ) {
					$tempproductimgwidth = $thumb_productimgheight * 1.35;
					$thumb_productimgwidth = floor( $tempproductimgwidth );
		} elseif ( $thumb_img_ratio == 'landscape32' ) {
					$tempproductimgwidth = $thumb_productimgheight * 1.5;
					$thumb_productimgwidth = floor( $tempproductimgwidth );
		} elseif ( $thumb_img_ratio == 'widelandscape' ) {
					$tempproductimgwidth = $thumb_productimgheight * 2;
					$thumb_productimgwidth = floor( $tempproductimgwidth );
		} elseif ( $thumb_img_ratio == 'inherit' ) {
				   $thumb_productimgwidth = 120;
				   $thumb_productimgheight = null;
		} else {
					$thumb_productimgwidth = $thumb_productimgheight;
					// $thumb_productpercentwidth = ( $thumb_productimgwidth / $productimgwidth ) * 100;
		}
	} else {
		if ( $thumb_img_ratio == 'portrait' ) {
					$tempproductimgheight = $thumb_productimgwidth * 1.35;
					$thumb_productimgheight = floor( $tempproductimgheight );
		} elseif ( $thumb_img_ratio == 'landscape' ) {
					$tempproductimgheight = $thumb_productimgwidth / 1.35;
					$thumb_productimgheight = floor( $tempproductimgheight );
		} elseif ( $thumb_img_ratio == 'landscape32' ) {
					$tempproductimgheight = $thumb_productimgwidth / 1.5;
					$thumb_productimgheight = floor( $tempproductimgheight );
		} elseif ( $thumb_img_ratio == 'inherit' ) {
				   $thumb_productimgheight = null;
		} elseif ( $thumb_img_ratio == 'widelandscape' ) {
					$tempproductimgheight = $thumb_productimgwidth / 2;
					$thumb_productimgheight = floor( $tempproductimgheight );
		} else {
					$thumb_productimgheight = $thumb_productimgwidth;
		}
	}
	if ( $layout == 'left' ) {
		$margin = 'margin-left:' . floor( $thumb_productimgwidth + 4 ) . 'px;';
		$thumbwidth = 'width:' . floor( $thumb_productimgwidth + 4 ) . 'px;';
	} elseif ( $layout == 'right' ) {
		 $margin = 'margin-right:' . floor( $thumb_productimgwidth + 4 ) . 'px;';
		 $thumbwidth = 'width:' . floor( $thumb_productimgwidth + 4 ) . 'px;';
	} else {
		 $margin = '';
		 $thumbwidth = 'width:auto;';
	}

	if ( isset( $kt_woo_extras['ga_trans_type'] ) ) {
		$transtype = $kt_woo_extras['ga_trans_type'];
	} else {
		$transtype = 'false';
	}
	if ( isset( $kt_woo_extras['ga_show_caption'] ) ) {
		$show_caption = $kt_woo_extras['ga_show_caption'];
	} else {
		$show_caption = 'false';
	}
	if ( isset( $kt_woo_extras['ga_slider_autoplay'] ) ) {
		$autoplay = $kt_woo_extras['ga_slider_autoplay'];
	} else {
		$autoplay = 'false';
	}
	if ( isset( $kt_woo_extras['ga_slider_pausetime'] ) ) {
		$pausetime = $kt_woo_extras['ga_slider_pausetime'];
	} else {
		$pausetime = '7000';
	}
	if ( isset( $kt_woo_extras['ga_slider_transtime'] ) ) {
		$transtime = $kt_woo_extras['ga_slider_transtime'];
	} else {
		$transtime = '400';
	}
	if ( isset( $kt_woo_extras['ga_zoom'] ) ) {
		$zoomactive = $kt_woo_extras['ga_zoom'];
	} else {
		$zoomactive = '0';
	}
	if ( isset( $kt_woo_extras['ga_zoom_type'] ) ) {
		$zoomtype = $kt_woo_extras['ga_zoom_type'];
	} else {
		$zoomtype = 'window';
	}
	if ( $zoomactive == 1 ) {
		$arrows = 'false';
	} else {
		if ( isset( $kt_woo_extras['ga_slider_arrows'] ) && 'true' == $kt_woo_extras['ga_slider_arrows'] ) {
			$arrows = 'true';
		} else {
			$arrows = 'false';
		}
	}

	if ( has_post_thumbnail() ) {
		$featureid[] = get_post_thumbnail_id();
		if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
			$attachment_ids = $product->get_gallery_image_ids();
		} else {
			$attachment_ids = $product->get_gallery_attachment_ids();
		}
		$images = array_merge( $featureid, $attachment_ids );
		$count = count( $images );
		if ( $count > $thumb_columns ) {
			$thumb_class = 'kt_thumb_show_arrow';
			$center = 'true';
			$thumb_max_width = '';
		} else {
			if ( $vlayout == 'true' ) {
				  $thumb_max_width = '';
				  $center = 'true';
				  $thumb_class = 'kt_thumb_show_arrow';
			} else {
				   $thumb_max_width = 'max-width:' . floor( floor( $count * 4 ) + floor( $count * $thumb_productimgwidth ) ) . 'px; margin:0 auto;';
				   $center = 'false';
				   $thumb_columns = $count;
				   $thumb_class = 'kt_thumb_hide_arrow';
			}
		}
		if ( empty( $attachment_ids ) ) {
			$margin = '';
			$layout = 'above';
			$vlayout == 'false';
		}
		$wrapper_classes   = apply_filters(
			'woocommerce_single_product_image_gallery_classes',
			array(
				'woocommerce-product-gallery',
				'woocommerce-product-gallery--with-images',
				'images',
			)
		);
		echo '<div class="kad-light-gallery kt-layout-' . esc_attr( $layout ) . ' ' . esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ) . ' kt-slick-slider"><div class="product_image loading">';
			echo '<div id="pr-slick" class="ktslickslider slick-slider ga-slick-init" style="' . $margin . '" data-slick-speed="' . esc_attr( $pausetime ) . '" data-slick-animation-speed="' . esc_attr( $transtime ) . '" data-slick-vlayout="' . esc_attr( $vlayout ) . '"  data-slick-animation="' . esc_attr( $transtype ) . '" data-slick-auto="' . esc_attr( $autoplay ) . '" data-slick-arrows="' . esc_attr( $arrows ) . '" data-gallery-items="' . esc_attr( $count ) . '" data-zoom-type="' . esc_attr( $zoomtype ) . '" data-visible-captions="' . esc_attr( $show_caption ) . '" data-zoom-active="' . esc_attr( $zoomactive ) . '" data-slick-thumb-show="' . esc_attr( $thumb_columns ) . '" data-slick-thumb-center="' . esc_attr( $center ) . '">';
			$number = 1;
		foreach ( $images as $slide ) :
			$alt = esc_attr( get_post_meta( $slide, '_wp_attachment_image_alt', true ) );
			if ( ! empty( $alt ) ) {
				$alttag = $alt;
			} else {
				$alttag = esc_attr( get_post_field( 'post_title', $slide ) );
			}
			$caption = get_post_field( 'post_excerpt', $slide );
			if ( empty( $caption ) ) {
				$data_caption = get_post_field( 'post_title', $slide );
			} else {
				$data_caption = $caption;
			}
			$img = kt_woo_get_image_array( $productimgwidth, $productimgheight, true, 'attachment-shop-single', $alttag, $slide, false, 'woocommerce_single' );
			if ( $number == 1 ) {
				$image_one_output = 'itemprop="image" class="woocommerce-main-image zoom kt-image-slide kt-no-lightbox"';
				// $images_class = 'images';
				$images_class = '';
			} else {
				$image_one_output = 'itemprop="image" class="zoom kt-image-slide kt-no-lightbox"';
				$images_class = '';
			}
				$html = '<div>';
					$html .= '<div class="' . esc_attr( $images_class ) . '">';
						$html .= '<a href="' . esc_url( $img['full'] ) . '"  data-rel="lightbox" ' . $image_one_output . ' title="' . esc_attr( get_post_field( 'post_title', $slide ) ) . '">';
							$html .= '<img width="' . esc_attr( $img['width'] ) . '" class="attachment-shop-single" data-caption="' . esc_attr( $data_caption ) . '" title="' . esc_attr( get_post_field( 'post_title', $slide ) ) . '" data-zoom-image="' . esc_url( $img['full'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['src'] ) . '" alt="' . esc_attr( $img['alt'] ) . '" ' . $img['srcset'] . ' />';
			if ( 'true' == $show_caption && ! empty( $caption ) ) {
				$html .= '<span class="sp-gal-image-caption">' . wp_kses_post( $caption ) . '</span>';
			}
						$html .= '</a>';
					$html .= '</div>';
				$html .= '</div>';
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $slide );
					$number ++;
				endforeach;
		echo '</div>';
		if ( ! empty( $attachment_ids ) ) {
			echo '<div id="pr-thumbnails" class="ktslickslider ' . esc_attr( $thumb_class ) . '" style="' . esc_attr( $thumbwidth ) . ' ' . esc_attr( $thumb_max_width ) . '">';
			foreach ( $images as $slide ) :
				$alt = esc_attr( get_post_meta( $slide, '_wp_attachment_image_alt', true ) );
				if ( ! empty( $alt ) ) {
							$alttag = $alt;
				} else {
						$alttag = esc_attr( get_the_title( $slide ) );
				}
						$img = kt_woo_get_image_array( $thumb_productimgwidth, $thumb_productimgheight, true, 'attachment-shop-single', $alttag, $slide, false, 'thumbnail' );
						$html = '<div>';
						$html .= '<img width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['src'] ) . '" alt="' . esc_attr( $img['alt'] ) . '" ' . $img['srcset'] . ' />';
						$html .= '</div>';
						echo apply_filters( 'kadence_single_product_image_thumbnail_html', $html, $slide );
						endforeach;
				echo '</div>';
		}
		echo '</div>';

	} else {
		echo '<div class="kad-light-gallery"><div class="product_image">';
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $post->ID );
		echo '</div>';
	}
	echo '</div>';
}
