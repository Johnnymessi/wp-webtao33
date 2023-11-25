<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'webtao33');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', '');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'bU<XqVu,^Uex;9tGWW2f>B h1 *KU%)!W6wN|a3$zdr >vEKL-xzKn jD5UUH/`5');
define('SECURE_AUTH_KEY',  '8JJ`AMMv93mvJuxRAeExVBT;!@Dm`W/f,:j! y:,W]_=g9d1aq)2j~7ZbTzCrQO4');
define('LOGGED_IN_KEY',    'Ni>0$nt|8-/xS#t0@t3sY;/?iZT;K~b>*nE P)a4cPx}S&h8&Lmu]=*eX &rA??M');
define('NONCE_KEY',        'pdu>edj(+xY`?^xrTrmD|js:+59[(um*}->Or7Z:<33(GSZ87gGA(Mlj47U*;tc`');
define('AUTH_SALT',        'LtzBc;mYKYD?{s QVXBxTmtzF(4^yaW]!gjAzD$Dori_)tz`s0h/D(p1ya)/8lLa');
define('SECURE_AUTH_SALT', 'VPa:Vc:t),<UC,/5=ow|nJ#QxJ^Qf$AW;KG~ikZUL<2ktQ#:e%pL&@xi=!L@:=.I');
define('LOGGED_IN_SALT',   'FTVhhGT>Rv`D~is[_iuOJ709totMIQQ-9RL5z(Zuc18Da3U4,&xWUYg9Q^Sp_[lU');
define('NONCE_SALT',       '!kP,FRHRIGRo=.R#q*^-RlTjmhI?8%1H.,;7Q=6nbCOYM9Bu|,28LmVsR1X!Hw1-');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_admin';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
