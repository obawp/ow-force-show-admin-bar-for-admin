<?php
/*
Plugin Name: OW Force Show Admin Bar for Admin
Plugin URI: https://obawp.com/plugin/ow-force-show-admin-bar-for-admin/
Description: Ensures the admin bar is always visible for users with administrative privileges, except in specific conditions.
Version: 0.2.1
Author: ObaWP
Author URI: https://obawp.com
Text Domain: ow-force-show-admin-bar-for-admin
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tested up to: 6.7
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action("init", "ow_force_show_admin_bar_for_admin");

/**
 * Forces the admin bar to be shown for admin users, except in specific scenarios.
 *
 * @return void
 */
function ow_force_show_admin_bar_for_admin() {
    // Check if the current user has administrative capabilities
    $is_admin = current_user_can('manage_options');

    // Validate and sanitize the 'elementor-preview' parameter
    $is_elementor_preview = isset($_GET['elementor-preview']) && wp_verify_nonce(
        sanitize_key($_GET['_wpnonce'] ?? ''),
        'elementor-preview-nonce'
    );

    // Validate and sanitize the 'preview' parameter
    $is_preview = (sanitize_text_field(wp_unslash($_GET['preview'] ?? '')) === 'true') && wp_verify_nonce(
        sanitize_key($_GET['_wpnonce'] ?? ''),
        'preview-nonce'
    );

    // Sanitize the request URI for customizer check
    $request_uri = sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'] ?? ''));
    $is_customize = strpos($request_uri, 'customize_changeset_uuid') !== false;

    // Show the admin bar for admins, except in Elementor preview or customizer changeset
    if (
        ($is_admin && !$is_elementor_preview && !$is_customize) ||
        ($is_admin && $is_preview)
    ) {
        add_filter("show_admin_bar", "__return_true");
    }
}