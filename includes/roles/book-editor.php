<?php
/**
 * Sample code for a new Book Editor role.
 *
 * @package custom-role-examples
 */

namespace Custom_Role_Examples\Roles\Book_Editor;

/**
 * Sets up hooks and filters
 *
 * @return void
 */
function setup() {

	// Hooks for the WP admin_init action.
	add_action( 'admin_init',          __NAMESPACE__ . '\add_book_editor_role' );
}

/**
 * Returns the name of this role
 *
 * @return string
 */
function get_role_name() {
	return 'book-editor';
}

/**
 * Adds the Book Editor role to the site if it does not exist.
 *
 * @return void
 */
function add_book_editor_role() {

	// Get the Books Editor role.
	$role = get_role( get_role_name() );

	// Create the role if it does not exist.
	if ( empty( $role ) ) {
		add_role( get_role_name(), __( 'Book Editor', 'custom-role-examples' ), get_role_capabilities() );
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

		// Can list and create new books, but not publish them.
		'edit_books' => true,

		// Can list and create other user's books.
		'edit_others_books' => true,

		// Can publish books.
		'publish_books' => true,

		// Can edit their published books.
		'edit_published_books' => true,

		// Can delete non-published book owned by them.
		'delete_books' => true,

		// Can delete books owned by other users.
		'delete_others_books' => true,

		// Can delete their own published books.
		'delete_published_books' => true,

		// Can uploads attachments, images, etc.
		'upload_files' => true,

	);

	return $caps;
}
