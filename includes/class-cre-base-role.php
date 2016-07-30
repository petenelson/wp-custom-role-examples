<?php

if ( ! class_exists( 'CRE_Base_Role' ) ) {

	abstract class CRE_Base_Role {

		/**
		 * The name of the role (slug)
		 * @var string
		 */
		public $role;

		/**
		 * The friendly display name of the role
		 * @var string
		 */
		public $display_name;

		/**
		 * Initializes the roll
		 *
		 * @return void
		 */
		public function init() {

			// Add this role if it does not exist.
			$role_object = get_role( $this->role );
			if ( empty( $role_object ) ) {

				add_role(
					$this->role,
					$this->display_name,
					$this->get_capabilities()
					);
			}

			if ( method_exists( $this, 'user_has_cap' ) ) {
				add_filter( 'user_has_cap', array( $this, 'user_has_cap' ), 10, 4 );
			}

			if ( method_exists( $this, 'map_meta_cap' ) ) {
				add_filter( 'map_meta_cap', array( $this, 'map_meta_cap' ), 10, 4 );
			}

		}

		/**
		 * Determines if the supplied user ID has the current role
		 *
		 * @param  int  $user_id
		 * @return boolean
		 */
		public function has_this_role( $user_id ) {
			$user = get_userdata( $user_id );
			return ! empty( $user ) && ! empty( $user->roles ) && in_array( $this->role, $user->roles );
		}

		/**
		 * Returns a list of capabilities that the role has.
		 *
		 * @return array
		 */
		abstract public function get_capabilities();

	}

}
