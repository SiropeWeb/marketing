<?php

add_filter( 'mc4wp_lists', function( $list_ids ) {
  $opts = mc4wp_wpml_get_options();
  $language_map = $opts['language_map'];
  $language_code = mc4wp_wpml_get_current_language_code();

  if( ! empty( $language_map[$language_code] ) ) {
    return array( $language_map[ $language_code ] );
  }

  // no list in language map, use defaults (from form or integration setting)
  return $list_ids;
});

add_filter( 'mc4wp_subscriber_data', function( MC4WP_MailChimp_Subscriber $subscriber ) {
  $opts = mc4wp_wpml_get_options();

  if( $opts['set_subscriber_language'] ) {
    $language_code = mc4wp_wpml_get_current_language_code();
    $subscriber->language = strtolower( substr( $language_code, 0, 2 ) );
  }
  
  return $subscriber;
});
