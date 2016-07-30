<?php
/**
 * Plugin Name: Custom Role Examples
 * Description: Sample code for custom roles and custom user capabilities
 * Version:     1.0.0
 * Author:      Pete Nelson pete@petenelson.com
 * Author URI:  https://petenelson.io
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/class-cre-base-role.php';
require_once __DIR__ . '/includes/class-cre-limited-role.php';
require_once __DIR__ . '/includes/class-cre-administrator-role.php';
require_once __DIR__ . '/includes/class-cre-dashboard-widget.php';


// http://isabelcastillo.com/list-capabilities-current-user-wordpress

function cre_get_custom_role_classes() {
	return array( 'CRE_Limited_Role', 'CRE_Administrator_Role' );
}

/**
 * Initialize custom roles
 *
 * @return void
 */
function cre_init_custom_roles() {
	$roles_instances = array();
	foreach( cre_get_custom_role_classes() as $class_name ) {
		$roles_instances[ $class_name ] = new $class_name;
		$roles_instances[ $class_name ]->init();
	}
}

add_action( 'admin_init', 'cre_init_custom_roles' );


/**
 * Rebuilds the custom roles in case there have been any updates.  Because
 * roles are stored in the database, we only want to remove and recreate
 * them when there are capabilitiy changes to the role.
 *
 * @return void
 */
function cre_maybe_flush_roles() {

	$roles_version = '2016-07-20-03';
	$flush_roles   = '1' === filter_input( INPUT_GET, 'cre-flush-roles', FILTER_SANITIZE_STRING );

	// Check if the roles version has changed
	if ( $roles_version !== get_option( 'cre-roles-versiom' ) || $flush_roles ) {

		cre_flush_roles();

		// Store the new roles version
		update_option( 'cre-roles-versiom', $roles_version );
	}

}

add_action( 'admin_init', 'cre_maybe_flush_roles' );

/**
 * Rebuilds the custom roles
 *
 * @return void
 */
function cre_flush_roles() {

	// Delete existing custom roles
	foreach( cre_get_custom_role_classes() as $class_name ) {
		$role_instance = new $class_name;
		remove_role( $role_instance->role );
	}

	// add our custom capability to the admin
	$administrator = get_role( 'administrator' );
	$administrator->add_cap( 'cre_flush_roles' );

	// Rerun the init function which will recreate the roles
	cre_init_custom_roles();
}

// Hook into our dashboard widget
add_action( 'plugins_loaded', 'CRE_Dashboard_Widget::plugins_loaded' );
