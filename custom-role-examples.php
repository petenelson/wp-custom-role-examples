<?php
/**
 * Plugin Name: Custom Role Examples
 * Description: Sample code for custom roles and custom user capabilities
 * Version:     1.1.0
 * Author:      Pete Nelson pete@petenelson.com
 * Author URI:  https://petenelson.io
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/dashboard-widget.php';
require_once __DIR__ . '/includes/roles/hr.php';
require_once __DIR__ . '/includes/roles/book-editor.php';
require_once __DIR__ . '/includes/roles/administrator.php';
require_once __DIR__ . '/includes/roles/limited-admin.php';
require_once __DIR__ . '/includes/post-types/book.php';

// Global functionality
\Custom_Role_Examples\setup();

// Setup the dashboard widget
\Custom_Role_Examples\Dashboard_Widget\setup();

// Custom post types
\Custom_Role_Examples\Post_Types\Book\setup();

// Setup our roles
\Custom_Role_Examples\Roles\HR\setup();
\Custom_Role_Examples\Roles\Book_Editor\setup();
\Custom_Role_Examples\Roles\Administrator\setup();
\Custom_Role_Examples\Roles\Limited_Admin\setup();
