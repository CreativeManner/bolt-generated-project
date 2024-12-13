<?php

namespace BookneticAddon\Blocker\Providers;

class Installation
{
    public static function createTables()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'booknetic_blocked_slots';

        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `staff_id` int(11) NOT NULL,
            `date` date NOT NULL,
            `start_time` time NOT NULL,
            `end_time` time NOT NULL,
            `repeat` varchar(20) DEFAULT 'none',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `staff_id` (`staff_id`),
            KEY `date` (`date`)
        ) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function removeData()
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'booknetic_blocked_slots';
        $wpdb->query("DROP TABLE IF EXISTS `{$table_name}`");

        delete_option('booknetic_blocker_enabled');
        delete_option('booknetic_blocker_version');
    }

    public static function upgrade()
    {
        $current_version = get_option('booknetic_blocker_version', '0');
        $new_version = '1.0.12';

        if (version_compare($current_version, $new_version, '<')) {
            self::createTables();
            update_option('booknetic_blocker_version', $new_version);
        }
    }
}
