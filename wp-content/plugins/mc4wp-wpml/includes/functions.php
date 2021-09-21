<?php

/**
* @return array
*/
function mc4wp_wpml_get_options() {
  $opts = get_option( 'mc4wp_wpml', array() );
  $defaults = array(
    'language_map' => array(),
    'set_subscriber_language' => 0,
  );
  $opts = array_merge( $defaults, $opts );
  return $opts;
}

/**
* @return string
*/
function mc4wp_wpml_get_current_language_code() {
  $lang = (string) apply_filters( 'wpml_current_language', null );
  return $lang;
}
