<?php
namespace Custom_Role_Examples\Post_Types\Book;

function setup() {
	add_action( 'init', __NAMESPACE__ . '\register_book_cpt' );
}

function register_book_cpt() {

	$labels = array(
		'name'               => esc_html__( 'Books', 'custom-role-examples' ),
		'singular_name'      => esc_html__( 'Book', 'custom-role-examples' ),
		'add_new'            => esc_html__( 'Add New Book', 'custom-role-examples' ),
		'add_new_item'       => esc_html__( 'Add New Book', 'custom-role-examples' ),
		'new_item'           => esc_html__( 'New Book', 'custom-role-examples' ),
		'edit_item'          => esc_html__( 'Edit Book', 'custom-role-examples' ),
		'view_item'          => esc_html__( 'View Book', 'custom-role-examples' ),
		'all_items'          => esc_html__( 'All Books', 'custom-role-examples' ),
		'search_items'       => esc_html__( 'Search Books', 'custom-role-examples' ),
		'not_found'          => esc_html__( 'No Books found', 'custom-role-examples' ),
		'not_found_in_trash' => esc_html__( 'No Books found in Trash', 'custom-role-examples' ),
	);

	$args = array(
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'labels'             => $labels,
		'has_archive'        => false,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'            => array( 'slug' => 'book' ),
		'menu_icon'          => 'dashicons-book-alt',
		'capability_type'    => array( 'book', 'books' ),
		'map_meta_cap'       => true,
	);

	register_post_type( 'book', $args );
}