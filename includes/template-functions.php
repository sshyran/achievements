<?php
/**
 * Achievements template functions
 *
 * This file contains functions necessary to mirror the WordPress core template
 * loading process. Many of those functions are not filterable, and even then
 * would not be robust enough to predict where Achievements templates might exist.
 *
 * @package Achievements
 * @subpackage TemplateFunctions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds Achievements theme support to any active WordPress theme
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name Optional. The name of the specialised template.
 * @since 3.0
 */
function dpa_get_template_part( $slug, $name = null ) {
	// Execute code for this part
	do_action( 'get_template_part_' . $slug, $slug, $name );

	// Setup possible parts
	$templates = array();
	if ( isset( $name ) )
		$templates[] = $slug . '-' . $name . '.php';

	$templates[] = $slug . '.php';

	// Allow template parts to be filtered
	$templates = apply_filters( 'dpa_get_template_part', $templates, $slug, $name );

	// Return the part that is found
	return dpa_locate_template( $templates, true, false );
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the child theme before the parent theme so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the theme-compat folder last.
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load Optional. If true the template file will be loaded if it is found.
 * @param bool $require_once Optional. Whether to require_once or require. Default true. Has no effect if $load is false.
 * @return string The template filename if one is located.
 * @since 3.0
 */
function dpa_locate_template( $template_names, $load = false, $require_once = true ) {
	$child_theme    = get_stylesheet_directory(); 
	$located        = false;
	$parent_theme   = get_template_directory(); 
	$fallback_theme = dpa_get_theme_compat_dir(); 

	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {

		// Skip to the next template if this one is empty
		if ( empty( $template_name ) )
			continue;

		// Trim off any slashes from the template name
		$template_name = ltrim( $template_name, '/' );

		// Check child theme first
		if ( file_exists( trailingslashit( $child_theme ) . $template_name ) ) {
			$located = trailingslashit( $child_theme ) . $template_name;
			break;

		// Check parent theme next
		} elseif ( file_exists( trailingslashit( $parent_theme ) . $template_name ) ) {
			$located = trailingslashit( $parent_theme ) . $template_name;
			break;

		// Check theme compatibility last
		} elseif ( file_exists( trailingslashit( $fallback_theme ) . $template_name ) ) {
			$located = trailingslashit( $fallback_theme ) . $template_name;
			break;

		// 3rd-party plugins can use this to load custom templates for their component if desired
		} else {
			$fallback_template = apply_filters( 'dpa_locate_fallback_template', trailingslashit( $fallback_theme ) . $template_name, $template_name, $template_names, $load, $require_once );

			if ( file_exists( $fallback_template ) ) {
				$located = $fallback_template;
				break;
			}
		}
	}

	// Check function arguments to see if we need to $load this template
	if ( ( true == $load ) && ! empty( $located ) )
		load_template( $located, $require_once );

	return $located;
}

/**
 * Retrieve path to a template
 *
 * Used to quickly retrieve the path of a template without including the file
 * extension. It will also check the parent theme and theme-compat theme with
 * the use of {@link dpa_locate_template()}. Allows for more generic template
 * locations without the use of the other get_*_template() functions.
 *
 * @param string $type Filename without extension.
 * @param array $templates An optional list of template candidates
 * @return string Full path to file.
 * @since 3.0
 */
function dpa_get_query_template( $type, $templates = array() ) {
	// Only allow a-z0-9 characters in file names
	$type = preg_replace( '|[^a-z0-9-]+|', '', $type );

	if ( empty( $templates ) )
		$templates = array( "{$type}.php" );

	/**
	 * Filter possible templates, try to match one, and set any Achievements theme
	 * compat properties so they can be cross-checked later.
	 */
	$templates = apply_filters( "dpa_get_{$type}_template", $templates );
	$templates = dpa_set_theme_compat_templates( $templates );
	$template  = dpa_locate_template( $templates );
	$template  = dpa_set_theme_compat_template( $template );

	return apply_filters( "dpa_{$type}_template", $template );
}

/**
 * Get the possible subdirectories to check for templates in
 *
 * @param array $templates Optional. Templates we are looking for
 * @return array Possible subfolders to look in
 * @since 3.0
 */
function dpa_get_template_locations( $templates = array() ) {
	$locations = array(
		'achievements',
		''
	);

	return apply_filters( 'dpa_get_template_locations', $locations, $templates );
}

/**
 * Add template locations to template files being searched for
 *
 * @param array Optionall. $templates
 * @return array() 
 * @since 3.0
 */
function dpa_add_template_locations( $templates = array() ) {
	$retval = array();

	// Get alternate locations
	$locations = dpa_get_template_locations( $templates );

	// Loop through locations and templates and combine
	foreach ( $locations as $location )
		foreach ( $templates as $template )
			$retval[] = trailingslashit( $location ) . $template;

	return apply_filters( 'dpa_add_template_locations', $retval, $templates );
}

/**
 * Add checks for Achievements conditions to parse_query action
 *
 * @param WP_Query $posts_query
 * @since 3.0
 */
function dpa_parse_query( $posts_query ) {
	// Bail if $posts_query is not the main loop
	if ( ! $posts_query->is_main_query() )
		return;

	// Bail if filters are suppressed on this query
	if ( true == $posts_query->get( 'suppress_filters' ) )
		return;

	// Bail if in admin
	if ( is_admin() )
		return;

	// Are we on an author archive page?
	if ( $posts_query->is_author() ) {

		// Set the displayed user global to this user
		achievements()->displayed_user = get_queried_object();
	}
}