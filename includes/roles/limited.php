<?php

namespace Custom_Role_Examples\Roles\Limited;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hooks for the WP admin_init action
	add_action( 'admin_init',          __NAMESPACE__ . '\add_limited_role' );

	// Hooks for our custom action
	add_action( 'cre_flush_roles',     __NAMESPACE__ . '\recreate_limited_role' );

	// Hooks for map_meta_cap filter
	// add_filter( 'map_meta_cap',        __NAMESPACE__ . '\map_meta_cap', 10, 4 );

	// Hooks for user_has_cap filter
	add_filter( 'user_has_cap',        __NAMESPACE__ . '\add_additional_caps', 10, 4 );
	// add_filter( 'user_has_cap',        __NAMESPACE__ . '\can_publish_about_us_page', 10, 4 );

}

/**
 * Returns the name of this role
 *
 * @return string
 */
function get_role_name() {
	return 'limited';
}

/**
 * Adds the Limited role to the site if it does not exist.
 *
 * @return void
 */
function add_limited_role() {

	// Get the Limited role.
	$role = get_role( get_role_name() );

	// Create the role if it does not exist.
	if ( empty( $role ) ) {
		add_role( get_role_name(), __( 'Limited Role', 'custom-role-examples' ), get_role_capabilities() );
	}
}

/**
 * Gets the list of capabilities for this role.
 *
 * @return array
 */
function get_role_capabilities() {

	$caps = array(
		// Every user can read
		'read' => true,

		// can list and create new posts, but not publish them
		// 'edit_posts' => true,

		// can publish posts
		// 'publish_posts' => true,

		// can edit their published posts
		// 'edit_published_posts' => true,

		// can list and create new pages, but not publish them
		// 'edit_pages' => true,

		// can edit any non-published page not owned by them
		// 'edit_others_pages' => true,

		// can edit any published page they own
		// 'edit_published_pages' => true,

		// custom flush roles capability, handy for the demo
		'cre_flush_roles' => true

		);

	return $caps;
}

/**
 * Removed and recreates the Limited role.
 *
 * @return void
 */
function recreate_limited_role() {

	// Get the Limited role.
	$role = get_role( get_role_name() );

	// Remove and recreate the role.
	if ( ! empty( $role ) ) {
		remove_role( get_role_name() );
		add_limited_role();
	}
}

/**
 * Maps a meta capability (such as edit_post) to a primitive
 * capability (such as edit_pages)
 *
 * @param array  $required_caps The user's actual capabilities (primitives).
 * @param string $cap           Capability name (usually meta)
 * @param int    $user_id       The user ID.
 * @param array  $args          Adds the context to the cap. Typically the object ID.
 * @return array                Returns a list of the caps a user needs in
 *                              order to perform the action.
 */
function map_meta_cap( $required_caps, $cap, $user_id, $args ) {

	// Only continue if we're working with the Limited role.
	if ( ! \Custom_Role_Examples\user_has_role( $user_id, get_role_name() ) ) {
		return $required_caps;
	}

	$caps_needed = array(
		// 'edit_post',
		// 'edit_others_pages',
		);

	if ( ! in_array( $cap, $caps_needed ) ) {
		return $required_caps;
	}

	// Post ID is usually passed as part of $args, but not always (such as edit_others_pages).
	if ( ! isset( $args[0] ) && isset( $_POST['post_ID'] ) ) {
		$post_id = absint( $_POST['post_ID'] );
	} else if ( isset( $args[0] ) ) {
		$post_id = absint( $args[0] );
	}

	if ( ! empty( $post_id ) ) {
		$post = get_post( $post_id );

		// Check if they're editing the About Us page.
		if ( ! empty( $post ) && 'page' === $post->post_type && 'about-us' === $post->post_name) {

			// This tells WP what capabilities the user needs assigned in order to edit this page.
			// Essentially we're saying: "This user needs the edit_pages primitive capability to edit
			// this specific page."  These are passed to the user_has_cap filter in the $caps param.
			$required_caps = array( 'edit_pages' );

		}
	}

	return $required_caps;
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

	// Give the limited role some extra capabilities dynamically.
	// $allcaps['edit_pages']        = true;
	// $allcaps['edit_others_pages'] = true;

	// Capabilities for the book CPT.
	$allcaps['edit_books']             = true;
	// $allcaps['edit_othgers_books']     = true;
	// $allcaps['publish_books']          = true;
	// $allcaps['edit_published_books']   = true;
	// $allcaps['delete_books']           = true;
	// $allcaps['delete_published_books'] = true;

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
function can_publish_about_us_page( $allcaps, $caps, $args, $user ) {

	if ( ! \Custom_Role_Examples\user_has_role( $user->ID, get_role_name() ) ) {
		return $allcaps;
	}

	// args[0] is the cability being tested
	// args[1] is the user id
	// args[2] is the object is, such as post ID

	$object_id = absint( isset( $args[2] ) ? $args[2] : 0 );

	if ( empty( $object_id ) && isset( $_POST['post_ID'] ) ) {
		$object_id = absint( $_POST['post_ID'] );
	}

	$post = get_post( $object_id );
	if ( ! empty( $post ) && 'page' === $post->post_type ) {

		// Give the user the capability to publish the About Us page
		if ( 'about-us' === $post->post_name ) {
			$allcaps['publish_pages'] = true;
		}

	}

	return $allcaps;
}

