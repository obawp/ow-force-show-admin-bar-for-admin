<?php
/*
Plugin Name: OW Force show admin bar for admin
Plugin URI: https://obawp.com.br/plugin/always-show-admin-bar-to-admin/
Description: Always show the admin bar to Admin
Author: ObaWP
Version: 0.1
Author URI: https://obawp.com.br
*/

add_action("init", function () {
  $is_admin = current_user_can('manage_options');  // all user they have manage option will get 
  $elementor = isset($_GET['elementor-preview']);
  $preview = ($_GET['preview'] ?? false) == 'true';
  $customize = strpos($_SERVER['REQUEST_URI'], 'customize_changeset_uuid') !== false;
  if (
    ($is_admin && !$elementor && !$customize)
    || ($is_admin && $preview)
  ) {
    add_filter("show_admin_bar", "__return_true");
  } 
});