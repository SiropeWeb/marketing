<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_5gr0g' );

/** MySQL database username */
define( 'DB_USER', 'wp_a6aor' );

/** MySQL database password */
define( 'DB_PASSWORD', 's8#$lREAEKY63zBt' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '69&7DRW6n%dMAu/+00t0U%RlXFw7NRGf4I9c~+Hysil55qA_bn*i0ALK[7T%mPK4');
define('SECURE_AUTH_KEY', '3RVa@oBm60~un6b9I1fv06Yr7P)b;qdK:6otjF[3JE]GM7v1fcv]@09h)v3e%53M');
define('LOGGED_IN_KEY', '1N-x/Wwj[X04G(I/w[9@8l9d70~aN6/9(%a[;33Wc1dlyPRbUqgS!~!;15C1U[7]');
define('NONCE_KEY', ')o&TeR%xPqv6/tmXDU9QXTk9JK;VD;nyshhYk35v51!v/S08@33hAh45ff0e@0&B');
define('AUTH_SALT', '9pQ*kSWw840Xj7U6/oR22S&]dc3*~YH7;tN(5eNj3|3%38L2[+Z|OcLm4IK1B&5T');
define('SECURE_AUTH_SALT', 'D27#07jNP~#9tQDQlcN0UX82~F9%10~|jfPGd!@0kcwYaJJ9~&vm6Vhd3@GB8iW]');
define('LOGGED_IN_SALT', 'FvZY75y/b5ygGrWK/3NA:+0ek3647p0%[uNj95siYb*2b!:dj|]~]|F)70j:!&1n');
define('NONCE_SALT', 'Q93#lIi@8&z-]Y81qo(8[74e7022Nr9Ycsm~0Y&jg4/22&&WmQHypu008SvY0653');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'eQI6l79_';


define('WP_ALLOW_MULTISITE', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
