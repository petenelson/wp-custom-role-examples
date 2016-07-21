<?php

if ( ! class_exists( 'CRE_Dashboard_Widget' ) ) {

	class CRE_Dashboard_Widget {

		static public function plugins_loaded() {
			add_action( 'wp_dashboard_setup', 'CRE_Dashboard_Widget::add_dashboard_widget' );
			add_action( 'admin_init', 'CRE_Dashboard_Widget::check_flush_roles' );
			add_action( 'admin_notices', 'CRE_Dashboard_Widget::roles_flushed_notice' );
		}

		static public function add_dashboard_widget() {
			wp_add_dashboard_widget(
				'cre_dashboard_widget',
				'Current Role',
				'CRE_Dashboard_Widget::dashboard_widget'
				);
		}


		static public function dashboard_widget() {

			$flush_roles_url = add_query_arg(
				'cre-flush-roles-nonce',
				rawurlencode( wp_create_nonce( 'cre-flush-roles' ) ),
				admin_url( '/' )
				);

			$user = wp_get_current_user();
			$role = reset( $user->roles );
			$role = get_role( $role );

			?>

				<h3>Role: <?php echo esc_html( $role->name ); ?></h3>

				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th>Capability</th>
							<th>Enabled</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $role->capabilities as $cap => $enabled ) : ?>
							<tr>
								<td><?php echo esc_html( $cap ); ?></td>
								<td><?php echo esc_html( $enabled ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<p>
					<a href="<?php echo esc_url( $flush_roles_url ); ?>">Flush Roles</a>
				</p>

			<?php


		}

		static public function check_flush_roles() {
			$nonce = filter_input( INPUT_GET, 'cre-flush-roles-nonce', FILTER_SANITIZE_STRING );
			if ( wp_verify_nonce( $nonce, 'cre-flush-roles' ) ) {
				cre_flush_roles();
				wp_safe_redirect( add_query_arg( 'cre-roles-flushed', '1', admin_url( '/' ) ) );
				exit;
			}
		}

		static public function roles_flushed_notice() {
			$flushed = '1' === filter_input( INPUT_GET, 'cre-roles-flushed', FILTER_SANITIZE_STRING );
			if ( $flushed ) {
				?>
					<div class="notice notice-success">
						<p>Roles Flushed</p>
					</div>
				<?php
			}
		}

	}

}
