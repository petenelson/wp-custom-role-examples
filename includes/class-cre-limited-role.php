<?php

if ( ! class_exists( 'CRE_Limited_Role' ) ) {

	class CRE_Limited_Role extends CRE_Base_Role {

		public function __construct() {
			$this->role = 'limited';
			$this->display_name = 'Limited Access';
		}

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

				// custom flush roles capability, handy for the demo
				// 'cre_flush_roles' => true

				);

			return $caps;
		}

		public function user_has_cap( $allcaps, $caps, $args, $user ) {
			
			if ( $this->has_this_role( $user->ID ) ) {

				// args[0] is the cability being tested
				// args[1] is the user id
				// args[2] is the object is, such as post ID

				$object_id = absint( isset( $args[2] ) ? $args[2] : 0 );

				if ( empty( $object_id ) && isset( $_POST['post_ID'] ) ) {
					$object_id = absint( $_POST['post_ID'] );
				}

				$post = get_post( $object_id );
				if ( ! empty( $post ) && 'page' === $post->post_type ) {

					// Give the user the capability to edit the About Us page, regardless of status or ownership
					if ( 'about-us' === $post->post_name ) {
						$allcaps['edit_published_pages'] = true;
						$allcaps['edit_others_pages'] = true;
					}

					// If the user owns the published page, allow them to edit it
					if ( absint( $user->ID ) === absint( $post->post_author ) && 'publish' === $post->post_status ) {
						$allcaps['edit_published_pages'] = true;
					}

				}

			}

			return $allcaps;
		}

	}

}