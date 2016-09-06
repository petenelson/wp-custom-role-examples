<?php

namespace Custom_Role_Examples\Dashboard_Widget;

function setup() {

	add_action( 'wp_dashboard_setup',   __NAMESPACE__ . '\add_dashboard_widget' );
	add_action( 'admin_init',           __NAMESPACE__ . '\check_flush_roles' );
	add_action( 'admin_notices',        __NAMESPACE__ . '\roles_flushed_notice' );

}

function add_dashboard_widget() {
	wp_add_dashboard_widget(
		'cre_dashboard_widget',
		'Current Role',
		__NAMESPACE__ . '\dashboard_widget'
		);
}

function dashboard_widget() {

	$flush_roles_url = add_query_arg(
		'cre-flush-roles-nonce',
		rawurlencode( wp_create_nonce( 'cre-flush-roles' ) ),
		admin_url( '/' )
		);

	$user = wp_get_current_user();
	$role = reset( $user->roles );
	$role = get_role( $role );

	$current_user_can_examples = array(
		'edit_posts',
		'edit_pages',
		'edit_others_pages',
		'edit_published_pages',
		'publish_pages',
		'edit_books',
		);

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
						<td><?php echo esc_html( '1' == $enabled ? 'Yes' : 'No' ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<p></p>

		<table class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th>current_user_can()</th>
					<th>Y/N</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $current_user_can_examples as $cap ) : ?>
					<tr>
						<td><?php echo esc_html( $cap ); ?></td>
						<td><?php echo esc_html( current_user_can( $cap ) ? 'Yes' : 'No' ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>


		<?php if ( current_user_can( 'cre_flush_roles' ) ) : ?>
			<p>
				<a href="<?php echo esc_url( $flush_roles_url ); ?>">Flush Roles</a>
			</p>
		<?php endif; ?>

	<?php
}

function check_flush_roles() {
	$nonce = filter_input( INPUT_GET, 'cre-flush-roles-nonce', FILTER_SANITIZE_STRING );
	if ( current_user_can( 'cre_flush_roles' ) && wp_verify_nonce( $nonce, 'cre-flush-roles' ) ) {

		// Hook to allow our custom roles to rebuild themselves
		do_action( 'cre_flush_roles' );

		wp_safe_redirect( add_query_arg( 'cre-roles-flushed', '1', admin_url( '/' ) ) );
		exit;
	}
}

function roles_flushed_notice() {
	$flushed = '1' === filter_input( INPUT_GET, 'cre-roles-flushed', FILTER_SANITIZE_STRING );
	if ( $flushed ) {
		?>
			<div class="notice notice-success">
				<p>Roles Flushed</p>
			</div>
		<?php
	}
}
