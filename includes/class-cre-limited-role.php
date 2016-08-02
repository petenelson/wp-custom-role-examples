<?php

if ( ! class_exists( 'CRE_Limited_Role' ) ) {

	class CRE_Limited_Role extends CRE_Base_Role {

		public function __construct() {
			$this->role = 'limited';
			$this->display_name = 'Limited Access';
			$this->custom = true;
		}

		/**
		 * Get a list of capabilities for this role
		 *
		 * @return array
		 */
		public function get_capabilities() {
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
		public function map_meta_cap( $required_caps, $cap, $user_id, $args ) {

			if ( ! $this->has_this_role( $user_id ) ) {
				return $required_caps;
			}

			$caps_needed = array(
				// 'edit_post',
				// 'edit_others_pages',
				// 'delete_post',
				);

			if ( ! in_array( $cap, $caps_needed ) ) {
				return $required_caps;
			}


			// Post ID is usually passed as part of $args, but not always
			if ( ! isset( $args[0] ) && isset( $_POST['post_ID'] ) ) {
				$post_id = absint( $_POST['post_ID'] );
			} else if ( isset( $args[0] ) ) {
				$post_id = absint( $args[0] );
			}

			if ( ! empty( $post_id ) ) {
				$post = get_post( $post_id );

				// Check if they're editing the About Us page
				if ( ! empty( $post ) && 'page' === $post->post_type && 'about-us' === $post->post_name) {

					// This tells WP what capabilities the user needs assigned in order to edit this page.
					// Essentially we're saying: "This user needs the edit_pages primitive capability to edit
					// this specific page."  These are passed to the user_has_cap filter in the $caps param.
					$required_caps = array( 'edit_pages' );

					// or you can also do this
					// return array();

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
		public function user_has_cap( $allcaps, $caps, $args, $user ) {
			if ( ! $this->has_this_role( $user->ID ) ) {
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
					// $allcaps['publish_pages'] = true;
				}

			}

			return $allcaps;
		}

	}

}