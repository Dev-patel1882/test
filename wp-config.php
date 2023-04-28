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
define( 'DB_NAME', 'wp_test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );


define( 'FS_METHOD', 'direct' );

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
define( 'AUTH_KEY',         'MHehnv6~r>M44`TOfM/i[a48cD_6X aHuL0Xha3cEBW@#u>qp-b9:yK.4#y;D< q' );
define( 'SECURE_AUTH_KEY',  '(wgk5i#sD?^0WdH/hSbYq>oZ*Hi>j$^8Rr!>a03P =pX@#%VK}KD_K~xM7/8.~AC' );
define( 'LOGGED_IN_KEY',    '}IR#V3<xA:W@cPBj11p]3A*3X+~(tn3[eJuYHVntP6b-M)nw@HT) LF/W!@;R5LL' );
define( 'NONCE_KEY',        'Q=,%HO S#BLuJaHFna3j4#D4zr9<MOEl0M>=j$SLV>m7ex7/ObY~gQ`yy}m/DQTs' );
define( 'AUTH_SALT',        '~Iwv,>5ExFh7E u6W5Dj<v&u!ab^2Mf4r|G^(&;!OOA;jj3#D<uSXMyu}I&0KC+V' );
define( 'SECURE_AUTH_SALT', 'VztGdTXk(@eJo-}3Ls>-Wbt}uFs!f=)K?fnWF`<*VDu>YWFSp#}_2]+B/?;fc^H7' );
define( 'LOGGED_IN_SALT',   '{F[{*KUr0Rvbf[5Ok3-Du#]YqIZ_/$4|5ksQ:{BYc*krCT!^4Psk)T08ylS/Jrf@' );
define( 'NONCE_SALT',       '^vQ!82:;2 t9biDjVw^+d;UJ+DWq%NfEZT*V_B,JZcX<|m|4&3OG|vs_&-G;)ETn' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

