<?php
/**
 * Sample code for Uh Oh demo.
 *
 * @package custom-role-examples
 */

namespace Custom_Role_Examples\Roles\Uh_Oh;


function setup() {
	// add_filter( 'user_has_cap', __NAMESPACE__ . '\grant_any_cap' );
	// add_filter( 'map_meta_cap', __NAMESPACE__ . '\set_any_cap' );
}

/**
 * Returns the cap needed to do whatever you want.
 */
function get_cap() {
	return 'I do what I want!';
}

/**
 * Grant the cap to the user.
 */
function grant_any_cap() {
	return array(
		get_cap() => true,
		);
}

/**
 * Set the cap needed to get_cap()
 */
function set_any_cap() {
	return array( get_cap() );
}
