<?php
/**
* Action and filters
*
* Contains the actions and filters that are used throughout Achievements.
* They are consolidated here to better help developers understand the order
* in which things occur.
*
* @package Achievements
* @subpackage Hooks
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Attach Achievements to WordPress
 *
 * Achievements uses its own internal actions to help aid in additional plugin
 * development, and to limit the amount of potential future code changes when
 * updates to WordPress occur.
 */
add_action( 'plugins_loaded',         'dpa_loaded',                 10 );
add_action( 'init',                   'dpa_init',                   10 );
add_action( 'widgets_init',           'dpa_widgets_init',           10 );
//add_action( 'generate_rewrite_rules', 'dpa_generate_rewrite_rules', 10 );
add_action( 'wp_enqueue_scripts',     'dpa_enqueue_scripts',        10 );
//add_action( 'template_redirect',      'dpa_template_redirect',      10 );
//add_filter( 'template_include',       'dpa_template_include',       10 );

/**
 * dpa_loaded - Attached to 'plugins_loaded' above
 *
 * Attach various loader actions to the dpa_loaded action.
 */
add_action( 'dpa_loaded', 'dpa_constants',          2  );
add_action( 'dpa_loaded', 'dpa_bootstrap_globals',  4  );
add_action( 'dpa_loaded', 'dpa_includes',           6  );
add_action( 'dpa_loaded', 'dpa_setup_globals',      8  );

/**
 * dpa_init - Attached to 'init' above
 *
 * Attach various initialisation actions to the init action.
 */
add_action( 'dpa_init', 'dpa_load_textdomain',        2   );
add_action( 'dpa_init', 'dpa_setup_option_filters',   4   );
add_action( 'dpa_init', 'dpa_setup_current_user',     6   );
//add_action( 'dpa_init', 'dpa_setup_theme_compat',     8   );
add_action( 'dpa_init', 'dpa_register_post_types',    10  );
//add_action( 'dpa_init', 'dpa_register_post_statuses', 12  );
add_action( 'dpa_init', 'dpa_register_taxonomies',    14  );
//add_action( 'dpa_init', 'dpa_register_views',         16  );
//add_action( 'dpa_init', 'dpa_add_rewrite_tags',       20  );
add_action( 'dpa_init', 'dpa_ready',                  999 );

// Caps & Roles
add_filter( 'map_meta_cap',     'dpa_map_meta_caps', 10, 4 );
add_action( 'dpa_activation',   'dpa_add_caps',      1     );
add_action( 'dpa_deactivation', 'dpa_remove_caps',   1     );

// Options & Settings
add_action( 'dpa_activation', 'dpa_add_options', 1 );

// Flush rewrite rules
add_action( 'dpa_activation',   'flush_rewrite_rules' );
add_action( 'dpa_deactivation', 'flush_rewrite_rules' );


// wp-admin stuff
if ( is_admin() ) {
	add_action( 'dpa_init',       'dpa_admin'           );

	/**
	 * Take bbPress' advice and run the updater later on 'dpa_admin_init'.
	 * This apparently ensures that all alterations to the permalink structure 
	 * have taken place. This fixed a bbPress issue where permalinks were not
	 * being flushed properly when an update occured.
	 */
	add_action( 'dpa_admin_init', 'dpa_setup_updater', 999 );
}


/**
 * The purpose of the following actions is to mimic the behaviour of something
 * called 'plugin dependency' which enables a plugin to have plugins of their
 * own in a safe and reliable way.
 *
 * This is done in BuddyPress and bbPress. We do this by mirroring existing
 * WordPress actions in many places allowing dependant plugins to hook into
 * the Achievements specific ones, thus guaranteeing proper code execution.
 */

/**
 * Activation Actions
 */

/**
 * Runs on plugin activation
 *
 * @since 3.0
 */
function dpa_activation() {
	do_action( 'dpa_activation' );
}

/**
 * Runs on plugin deactivation
 *
 * @since 3.0
 */
function dpa_deactivation() {
	do_action( 'dpa_deactivation' );
}

/**
 * Runs when uninstalling the plugin
 *
 * @since 3.0
 */
function dpa_uninstall() {
	do_action( 'dpa_uninstall' );
}


/**
 * Main Actions
 */

/**
 * Main action responsible for constants, globals, and includes
 *
 * @since 3.0
 */
function dpa_loaded() {
	do_action( 'dpa_loaded' );
}

/**
 * Set up constants
 *
 * @since 3.0
 */
function dpa_constants() {
	do_action( 'dpa_constants' );
}

/**
 * Set up globals BEFORE includes
 *
 * @since 3.0
 */
function dpa_bootstrap_globals() {
	do_action( 'dpa_bootstrap_globals' );
}

/**
 * Include files
 *
 * @since 3.0
 */
function dpa_includes() {
	do_action( 'dpa_includes' );
}

/**
 * Set up globals AFTER includes
 *
 * @since 3.0
 */
function dpa_setup_globals() {
	do_action( 'dpa_setup_globals' );
}

/**
 * Initialise any code after everything has been loaded
 *
 * @since 3.0
 */
function dpa_init() {
	do_action( 'dpa_init' );
}

/**
 * Initialise widgets
 *
 * @since 3.0
 */
function dpa_widgets_init() {
	do_action( 'dpa_widgets_init' );
}


/**
 * Supplemental Actions
 */

/**
 * Set up the currently logged-in user
 *
 * @since 3.0
 */
function dpa_setup_current_user() {
	do_action( 'dpa_setup_current_user' );
}

/**
 * Load translations for current language
 *
 * @since 3.0
 */
function dpa_load_textdomain() {
	do_action( 'dpa_load_textdomain' );
}

/**
 * Set up the post types
 *
 * @since 3.0
 */
function dpa_register_post_types() {
	do_action( 'dpa_register_post_types' );
}

/**
 * Register the built-in taxonomies
 *
 * @since 3.0
 */
function dpa_register_taxonomies() {
	do_action( 'dpa_register_taxonomies' );
}

/**
 * Enqueue CSS and JS
 *
 * @since 3.0
 */
function dpa_enqueue_scripts() {
	do_action( 'dpa_enqueue_scripts' );
}


/**
 * Everything's loaded and ready to go!
 *
 * @since 3.0
 */
function dpa_ready() {
	do_action( 'dpa_ready' );
}
?>