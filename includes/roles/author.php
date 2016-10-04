<?php

namespace Custom_Role_Examples\Roles\Author;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hooks for user_has_cap filter
	add_filter( 'user_has_cap',        __NAMESPACE__ . '\add_additional_caps', 10, 4 );
}

/**
 * Returns the name of this role
 *
 * @return string
 */
function get_role_name() {
	return 'author';
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

	// Give the author role the abilty to edit pages.
	$allcaps['edit_pages']           = true;

	// And the the abilty to publish pages.
	// $allcaps['publish_pages']        = true;

	return $allcaps;
}
