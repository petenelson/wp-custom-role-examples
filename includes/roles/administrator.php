<?php

namespace Custom_Role_Examples\Roles\Administrator;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hooks for map_meta_cap filter
	// add_filter( 'map_meta_cap',    __NAMESPACE__ . '\disallow_tos_page_edit', 10, 4 );

	// Hooks for user_has_cap filter
	// add_filter( 'user_has_cap',        __NAMESPACE__ . '\add_additional_caps', 10, 4 );
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

	// Capabilities for the book CPT.
	$allcaps['edit_books']             = true;
	$allcaps['edit_othgers_books']     = true;
	$allcaps['publish_books']          = true;
	$allcaps['edit_published_books']   = true;
	$allcaps['delete_books']           = true;
	$allcaps['delete_published_books'] = true;

	// Give the limited role some extra capabilities dynamically.
	$allcaps['cre_flush_roles']        = true;

	return $allcaps;
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
function disallow_tos_page_edit( $caps, $cap, $user_id, $args ) {
	if ( \Custom_Role_Examples\user_has_role( $user_id, get_role_name() ) ) {

		$cap_needed = array(
			'edit_post',
			// 'delete_post',
			);

		if ( isset( $args[0] ) ) {
			$post = get_post( $args[0] );
		}

		// If the admin is trying to edit the Terms of Service page, don't allow it.
		if ( in_array( $cap, $cap_needed ) && ! empty( $post ) && 'terms-of-service' === $post->post_name ) {
			return array( 'do_not_allow' );
		}

	}

	return $caps;
}
