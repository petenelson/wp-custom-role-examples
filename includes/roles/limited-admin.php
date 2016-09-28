<?php
/**
 * Sample code for a new Limited Admin role.
 *
 * @package custom-role-examples
 */

namespace Custom_Role_Examples\Roles\Limited_Admin;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hooks for the WP plugins_loaded action.
	add_action( 'plugins_loaded',      __NAMESPACE__ . '\add_limited_admin_role' );
}

/**
 * Returns the name of this role
 *
 * @return string
 */
function get_role_name() {
	return 'limited-admin';
}

/**
 * Adds the HR role to the site if it does not exist.
 *
 * @return void
 */
function add_limited_admin_role() {

	// Get the Limited Admin role.
	$role = get_role( get_role_name() );

	// Create the role if it does not exist.
	if ( empty( $role ) ) {

		// Get the admin role.
		$admin = get_role( 'administrator' );

		// Remove the ability to manage users.
		$capabilities = $admin->capabilities;
		$capabilities['edit_users']    = false;
		$capabilities['create_users']  = false;
		$capabilities['delete_users']  = false;
		$capabilities['list_users']    = false;
		$capabilities['remove_users']  = false;
		$capabilities['add_users']     = false;
		$capabilities['promote_users'] = false;

		// Remove the capabilities to manage plugins.
		$capabilities['update_plugins']  = false;
		$capabilities['delete_plugins']  = false;
		$capabilities['install_plugins'] = false;
		$capabilities['activate_plugins'] = false;

		add_role( get_role_name(), __( 'Limited Admin', 'custom-role-examples' ), $capabilities );
	}
}
