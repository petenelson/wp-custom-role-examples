<?php
/**
 * Sample code for a new Human Resources role.
 *
 * @package custom-role-examples
 */

namespace Custom_Role_Examples\Roles\HR;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hooks for the WP plugins_loaded action.
	add_action( 'admin_init',      __NAMESPACE__ . '\add_hr_role' );

	// Hooks for user_has_cap filter.
	 // add_filter( 'user_has_cap',        __NAMESPACE__ . '\add_additional_caps', 10, 4 );
	 // add_filter( 'user_has_cap',        __NAMESPACE__ . '\can_edit_current_openings_page', 20, 4 );

}

/**
 * Returns the name of this role
 *
 * @return string
 */
function get_role_name() {
	return 'hr';
}

/**
 * Adds the HR role to the site if it does not exist.
 *
 * @return void
 */
function add_hr_role() {

	// Get the HR role.
	$role = get_role( get_role_name() );

	// Create the role if it does not exist.
	if ( empty( $role ) ) {
		add_role( get_role_name(), __( 'Human Resources', 'custom-role-examples' ), get_role_capabilities() );
	}
}

/**
 * Gets the list of capabilities for this role.
 *
 * @return array
 */
function get_role_capabilities() {

	$caps = array(
		// Every user can read.
		'read' => true,
		);

	return $caps;
}


/**
 * Dynamically filter a user's capabilities.
 *
 * @param array   $allcaps An array of all the user's capabilities.
 * @param array   $caps    Actual capabilities for meta capability.
 * @param array   $args    Optional parameters passed to has_cap(), typically object ID.
 * @param WP_User $user    The user object.
 */
function add_additional_caps( $allcaps, $caps, $args, $user ) {

	if ( ! \Custom_Role_Examples\user_has_role( $user->ID, get_role_name() ) ) {
		return $allcaps;
	}

	// Give the HR role some extra capabilities dynamically.

	// They can list/edit pages.
	$allcaps['edit_pages']        = true;

	return $allcaps;
}

/**
 * Allow the Limited role to publish the About Us page.
 *
 * @param array   $allcaps An array of all the user's capabilities.
 * @param array   $caps    Actual capabilities for meta capability.
 * @param array   $args    Optional parameters passed to has_cap(), typically object ID.
 * @param WP_User $user    The user object.
 */
function can_edit_current_openings_page( $allcaps, $caps, $args, $user ) {

	if ( ! \Custom_Role_Examples\user_has_role( $user->ID, get_role_name() ) ) {
		return $allcaps;
	}

	/**
	 * args[0] is the cability being tested 
	 * args[1] is the user id
	 * args[2] is the object is, such as post ID
	 */

	$object_id = absint( isset( $args[2] ) ? $args[2] : 0 );

	$post = get_post( $object_id );
	if ( ! empty( $post ) && 'page' === $post->post_type ) {

		if ( 'current-openings' === $post->post_name ) {

			// Can edit Current Openings regardless of who owns it.
			$allcaps['edit_others_pages']    = true;

			// Can edit Current Openings if it's published.
			$allcaps['edit_published_pages'] = true;

			// Can publish Current Openings if it's not published.
			$allcaps['publish_pages']        = true;
		}

	}

	return $allcaps;
}

