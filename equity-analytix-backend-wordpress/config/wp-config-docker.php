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
define('DB_NAME', 'equity_wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '&3b!yiZ9Do');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('EA_CHAT_DOMAIN', 'http://localhost:8083');
define('EA_CHAT_API', 'http://equity-nginx:82');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'h|#|}_]C+-#j5D*;1J4tX7pm)U1- ~VS;Jn;x<2b}yoG!}RoHcCAVC{ST*zJ{V4^');
define('SECURE_AUTH_KEY',  'svdrpznOxQ`$0cQ2pCJ4H+!c-+r|H;]:x6miGV/dD-4y:Mz/vm_X1Bsi}hg>[1&N');
define('LOGGED_IN_KEY',    '+*OkN=+VwCHQ*fX34z[ncd2YD?(G&Nt~kNJK@[b8cQrXX^}oLZgQ3u@.!&|U|Lj.');
define('NONCE_KEY',        '[g8q:l/z+ArdY7^|u*.H1z4Um-U<gjs1-b(?YOD$)hbwfu2pYkva8hqLj]#cS-up');
define('AUTH_SALT',        '@`(r0H2m~1>f/^x?ex-rOi|4p^&Il@%tz|R{ fdXrI[`jfH,-rxRhZ-k`5_sB3ZZ');
define('SECURE_AUTH_SALT', '}k5VLc8!t|m(-Hz^p_$ ) .FJVv7kZZ1:kj8Y*.1fdWPOfs#F::R6:_A%R)^Dt#`');
define('LOGGED_IN_SALT',   'qALDO^DlV+|+UE285vD^/z!.RF9,$q,I:=7uS9@`oD1 f Cj<+Zs6~c#G7}xf=Aj');
define('NONCE_SALT',       '$f]K9@km@|H%#g=j<gP1nu!8?|q@F1-Xz|]lJ4WrekaO(n-8c`AR}0M:=!DqjE|_');

/**#@-*/

define('REDIS_HOST', 'localhost');
define('REDIS_PORT', 6379);

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
ini_set("display_errors", false);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
