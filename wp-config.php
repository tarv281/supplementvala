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
define( 'DB_NAME', 'ezhqprjxatu7dss6' );

/** MySQL database username */
define( 'DB_USER', 'z4hkodnj9cfvxdl8' );

/** MySQL database password */
define( 'DB_PASSWORD', 'kmq2ts4reqdly6iu' );

/** MySQL hostname */
define( 'DB_HOST','dyud5fa2qycz1o3v.cbetxkdyhwsb.us-east-1.rds.amazonaws.com' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dpkw7ltemjsmn0wicpfzbsr9i1bgscvc6psrgenxyngmecwuudjv9jw2lsuiknmw' );
define( 'SECURE_AUTH_KEY',  'gtz775u5280quyl1fmehthvldoe0wi7n0ct8mtn0udftsqwqky8lcaw4q9mavfcj' );
define( 'LOGGED_IN_KEY',    'olaaxctmc39cqpogunjdsvoknxupcsgs6qa6pdhsl5kjmgieznhpoamyahqflecr' );
define( 'NONCE_KEY',        'bezqccwmywvymw4goets5nh3a7hx23m63bilxwfg3tob1rdurikdefoddho4jexg' );
define( 'AUTH_SALT',        'hurv7svbbhlgkwzfwqdl4sh3tdrqtxie6lrcllsds84uvqrd6avgn1hustxisktx' );
define( 'SECURE_AUTH_SALT', 'hhfhqg1y0ifinps3btdbruayckjidl3yhxz4daz18oewjbzgrvxi7ola8g5mcmbx' );
define( 'LOGGED_IN_SALT',   'scyjczh92kslorja5duzrrxcogqphee6wrvbuchufnrefg3f3raujqnpjmfoscrm' );
define( 'NONCE_SALT',       'rifvdqwxtbauh3gdltchqyokbfvaoxac9hqwkvnzscugpa0z5hy4kf0doksvvzoy' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpts_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
