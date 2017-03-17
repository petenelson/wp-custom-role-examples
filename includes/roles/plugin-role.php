<?php
/**
 * Sample code for a new Plugin role.
 *
 * @package custom-role-examples
 */

namespace Custom_Role_Examples\Roles\Plugin_Role;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hooks for the WP plugins_loaded action.
	add_action( 'wp_roles_init',      __NAMESPACE__ . '\add_plugin_role' );
}

/**
 * Creates a new role for a plugin on-the-fly, but does not store it
 * in the database.
 *
 * @param WP_Roles $wp_roles The roles object.
 */
function add_plugin_role( $wp_roles ) {

	// Define a new role.
	$role = 'my-plugin-role';
	$display_name = 'Custom Plugin Role';

	// Set up some capabilities.
	$caps = array(
		'read'            => true,
		'edit_pages'      => true,
		'edit_posts'      => true,
		'upload_files'    => true,
		);

	// Allow other code to override the caps for the role.
	$caps = apply_filters( 'my-plugin-role-caps', $caps );

	// Add the role to the global roles object.
	$wp_roles->roles[ $role ] = array( 'name' => $display_name, 'capabilities' => $caps );
	$wp_roles->role_objects[ $role ] = new \WP_Role( $role, $caps );
	$wp_roles->role_names[ $role ] = $display_name;
}
