<?php

/*
 * Plugin Name: Booknetic Time Blocker
 * Plugin URI: https://www.booknetic.com/
 * Description: Time Blocker addon for Booknetic Appointment Booking System
 * Version: 1.0.12
 * Author: FS Code
 * Author URI: https://www.booknetic.com/
 * Text Domain: booknetic-blocker
 * Domain Path: /languages
 */

defined('ABSPATH') or exit;

add_action('plugins_loaded', function() {
    if (class_exists('\BookneticApp\Providers\Core\Bootstrap')) {
        require_once __DIR__ . '/vendor/autoload.php';
        require_once __DIR__ . '/App/BlockerAddon.php';
        
        $addon = new \BookneticAddon\Blocker\BlockerAddon();
        $addon->init();

        add_filter('bkntc_addons_load', function($addons) use ($addon) {
            $addons['blocker'] = $addon;
            return $addons;
        });
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Booknetic plugin is not active. Please activate Booknetic to use the Time Blocker addon.</p></div>';
        });
    }
}, 20);
