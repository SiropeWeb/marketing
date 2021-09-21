<div id="mc4wp-admin" class="wrap mc4wp-settings">

  <style type="text/css" scoped>
    .form-table table th { padding: 0; }
  </style>

  <h1 class="page-title">WPML Settings</h1>
  <?php settings_errors(); ?>

  <form method="POST" action="options.php">
    <?php settings_fields( 'mc4wp_wpml' ); ?>
      <table class="form-table">

        <tr valign="top">
          <th scope="row">Language map</th>
          <td>
            <table>
              <thead>
                <tr>
                  <th style="width: auto; text-align: left;">Language</th>
                  <th style="width: 20px;"></th>
                  <th style="width: auto; text-align: left;">Mailchimp list</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach( $languages as $language_code => $language ) { ?>
                  <tr>
                    <td style="padding-left: 0; padding-right: 0;">
                      <?php echo $language['display_name']; ?>
                    </td>
                    <td>&rarr;</td>
                    <td style="padding-left: 0;">
                      <select name="mc4wp_wpml[language_map][<?php echo esc_attr($language_code ); ?>]">
                        <option value="">-- use form or integration setting</option>
                        <?php foreach( $mailchimp_lists as $list ) { ?>
                          <option value="<?php echo $list->id; ?>" <?php selected( isset( $opts['language_map'][$language_code] ) && $opts['language_map'][$language_code] == $list->id, true ); ?>><?php echo $list->name; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <p class="help">Configure which site languages should subscribe to which lists. Please note that this will override the list setting for the specific form or integration settings.</p>
            </td>
          </tr><!-- / language map tr -->

          <tr valign="top">
            <th scope="row">Subscriber language</th>
            <td>
              <label>
                <input type="checkbox" name="mc4wp_wpml[set_subscriber_language]" value="1" <?php checked( $opts['set_subscriber_language'], 1 ); ?> />
                Set the subscriber's language based on the site language
              </label>
              <p class="help">
                By default, Mailchimp will guess a subscriber's language based on the browser language &amp; IP address that we include in every sign-up request. Use this option to explicitly set the subscriber language to the WPML site language.
              </p>
            </td>
          </tr>

        </table>

        <?php submit_button(); ?>
      </form>
    </div>
