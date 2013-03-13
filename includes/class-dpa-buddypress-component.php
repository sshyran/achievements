<?php
/**
 * Achievements' BuddyPress integration -- integrates into members and activity components.
 *
 * Achievements and BuddyPress are designed to connect together seamlessly and this makes that happen.
 * Everything in this file requires BuddyPress.
 *
 * @package Achievements
 * @subpackage BuddyPressClasses
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'DPA_BuddyPress_Component' ) ) :
/**
 * Loads achievements component for BuddyPress
 *
 * This class requires BuddyPress.
 *
 * @since Achievements (3.2)
 */
class DPA_BuddyPress_Component extends BP_Component {

	/**
	 * Start the achievements component creation process
	 *
	 * @since Achievements (3.2)
	 */
	public function __construct() {
		parent::start(
			'achievements',
			__( 'Achievements', 'dpa' ),
			BP_PLUGIN_DIR
		);

		// If BP's activity componet is active, we should integrate with it.
//		if ( bp_is_active( 'activity' ) )
	//		$this->activity();
	}

	/**
	 * Set up the component's global variables
	 *
	 * @param string|array $args
	 * @since Achievements (3.2)
	 */
	public function setup_globals( $args = '' ) {
		parent::setup_globals( array(
			'has_directory' => true,
			'root_slug'     => dpa_get_achievement_slug(),
			'slug'          => dpa_get_achievement_slug(),
		) );
	}

	/**
	 * Set up the component's URLs in the BP navigation object and the BuddyBar.
	 * 
	 * This does not add any items to the WP Toolbar.
	 *
	 * @param string|array $main_nav
	 * @param string|array $sub_nav
	 * @since Achievements (3.2)
	 */
	public function setup_nav( $main_nav = '', $sub_nav = '' ) {

		// Stop if there is no user displayed or logged in
		if ( ! is_user_logged_in() && ! bp_displayed_user_id() )
			return;

		// Add to the main navigation
		$main_nav = array(
			'default_subnav_slug' => 'all',
			'item_css_id'         => $this->id,
			'name'                => __( 'Achievements', 'dpa' ),
			'position'            => 100,
			'screen_function'     => 'dpa_bp_members_my_achievements',
			'slug'                => $this->slug,
		);

		// Determine user to use for the link
		if ( bp_displayed_user_id() )
			$user_domain = bp_displayed_user_domain();
		elseif ( bp_loggedin_user_domain() )
			$user_domain = bp_loggedin_user_domain();
		else
			return;

		// Add to the user navigation -- "my achievements"
		$sub_nav = array(
			'item_css_id'     => "{$this->id}-all",
			'name'            => __( 'My Achievements', 'dpa' ),
			'parent_slug'     => $this->slug,
			'parent_url'      => trailingslashit( $user_domain . $this->slug ),
			'position'        => 20,
			'screen_function' => 'dpa_bp_members_my_achievements',
			'slug'            => 'all',
		);

		parent::setup_nav( $main_nav, $sub_nav );
	}
}
endif;