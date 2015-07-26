<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
 
// Include local configuration
if (file_exists(dirname(__FILE__) . '/local-config.php')) {
	include(dirname(__FILE__) . '/local-config.php');
}

// Global DB config
if (!defined('DB_NAME')) {
	define('DB_NAME', 'zemeringvastgoed');
}
if (!defined('DB_USER')) {
	define('DB_USER', 'root');
}
if (!defined('DB_PASSWORD')) {
	define('DB_PASSWORD', 'test1234');
}
if (!defined('DB_HOST')) {
	define('DB_HOST', 'localhost');
}

/** Database Charset to use in creating database tables. */
if (!defined('DB_CHARSET')) {
	define('DB_CHARSET', 'utf8');
}

/** The Database Collate type. Don't change this if in doubt. */
if (!defined('DB_COLLATE')) {
	define('DB_COLLATE', '');
}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '&(G-$X6VAmaf)>QTeG]]2?`a_BW |}=/(=bt5] qpk%|;|~2m%ibU6O{#4%|`E+W');
define('SECURE_AUTH_KEY',  '`r<O&tp]deB5+yFwy~y+=Q@U{@7-&t^@V{<;0`&#+6nFNHisZoJsbh`[A+:2jd`Z');
define('LOGGED_IN_KEY',    '5a<Z+g+p:!6Z_ColmYM|kQhp}q8Hyq[Nu4Bx&M^nc(;!oB*}m_@9K^Ar @9oqW,G');
define('NONCE_KEY',        '|`UjW|WEpG0+MVQVLH7cC_tP28qC|>>p{35)XkOW.a=7iO35]xUdWeI@XkRE1dq-');
define('AUTH_SALT',        'EcX&|DR>j]0a|KH8-=&+X%$=rzp!P$YD4K$f#eC=H]fh`ZjU$0,eIy4?4=yXgHpp');
define('SECURE_AUTH_SALT', 'E>-bx%n8+98 dL$MluY+PyO.!4?;Vt:@DcxsW`(e`yc0M)$n@|t2Aa X(/f`Ivll');
define('LOGGED_IN_SALT',   '@r(EMvpc/,-J]|PH<Mw$qt@Bc^D:f+uDL+@+e Y3k_ }PV.DzAF$99&/D9|B-_Bj');
define('NONCE_SALT',       'w9,f,pZrV|K!03G[Od7ZuDun.,.:;q5K xRK+f:c-}}o+r)Sr1LC/3RQ?%XJhCDG');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'yp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if (!defined('WP_DEBUG')) {
	define('WP_DEBUG', false);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
