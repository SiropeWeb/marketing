<?php


function mc4wp_wpml_settings_page() {
  global $sitepress;
  $opts = mc4wp_wpml_get_options();
  $languages =  $sitepress->get_active_languages();
  $mailchimp = new MC4WP_MailChimp();
  $mailchimp_lists = $mailchimp->get_lists();

  require __DIR__ . '/views/settings-page.php';
}

function mc4wp_wpml_sanitize_settings( $settings ) {
  return $settings;
}
