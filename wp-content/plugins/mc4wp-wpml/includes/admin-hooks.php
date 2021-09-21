<?php

add_filter( 'mc4wp_admin_menu_items', function( $items ) {
  $items['ptc'] = array(
    'title' => __( 'WPML', 'mc4wp-wpml' ),
    'text' => __( 'WPML', 'mc4wp-wpml' ),
    'slug' => 'wpml',
    'callback' => 'mc4wp_wpml_settings_page',
  );
  return $items;
} );

add_action( 'admin_init', function() {
  register_setting( 'mc4wp_wpml', 'mc4wp_wpml', 'mc4wp_wpml_sanitize_settings' );
} );
