<?php

if ( ! class_exists( 'CRE_Limited_Role' ) ) {

	class CRE_Limited_Role extends CRE_Base_Role {

		public function __construct() {
			$this->role = 'limited';
			$this->display_name = 'Limited Access';
		}

		public function get_capabilities() {
			// Every user can read
			$caps = array( 'read' => true );

			$caps['edit_posts'] = true;

			return $caps;
		}

	}

}