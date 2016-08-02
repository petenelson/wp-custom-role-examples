<?php
if ( ! class_exists( 'CRE_Administrator_Role' ) ) {

	class CRE_Administrator_Role extends CRE_Base_Role {

		public function __construct() {
			$this->role = 'administrator';
			$this->display_name = 'Administrator';
			$this->custom = false;
		}

		public function get_capabilities() {
			get_role( 'administrator' )->capabilities;
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