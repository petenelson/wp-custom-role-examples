<?php
if ( ! class_exists( 'CRE_Administrator_Role' ) ) {

	class CRE_Administrator_Role extends CRE_Base_Role {

		public function __construct() {
			$this->role = 'administrator';
			$this->display_name = 'Administrator';
		}

		public function get_capabilities() {
			get_role( 'administrator' )->capabilities;
		}

		public function map_meta_cap( $caps, $cap, $user_id, $args ) {
			if ( $this->has_this_role( $user_id ) ) {

				if ( isset( $args[0] ) ) {
					$post = get_post( $args[0] );
				}

				// if ( 'edit_post' === $cap && ! empty( $post ) && 'home-page' === $post->post_name ) {
				// 	return array( 'do_not_allow' );
				// }

			}

			return $caps;
		}


	}
}