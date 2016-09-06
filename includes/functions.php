<?php
namespace Custom_Role_Examples;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {
	add_action( 'admin_init', __NAMESPACE__ . '\maybe_flush_roles' );
}


/**
 * Rebuilds the custom roles in case there have been any updates.  Because
 * roles are stored in the database, we only want to remove and recreate
 * them when there are capabilitiy changes to the role.
 *
 * @return void
 */
function maybe_flush_roles() {

	$roles_version = '2016-07-20-03';

	// Check if the roles version has changed
	if ( $roles_version !== get_option( 'cre_roles_version' ) ) {

		// Hook to allow our custom roles to rebuild themselves
		do_action( 'cre_flush_roles' );

		// Store the new roles version
		update_option( 'cre_roles_version', $roles_version );
	}

}

/**
 * Determines if the supplied user ID has the supplied role.
 *
 * @param  int    $user_id The user ID.
 * @param  string $role    The role name.
 * @return boolean
 */
function user_has_role( $user_id, $role ) {
	$user = get_userdata( $user_id );
	return ! empty( $user ) && ! empty( $user->roles ) && in_array( $role, $user->roles );
}
