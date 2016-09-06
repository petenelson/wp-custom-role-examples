<?php

namespace Custom_Role_Examples\Roles\Administrator;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hook for the WP admin_init action
	add_action( 'admin_init',      __NAMESPACE__ . '\update_admin_role' );

	// Hooks for map_meta_cap filter
	// add_filter( 'map_meta_cap',    __NAMESPACE__ . '\disallow_home_page_edit', 10, 4 );

}

/**
 * Returns the name of this role
 *
 * @return string
 */
function get_role_name() {
	return 'administrator';
}

/**
 * Updates the administrator role with additional capabilities.
 *
 * @return void
 */
function update_admin_role() {
	// Add our custom capability to the admin.
	$role = get_role( get_role_name() );

	if ( ! empty( $role ) && empty( $role->capabilities['cre_flush_roles'] ) ) {
		$role->add_cap( 'cre_flush_roles' );
	}
}

/**
 * Removes access to edit the home page from Administrators.
 *
 * @param array  $required_caps The user's actual capabilities (primitives).
 * @param string $cap           Capability name (usually meta)
 * @param int    $user_id       The user ID.
 * @param array  $args          Adds the context to the cap. Typically the object ID.
 * @return array                Returns a list of the caps a user needs in
 *                              order to perform the action.
 */
function disallow_home_page_edit( $caps, $cap, $user_id, $args ) {
	if ( \Custom_Role_Examples\user_has_role( $user_id, get_role_name() ) ) {

		if ( isset( $args[0] ) ) {
			$post = get_post( $args[0] );
		}

		// If the admin is trying to edit the Home Page, don't allow it.
		if ( 'edit_post' === $cap && ! empty( $post ) && 'home-page' === $post->post_name ) {
			return array( 'do_not_allow' );
		}

	}

	return $caps;
}
