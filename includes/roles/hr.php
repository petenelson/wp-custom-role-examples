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
	// add_action( 'admin_init',      __NAMESPACE__ . '\add_hr_role' );

	// Hooks for user_has_cap filter.
	// add_filter( 'user_has_cap',        __NAMESPACE__ . '\add_additional_caps', 10, 4 );

	// Hooks for map_meta_cap
	// add_filter( 'map_meta_cap',        __NAMESPACE__ . '\can_edit_current_openings_page', 20, 4 );

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

	// They can upload files pages.
	// $allcaps['upload_files']      = true;

	return $allcaps;
}

/**
 * Allow the HR role to edit the Current Openings page.
 *
 * @param array  $caps          The user's actual capabilities (primitives).
 * @param string $cap           Capability name (usually meta)
 * @param int    $user_id       The user ID.
 * @param array  $args          Adds the context to the cap. Typically the object ID.
 * @return array                Returns a list of the caps a user needs in
 *                              order to perform the action.
 */
function can_edit_current_openings_page( $caps, $cap, $user_id, $args ) {

	if ( ! \Custom_Role_Examples\user_has_role( $user_id, get_role_name() ) ) {
		return $caps;
	}

	$caps_to_check = array(
		'edit_post',
		// 'edit_published_pages',
		// 'edit_others_pages',
		// 'publish_pages',
		);

	// Only check for specific capalities that WP is trying.
	if ( ! in_array( $cap, $caps_to_check ) ) {
		return $caps;
	}

	// Try to get the post ID.
	if ( isset( $args[0] ) ) {
		$post = get_post( $args[0] );
	}

	// Not every capability check contains the post ID.
	// See https://core.trac.wordpress.org/ticket/36056 for more info.
	if ( empty( $post ) && isset( $_POST['post_ID'] ) ) {
		$post = get_post( absint( $_POST['post_ID'] ) );
	}

	// Is this the Current Openings page?
	if ( ! empty( $post ) && 'page' === $post->post_type && 'current-openings' === $post->post_name) {
		// Tell the user_has_cap filter that this user only needs the edit_pages capability
		// in order to edit or publish the Current Openins page.
		$caps = array( 'edit_pages' );
	}

	return $caps;
}

