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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('DB_NAME', 'ajc_staging');
define('DB_NAME', 'ajc_restore');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
//define('DB_PASSWORD', 'baldur434241');
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+XUGB4pXB|jbKd$dcgRm`u-PhzE]L+`s|_Q7+45 =p$iz{uBCVxZR~^~XP4Om6?+');
define('SECURE_AUTH_KEY',  'ruk82&3u?J<khKGoxU+@w5Y-K|@-WhF7&UGsl=DlJmnW/&u+LS?l}3wZ+1b:VfF[');
define('LOGGED_IN_KEY',    '?ol|*g39JskEi}{|p;S&+iEXB$?m7q#e.cqOM; +-q{>_U=U;HS|WvX?MYe`Bd<-');
define('NONCE_KEY',        ')|F^._T;K1+<;j]&mQU%s[Z#K0wJQTC~7Caez[~*c%mA2y2(o>vJ{j&^yN}l<kRq');
define('AUTH_SALT',        '<h%Uq^emQA:a,+wEeSktqd/-z7}S|C-<Up$@,+mVif5z2}iYG`!F-$RwRTw-kWOx');
define('SECURE_AUTH_SALT', '[6rJF_ps}e0Z)s NM+Dv=,|PZ_2!z_b!@}H=RE2N2Ya+%knW*VVN6|-?|Hvs,LUh');
define('LOGGED_IN_SALT',   '.-p7Mw yFk]moIB(@()i~s`h+}ua5RZXN^T$x5FGIq0Ah[C0|J+Z6APXk.4Xm*rU');
define('NONCE_SALT',       'cKQh_jsXs}89+t)`8sArj;9RF-%g{@c!K_+:U<Ld{1vaLLq;Stb4d9V-Y:-n#JW>');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

define('WP_DEBUG', false);
define('WP_MEMORY_LIMIT', '1000M');
define('WP_MAX_MEMORY_LIMIT', '1000M');

set_time_limit(300);

define( 'WP_ALLOW_REPAIR', true );
define( 'WP_POST_REVISIONS', 2 );
define( 'EMPTY_TRASH_DAYS', 5 ); // 5 days

/* That's all, stop editing! Happy blogging. */
#$_SERVER['HTTPS'] = 'on';

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
