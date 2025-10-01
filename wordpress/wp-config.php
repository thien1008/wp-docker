<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wordpress' );

/** MySQL hostname */
define( 'DB_HOST', 'db:3306' );   // ✅ dùng tên service trong docker-compose.yml


/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '%Z*9{ep/x4Hi,z{]JeojQ[&AH4_?vGxS= obH;#WNY%WFM(+#G?=5n P8e9_y]<;' );
define( 'SECURE_AUTH_KEY',  'USmiou8fErKykg11Cp$Y3bgbh*Wru2|GxKz&}ysERE~im<Xa*pTo]YSy?y>f?l8B' );
define( 'LOGGED_IN_KEY',    '[Mgjbt+U#QfegM%%4/d.Y4/kb7rAR_H%4ouvIl.4Dd-Y +mhKEeO7Is6-LXXTwv@' );
define( 'NONCE_KEY',        '+udK*9#@srL]l^7/m:H6n0O+.6/pqi@<C.!e4z@`RUb>@B8XzNaIVZOn(pR5~uf|' );
define( 'AUTH_SALT',        '_M==zHf}gN~b{%CGIX#$@`#BN!KHId!~9_bIfrZ,_-9!%?XV@fJ^5y_?xAz0JsU}' );
define( 'SECURE_AUTH_SALT', '_fQ2bVQy21EVk9]51+7Uev7rKmH*N?Ei6vp4.}vSX8N&[_8qAQ(=Dr*,[:Ow@(b>' );
define( 'LOGGED_IN_SALT',   'dRF/!o{eh^JBc-%N)(,<1./l47lbJM&PI;%Uo%QV3]A0w7^pw7}MqGCfYI9]/_0R' );
define( 'NONCE_SALT',       'HO4Ir[wf7p.AvDeXULo63U{J5c=,U?WE92x~!;g`L!2z*`;8Ux{TM3&E*o~&3MIN' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */


define('FS_METHOD', 'direct');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
