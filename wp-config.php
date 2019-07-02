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
define( 'DB_NAME', 'proper' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         ',S?Z^R`8c|2=wYg{LA8]tBZy1qjX=f) XEE|9kCi6C#l6+.!V>sua#WLiOh7Y /K' );
define( 'SECURE_AUTH_KEY',  '<,V=2Wesh!%I];LXdDvkD/vze37XtzZlVib0,K2^d]~4v)GY[p]un*iRncr MX@}' );
define( 'LOGGED_IN_KEY',    '/pM}Kh![.EB2a:Yq6fH#NtZcwul)QOyQyFUAy&H@jL}2xi#5!@ RyyJIdKX5<-cq' );
define( 'NONCE_KEY',        '_g3N4f|]1HtQDho1~ 0X!FY*3g%R?]<c<wj%3b~C7}JMfXNvDy$!**a@8Chp@;S1' );
define( 'AUTH_SALT',        '>$CIsK/a9wPXSpKc=z%!z,gcS1W}+f]ha-X|!lMCj^TwV[5cx|K_:YRg@h:er7zK' );
define( 'SECURE_AUTH_SALT', 'tvWSv)6[TRH!X9-Z{esn9X]Sk}KG{HYpve^V|?K03GLto^Ouy5mp}PVn|8DTlhxk' );
define( 'LOGGED_IN_SALT',   'x3f6{vCn#%6{mC`B^-$g!&KKz98aL[dA@oIc%U~>,at>x_=|seH Sm76tZvX?bW|' );
define( 'NONCE_SALT',       'nmBGm[;kX`<p6 fge;q>-++FD..TdMO@yv$%:TNu,Zc4q3SVX?{#.jIRZ1&>]Dhr' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
