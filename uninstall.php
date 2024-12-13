<?php

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete the plugin's options
delete_option('booknetic_blocker_enabled');
delete_option('booknetic_blocker_threshold');
delete_option('booknetic_blocker_duration');
delete_option('booknetic_blocker_message');

// Remove the plugin's database table
global $wpdb;
$table_name = $wpdb->prefix . 'booknetic_blocked_slots';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

// Clear any cached data that may be in the database
wp_cache_flush();
